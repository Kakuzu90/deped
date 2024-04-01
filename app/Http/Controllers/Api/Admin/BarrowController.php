<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\RequestItem;
use App\Models\EmployeeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Request as ModelsRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BarrowController extends Controller
{
	public function index(ModelsRequest $request)
	{
		return $request->items->map(function ($row) {
			return [
				"image" => asset("assets/images/add-item.png"),
				"id" => $row->id,
				"item_id" => $row->item_id,
				"name" => $row->item->name,
				"amount" => $row->item->amount,
				"brand" => $row->item->brand,
				"quantity" => $row->quantity,
				"max" => $row->item->quantity,
				"disabled" => $row->item->isEquipment(),
				"color" => "bg-" . $row->item->itemColor(),
				"text" => $row->item->itemType(),
				"status" => $row->status,
				"checkLoading" => false,
			];
		});
	}

	public function checkItem(ModelsRequest $request, RequestItem $item)
	{
		if ($item->item->isWorking()) {
			if ($item->item->isEquipment()) {
				$owned = EmployeeItem::where("item_id", $item->item_id)->working()->exists();
				if ($owned) {
					return response()->json([
						"item_status" => 3,
						"icon" => "mdi mdi-alert-decagram mdi-48px text-danger",
						"title" => "Item not available",
					]);
				}
				return response()->json([
					"item_status" => 2,
					"icon" => "mdi mdi-check-decagram mdi-48px text-success",
					"title" => "Item is available",
				]);
			}
			if ($item->item->isSupply()) {
				$checkStock = $item->item->quantity - $item->quantity;
				if ($item->quantity > $item->item->quantity) {
					return response()->json([
						"item_status" => 3,
						"icon" => "mdi mdi-alert-decagram mdi-48px text-danger",
						"title" => "Item is on low stocks, adjust the quantity",
						"sub_title" => "Only " . $item->item->quantity . " left"
					]);
				}
				if ($checkStock > 0) {
					return response()->json([
						"item_status" => 2,
						"icon" => "mdi mdi-check-decagram mdi-48px text-success",
						"title" => "Item is available",
						"sub_title" => "Only " . $checkStock . " left"
					]);
				}
				return response()->json([
					"item_status" => 3,
					"icon" => "mdi mdi-check-decagram mdi-48px text-success",
					"title" => "Item not available, low on stocks",
					"sub_title" => "No more stocks available"
				]);
			}
		}
		if ($item->item->isRepair()) {
			return response()->json([
				"item_status" => 3,
				"icon" => "mdi mdi-alert-decagram mdi-48px text-danger",
				"title" => "Item is currently on maintenances",
			]);
		}
		return response()->json([
			"item_status" => 3,
			"icon" => "mdi mdi-alert-decagram mdi-48px text-danger",
			"title" => "Item is no longer available",
		]);
	}

	public function accept(Request $request, ModelsRequest $model)
	{
		$request->validate([
			"form" => "required|array"
		]);

		DB::beginTransaction();

		try {
			foreach ($request->form as $item) {
				// 3 is for rejected or not available items
				if ($item["status"] === 3) {
					DB::table("request_items")->where("id", $item["id"])->delete();
				}
				// 2 is for accepted items
				if ($item["status"] === 2) {
					DB::table("employee_items")->insert([
						"employee_id" => $model->employee_id,
						"item_id" => $item["item_id"],
						"quantity" => $item["quantity"],
						"created_at" => Carbon::now(),
					]);
					DB::table("item_histories")->insert([
						"employee_id" => $model->employee_id,
						"item_id" => $item["item_id"],
						"quantity" => $item["quantity"],
						"type" => 1,
						"status" => 1,
						"created_at" => Carbon::now(),
					]);

					$model->update([
						"accepted_by" => Auth::id(),
						"released_by" => Auth::id(),
						"accepted_at" => Carbon::now(),
						"released_at" => Carbon::now(),
						"status" => ModelsRequest::ACCEPTED
					]);
				}
			}

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();

			Session::flash("danger", ["Request Error", "Something went wrong!"]);
		}

		Session::flash("success", ["Request Accepted", "You have successfully accept the request of " . $model->employee->full_name]);

		return response()->json([
			"redirect" => route("admin.requests.accepted")
		]);
	}

	public function reject(ModelsRequest $model)
	{
		$model->update(["status" => ModelsRequest::REJECTED]);

		Session::flash("warning", ["Request Rejected", "You have successfully reject the request of " . $model->employee->full_name]);

		return response()->json([
			"redirect" => route("admin.requests.rejected")
		]);
	}
}
