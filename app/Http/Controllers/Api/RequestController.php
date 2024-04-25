<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Employee;
use App\Models\ItemHistory;
use App\Models\RequestItem;
use App\Models\EmployeeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PendingResource;
use Illuminate\Support\Facades\Session;
use App\Models\Request as ModelsRequest;

class RequestController extends Controller
{
	public function getNew(Request $request)
	{
		$search = $request->input("search");

		$except = EmployeeItem::working()->whereHas("item", function ($query) {
			$query->where("item_type", Item::EQUIPMENT);
		})->pluck("item_id");
		$items = Item::working()->whereNotIn("id", $except);

		if ($search) {
			$items->where("name", "LIKE", "%$search%");
		}

		$items = $items->orderBy("item_type", "asc")->latest()->take($search ? null : 10)->get();

		return $items->filter(function ($item) {
			return $item->quantity() > 0;
		})->map(function ($item) {
			return [
				"image" => asset("assets/images/add-item.png"),
				"id" => $item->id,
				"stock" => $item->stock_no,
				"unit" => $item->unit,
				"name" => $item->name,
				"brand" => $item->brand,
				"amount" => $item->amount,
				"quantity" => $item->isEquipment() ? $item->quantity : $item->quantity(),
				"disabled" => $item->isEquipment(),
				"color" => "bg-" . $item->itemColor(),
				"text" => $item->itemType(),
			];
		});
	}

	public function storeNew(Employee $employee, Request $request)
	{
		$request->validate([
			"form" => "required|array"
		]);

		DB::beginTransaction();

		try {
			$modelID = DB::table("requests")->insertGetId([
				"employee_id" => $employee->id,
				"request_type" => ModelsRequest::TO_BARROW,
				"accepted_by" => Auth::id(),
				"released_by" => Auth::id(),
				"accepted_at" => Carbon::now(),
				"released_at" => Carbon::now(),
				"status" => ModelsRequest::ACCEPTED
			]);

			foreach ($request->form as $item) {
				DB::table("request_items")->insert([
					"request_id" => $modelID,
					"item_id" => $item["item_id"],
					"quantity" => $item["quantity"],
				]);

				$owned = DB::table("employee_items")->where([
					"employee_id" => $employee->id,
					"item_id" => $item["item_id"],
				])->first();

				if ($owned) {
					$itemModel = Item::find($item["item_id"]);
					$quantity = $item["quantity"];
					if ($itemModel->isSupply()) {
						$quantity += $owned->quantity;
					}
					DB::table("employee_items")->where([
						"employee_id" => $employee->id,
						"item_id" => $item["item_id"],
					])->update([
						"quantity" => $quantity,
						"created_at" => Carbon::now(),
						"returned_at" => null,
						"status" => EmployeeItem::ON_HAND,
					]);
				} else {
					DB::table("employee_items")->insert([
						"employee_id" => $employee->id,
						"item_id" => $item["item_id"],
						"quantity" => $item["quantity"],
						"created_at" => Carbon::now(),
						"returned_at" => null,
						"status" => EmployeeItem::ON_HAND,
					]);
				}

				DB::table("item_histories")->insert([
					"employee_id" => $employee->id,
					"item_id" => $item["item_id"],
					"request_id" => $modelID,
					"quantity" => $item["quantity"],
					"type" => 1,
					"status" => 1,
					"created_at" => Carbon::now(),
				]);
			}

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();

			Session::flash("danger", ["Request Error", "Something went wrong!"]);
		}

		Session::flash("success", ["Request Saved", "You have successfully submitted a new request with " . count($request->form) . " items."]);

		return response()->json(["redirect" => route("admin.employees.profile", $employee->id)]);
	}

	public function getRepair(Employee $employee, Request $request)
	{
		$search = $request->input("search");

		$items = EmployeeItem::myItem($employee->id)->working()->whereHas("item", function ($query) {
			$query->where("item_type", Item::EQUIPMENT);
		});

		if ($search) {
			$items->whereHas("item", function ($query) use ($search) {
				$query->where("name", "like", "%$search%");
			});
		}

		$items = $items->latest()->take($search ? null : 10)->get();

		return $items->map(function ($row) {
			return [
				"image" => asset("assets/images/add-item.png"),
				"id" => $row->item_id,
				"stock" => $row->item->stock_no,
				"unit" => $row->item->unit,
				"name" => $row->item->name,
				"brand" => $row->item->brand,
				"date_received" => $row->created_at->format("F d, Y")
			];
		});
	}

	public function storeRepair(Employee $employee, Request $request)
	{
		$request->validate([
			"form" => "required|array"
		]);

		$model = ModelsRequest::create([
			"employee_id" => $employee->id,
			"request_type" => ModelsRequest::TO_REPAIR,
			"accepted_by" => Auth::id(),
			"released_by" => Auth::id(),
			"accepted_at" => Carbon::now(),
			"released_at" => Carbon::now(),
			"status" => ModelsRequest::ACCEPTED
		]);

		DB::beginTransaction();

		try {

			foreach ($request->form as $item) {
				DB::table("request_items")->insert([
					"request_id" => $model->id,
					"item_id" => $item["item_id"],
					"quantity" => 1,
				]);

				DB::table("employee_items")
					->where("employee_id", $model->employee_id)
					->where("item_id", $item["item_id"])->update(["status" => EmployeeItem::TO_REPAIR, "returned_at" => Carbon::now()]);
				DB::table("items")->where("id", $item["item_id"])->update(["status" => Item::REPAIR]);
				DB::table("item_histories")->insert([
					"employee_id" => $model->employee_id,
					"item_id" => $item["item_id"],
					"request_id" => $model->id,
					"quantity" => 1,
					"type" => ItemHistory::RETURNED,
					"status" => Item::REPAIR,
					"created_at" => Carbon::now(),
				]);
			}

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();

			Session::flash("danger", ["Request Error", "Something went wrong!"]);
		}

		Session::flash("success", ["Request Saved", "You have successfully submitted a repair request."]);

		return response()->json(["redirect" => route("admin.employees.profile", $employee->id)]);
	}

	public function getReturn(Employee $employee, Request $request)
	{
		$search = $request->input("search");

		$items = EmployeeItem::myItem($employee->id)->working()->whereHas("item", function ($query) {
			$query->where("item_type", Item::EQUIPMENT);
		});

		if ($search) {
			$items->whereHas("item", function ($query) use ($search) {
				$query->where("name", "like", "%$search%");
			});
		}

		$items = $items->latest()->take($search ? null : 10)->get();

		return $items->map(function ($row) {
			return [
				"image" => asset("assets/images/add-item.png"),
				"id" => $row->item_id,
				"stock" => $row->item->stock_no,
				"unit" => $row->item->unit,
				"name" => $row->item->name,
				"amount" => $row->item->amount,
				"brand" => $row->item->brand,
				"quantity" => $row->quantity,
				"max" => $row->item->isEquipment() ? $row->item->quantity : $row->item->quantity(),
				"disabled" => $row->item->isEquipment(),
				"color" => "bg-" . $row->item->itemColor(),
				"text" => $row->item->itemType(),
				"status" => 0,
			];
		});
	}

	public function storeReturn(Employee $employee, Request $request)
	{
		$request->validate([
			"form" => "required|array"
		]);

		$model = ModelsRequest::create([
			"employee_id" => $employee->id,
			"request_type" => ModelsRequest::TO_RETURN,
			"accepted_by" => Auth::id(),
			"released_by" => Auth::id(),
			"accepted_at" => Carbon::now(),
			"released_at" => Carbon::now(),
			"status" => ModelsRequest::ACCEPTED
		]);

		DB::beginTransaction();

		try {

			foreach ($request->form as $item) {
				DB::table("request_items")->insert([
					"request_id" => $model->id,
					"item_id" => $item["item_id"],
					"quantity" => 1,
				]);

				DB::table("employee_items")
					->where("employee_id", $model->employee_id)
					->where("item_id", $item["item_id"])->update(["status" => EmployeeItem::RETURNED, "returned_at" => Carbon::now()]);
				DB::table("items")->where("id", $item["item_id"])->update(["status" => $item["status"]]);
				DB::table("item_histories")->insert([
					"employee_id" => $model->employee_id,
					"item_id" => $item["item_id"],
					"request_id" => $model->id,
					"quantity" => 1,
					"type" => ItemHistory::RETURNED,
					"status" => $item["status"],
					"created_at" => Carbon::now(),
				]);
			}

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();

			Session::flash("danger", ["Request Error", "Something went wrong!"]);
		}

		Session::flash("success", ["Request Saved", "You have successfully submitted a return request."]);

		return response()->json(["redirect" => route("admin.employees.profile", $employee->id)]);
	}

	public function getUpdate(ModelsRequest $model)
	{
		return response()->json([
			"data" => $model->items->map(function ($row) {
				return [
					"image" => asset("assets/images/add-item.png"),
					"item_id" => $row->item_id,
					"name" => $row->item->name,
					"brand" => $row->item->brand,
					"amount" => $row->item->amount,
					"stock" => $row->item->stock_no,
					"unit" => $row->item->unit,
					"quantity" => $row->quantity,
					"max" => $row->item->isEquipment() ? $row->item->quantity : $row->item->quantity(),
					"disabled" => $row->item->isEquipment(),
					"color" => "bg-" . $row->item->itemColor(),
					"text" => $row->item->itemType(),
					"date" => $row->request->accepted_at->format("F d, Y")
				];
			})
		]);
	}

	public function edit(ModelsRequest $model, Request $request)
	{
		$search = $request->input("search");

		$except = EmployeeItem::working()->where("employee_id", "!=", $model->employee_id)->whereHas("item", function ($query) {
			$query->where("item_type", Item::EQUIPMENT);
		})->pluck("item_id");
		$items = Item::working()->whereNotIn("id", $except);

		if ($model->isToBarrow()) {
			if ($search) {
				$items->where("name", "LIKE", "%$search%");
			}
		}
		if ($model->isToRepair()) {
			$items = EmployeeItem::myItem($model->employee_id)->where("status", "!=", EmployeeItem::RETURNED)->whereHas("item", function ($query) {
				$query->where("item_type", Item::EQUIPMENT);
			});
			if ($search) {
				$items->whereHas("item", function ($query) use ($search) {
					$query->where("name", "like", "%$search%");
				});
			}
		}
		if ($model->isToReturn()) {
			$items = EmployeeItem::myItem($model->employee_id)->whereHas("item", function ($query) {
				$query->where("item_type", Item::EQUIPMENT);
			});
			if ($search) {
				$items->whereHas("item", function ($query) use ($search) {
					$query->where("name", "like", "%$search%");
				});
			}
		}

		$items = $items->latest()->take($search ? null : 10)->get();

		if ($model->isToBarrow()) {
			return $items->filter(function ($item) {
				return $item->quantity() > 0;
			})->map(function ($item) {
				return [
					"image" => asset("assets/images/add-item.png"),
					"id" => $item->id,
					"stock" => $item->stock_no,
					"unit" => $item->unit,
					"name" => $item->name,
					"brand" => $item->brand,
					"amount" => $item->amount,
					"quantity" => $item->isEquipment() ? $item->quantity : $item->quantity(),
					"disabled" => $item->isEquipment(),
					"color" => "bg-" . $item->itemColor(),
					"text" => $item->itemType(),
				];
			});
		}
		if ($model->isToRepair()) {
			return $items->map(function ($row) {
				return [
					"image" => asset("assets/images/add-item.png"),
					"id" => $row->item_id,
					"stock" => $row->item->stock_no,
					"unit" => $row->item->unit,
					"name" => $row->item->name,
					"brand" => $row->item->brand,
					"status" => $row->statusText(),
					"color" => "text-" . $row->statusColor(),
					"date_received" => $row->item->created_at->format("F d, Y")
				];
			});
		}
		if ($model->isToReturn()) {
			return $items->map(function ($row) {
				return [
					"image" => asset("assets/images/add-item.png"),
					"id" => $row->item_id,
					"name" => $row->item->name,
					"brand" => $row->item->brand,
					"quantity" => $row->quantity,
					"max" => $row->item->isEquipment() ? $row->item->quantity : $row->item->quantity(),
					"disabled" => $row->item->isEquipment(),
					"color" => "bg-" . $row->item->itemColor(),
					"text" => $row->item->itemType(),
				];
			});
		}
	}
	public function update(ModelsRequest $model, Request $request)
	{
		$request->validate([
			"form" => "required|array",
			"delete" => "nullable|array"
		]);

		if (count($request->delete) > 0) {
			RequestItem::where("request_id", $model->id)->whereIn("item_id", $request->delete)->delete();
		}

		$model->update([
			"accepted_by" => Auth::id(),
			"released_by" => Auth::id(),
			"accepted_at" => Carbon::now(),
			"released_at" => Carbon::now(),
		]);

		DB::beginTransaction();

		try {
			foreach ($request->form as $item) {

				$quantity = $item["quantity"];

				if ($model->isToBarrow()) {
					$owned = DB::table("employee_items")->where([
						"employee_id" => $model->employee_id,
						"item_id" => $item["item_id"],
					])->first();

					if ($owned) {
						$itemModel = Item::find($item["item_id"]);
						$requestModel = RequestItem::where("request_id", $model->id)->where("item_id", $item["item_id"])->first();
						if ($itemModel->isSupply()) {
							$quantity += ($owned->quantity - $requestModel->quantity);
						}
						DB::table("employee_items")->where([
							"employee_id" => $model->employee_id,
							"item_id" => $item["item_id"],
						])->update([
							"quantity" => $quantity,
							"created_at" => Carbon::now(),
							"returned_at" => null,
							"status" => EmployeeItem::ON_HAND,
						]);
					} else {
						DB::table("employee_items")->insert([
							"employee_id" => $model->employee_id,
							"item_id" => $item["item_id"],
							"quantity" => $quantity,
							"created_at" => Carbon::now(),
							"returned_at" => null,
							"status" => EmployeeItem::ON_HAND,
						]);
					}

					DB::table("item_histories")
						->where("request_id", $model->id)
						->update([
							"quantity" => $quantity,
							"type" => 1,
							"status" => 1,
						]);
				}

				// if ($model->isToRepair()) {
				// 	DB::table("employee_items")
				// 		->where("employee_id", $model->employee_id)
				// 		->where("item_id", $item["item_id"])->update(["status" => EmployeeItem::TO_REPAIR, "returned_at" => Carbon::now()]);
				// 	DB::table("items")->where("id", $item["item_id"])->update(["status" => Item::REPAIR]);
				// 	DB::table("item_histories")
				// 		->where("request_id", $model->id)
				// 		->update([
				// 			"quantity" => 2,
				// 			"type" => ItemHistory::RETURNED,
				// 			"status" => Item::REPAIR,
				// 		]);
				// }

				// if ($model->isToReturn()) {
				// 	DB::table("employee_items")
				// 		->where("employee_id", $model->employee_id)
				// 		->where("item_id", $item["item_id"])->update(["status" => EmployeeItem::RETURNED, "returned_at" => Carbon::now()]);
				// 	DB::table("items")->where("id", $item["item_id"])->update(["status" => $item["status"]]);

				// 	DB::table("item_histories")
				// 		->where("request_id", $model->id)
				// 		->update([
				// 			"quantity" => 1,
				// 			"type" => ItemHistory::RETURNED,
				// 			"status" => $item["status"],
				// 		]);
				// }

				DB::table("request_items")->updateOrInsert(
					["request_id" => $model->id, "item_id" => $item["item_id"]],
					["quantity" => $quantity]
				);
			}

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			//return $e->getMessage();
			Session::flash("danger", ["Request Error", "Something went wrong!"]);
		}

		Session::flash("success", ["Request Updated", "You have successfully updated the request of " . $model->employee->full_name]);

		return response()->json(["redirect" => route("admin.requests.edit", $model->id)]);
	}
}
