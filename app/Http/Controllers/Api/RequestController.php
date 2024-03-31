<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PendingResource;
use App\Models\EmployeeItem;
use App\Models\Item;
use App\Models\Request as ModelsRequest;
use App\Models\RequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

		return $items->map(function ($item) {
			return [
				"image" => asset("assets/images/add-item.png"),
				"id" => $item->id,
				"name" => $item->name,
				"brand" => $item->brand,
				"quantity" => $item->quantity,
				"disabled" => $item->isEquipment(),
				"color" => "bg-" . $item->itemColor(),
				"text" => $item->itemType(),
			];
		});
	}

	public function storeNew(Request $request)
	{
		$request->validate([
			"form" => "required|array"
		]);

		$model = ModelsRequest::create([
			"employee_id" => Auth::guard("employee")->id(),
			"request_type" => ModelsRequest::TO_BARROW,
		]);

		foreach ($request->form as $item) {
			RequestItem::create([
				"request_id" => $model->id,
				"item_id" => $item["item_id"],
				"quantity" => $item["quantity"],
			]);
		}

		return response()->json(["redirect" => route("employee.requests.index")]);
	}

	public function getRepair(Request $request)
	{
		$search = $request->input("search");

		$items = EmployeeItem::myItem()->working()->whereHas("item", function ($query) {
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
				"name" => $row->item->name,
				"brand" => $row->item->brand,
				"date_received" => $row->item->created_at->format("F d, Y")
			];
		});
	}

	public function storeRepair(Request $request)
	{
		$request->validate([
			"form" => "required|array"
		]);

		$model = ModelsRequest::create([
			"employee_id" => Auth::guard("employee")->id(),
			"request_type" => ModelsRequest::TO_REPAIR,
		]);

		foreach ($request->form as $item) {
			RequestItem::create([
				"request_id" => $model->id,
				"item_id" => $item["item_id"],
				"quantity" => 1,
			]);
		}

		return response()->json(["redirect" => route("employee.requests.index")]);
	}

	public function getReturn(Request $request)
	{
		$search = $request->input("search");

		$items = EmployeeItem::myItem()->working();

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
				"name" => $row->item->name,
				"brand" => $row->item->brand,
				"quantity" => $row->quantity,
				"max" => $row->item->quantity,
				"disabled" => $row->item->isEquipment(),
				"color" => "bg-" . $row->item->itemColor(),
				"text" => $row->item->itemType(),
			];
		});
	}

	public function storeReturn(Request $request)
	{
		$request->validate([
			"form" => "required|array"
		]);

		$model = ModelsRequest::create([
			"employee_id" => Auth::guard("employee")->id(),
			"request_type" => ModelsRequest::TO_RETURN,
		]);

		foreach ($request->form as $item) {
			RequestItem::create([
				"request_id" => $model->id,
				"item_id" => $item["item_id"],
				"quantity" => $item["quantity"],
			]);
		}

		return response()->json(["redirect" => route("employee.requests.index")]);
	}

	public function getUpdate(ModelsRequest $model)
	{
		return $model->items->map(function ($row) {
			return [
				"image" => asset("assets/images/add-item.png"),
				"item_id" => $row->item_id,
				"name" => $row->item->name,
				"brand" => $row->item->brand,
				"quantity" => $row->quantity,
				"max" => $row->item->quantity,
				"disabled" => $row->item->isEquipment(),
				"color" => "bg-" . $row->item->itemColor(),
				"text" => $row->item->itemType(),
			];
		});
	}

	public function edit(ModelsRequest $model, Request $request)
	{
		$search = $request->input("search");

		$except = EmployeeItem::working()->whereHas("item", function ($query) {
			$query->where("item_type", Item::EQUIPMENT);
		})->pluck("item_id");
		$items = Item::working()->whereNotIn("id", $except);

		if ($model->isToBarrow()) {
			if ($search) {
				$items->where("name", "LIKE", "%$search%");
			}
		}
		if ($model->isToRepair()) {
			$items = EmployeeItem::myItem()->working()->whereHas("item", function ($query) {
				$query->where("item_type", Item::EQUIPMENT);
			});
			if ($search) {
				$items->whereHas("item", function ($query) use ($search) {
					$query->where("name", "like", "%$search%");
				});
			}
		}
		if ($model->isToReturn()) {
			$items = EmployeeItem::myItem()->working();
			if ($search) {
				$items->whereHas("item", function ($query) use ($search) {
					$query->where("name", "like", "%$search%");
				});
			}
		}

		$items = $items->latest()->take($search ? null : 10)->get();

		if ($model->isToBarrow()) {
			return $items->map(function ($item) {
				return [
					"image" => asset("assets/images/add-item.png"),
					"id" => $item->id,
					"name" => $item->name,
					"brand" => $item->brand,
					"quantity" => $item->quantity,
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
					"name" => $row->item->name,
					"brand" => $row->item->brand,
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
					"max" => $row->item->quantity,
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

		foreach ($request->form as $item) {
			RequestItem::updateOrCreate(
				["request_id" => $model->id, "item_id" => $item["item_id"]],
				["quantity" => $item["quantity"]]
			);
		}

		return response()->json(["redirect" => route("employee.requests.index")]);
	}
}
