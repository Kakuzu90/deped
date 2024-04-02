<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Item;
use App\Models\EmployeeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ItemHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Request as ModelsRequest;

class RepairReturnController extends Controller
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
				"description" => $row->item->description,
				"status" => 0,
			];
		});
	}

	public function store(ModelsRequest $model, Request $request)
	{
		$request->validate([
			"form" => "required|array"
		]);

		DB::beginTransaction();

		try {

			if ($model->isToRepair()) {
				foreach ($request->form as $item) {
					if ($item["status"] !== 1) {
						DB::table("employee_items")
							->where("employee_id", $model->employee_id)
							->where("item_id", $item["item_id"])->update(["status" => EmployeeItem::TO_REPAIR, "returned_at" => Carbon::now()]);
						DB::table("items")->where("id", $item["item_id"])->update(["status" => $item["status"]]);
						DB::table("item_histories")->insert([
							"employee_id" => $model->employee_id,
							"item_id" => $item["item_id"],
							"quantity" => 1,
							"type" => ItemHistory::RETURNED,
							"status" => $item["status"],
							"created_at" => Carbon::now(),
						]);
					}
				}
			}

			if ($model->isToReturn()) {
				foreach ($request->form as $item) {
					DB::table("employee_items")
						->where("employee_id", $model->employee_id)
						->where("item_id", $item["item_id"])->update(["status" => EmployeeItem::RETURNED, "returned_at" => Carbon::now()]);
					DB::table("items")->where("id", $item["item_id"])->update(["status" => $item["status"]]);
					DB::table("item_histories")->insert([
						"employee_id" => $model->employee_id,
						"item_id" => $item["item_id"],
						"quantity" => 1,
						"type" => ItemHistory::RETURNED,
						"status" => $item["status"],
						"created_at" => Carbon::now(),
					]);
				}
			}

			$model->update([
				"accepted_by" => Auth::id(),
				"released_by" => Auth::id(),
				"accepted_at" => Carbon::now(),
				"released_at" => Carbon::now(),
				"status" => ModelsRequest::ACCEPTED
			]);

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
	public function update(ModelsRequest $model)
	{
		$model->update(["status" => ModelsRequest::REJECTED]);

		Session::flash("warning", ["Request Rejected", "You have successfully reject the request of " . $model->employee->full_name]);

		return response()->json([
			"redirect" => route("admin.requests.rejected")
		]);
	}
}
