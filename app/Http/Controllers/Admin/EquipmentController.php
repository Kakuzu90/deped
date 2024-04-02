<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Rules\UniqueEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class EquipmentController extends Controller
{
	public function index()
	{
		$equipments = Item::equipment()->latest()->get();
		return view("admin.equipment", compact("equipments"));
	}

	public function store(Request $request)
	{
		$request->validate([
			"name" => "required",
			"serial" => ["required", new UniqueEntry("items", "serial_no")],
			"model" => ["required", new UniqueEntry("items", "model_no")],
			"brand" => "required",
			"amount" => "required",
			"date_purchased" => "required|date|date_format:Y-m-d",
			"description" => "required",
		]);

		Item::create([
			"name" => $request->name,
			"serial_no" => $request->serial,
			"model_no" => $request->model,
			"brand" => $request->brand,
			"amount" => (float) str_replace(',', '', $request->amount),
			"quantity" => 1,
			"purchased_at" => $request->date_purchased,
			"description" => $request->description,
			"item_type" => Item::EQUIPMENT,
		]);

		$msg = ["Equipment Created", $request->name . " has been successfully created."];

		return redirect()->back()->with("success", $msg);
	}

	public function show(Item $equipment)
	{
		return $equipment;
	}

	public function update(Request $request, Item $equipment)
	{
		$request->validate([
			"name" => "required",
			"serial" => ["required", new UniqueEntry("items", "serial_no", $equipment->id)],
			"model" => ["required", new UniqueEntry("items", "model_no", $equipment->id)],
			"brand" => "required",
			"amount" => "required",
			"date_purchased" => "required|date|date_format:Y-m-d",
			"description" => "required",
			"status" => "required|numeric"
		]);

		$isRepair = $equipment->isRepair();

		$equipment->update([
			"name" => $request->name,
			"serial_no" => $request->serial,
			"model_no" => $request->model,
			"brand" => $request->brand,
			"amount" => (float) str_replace(',', '', $request->amount),
			"purchased_at" => $request->date_purchased,
			"description" => $request->description,
			"status" => $request->status
		]);

		$redirect = redirect()->back();
		if ($equipment->wasChanged()) {
			$msg = ["Equipment Updated", $equipment->name . " has been successfully updated."];
			$redirect->with("info", $msg);
		}

		return $redirect;
	}

	public function destroy(Request $request, Item $equipment)
	{
		$request->validate([
			"password" => "required"
		]);

		if (!verifyMe($request->password)) {
			return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
		}

		$equipment->update(["deleted_at" => Carbon::now()]);

		$msg = ["Equipment Removed", "The " . $equipment->name . " has been removed successfully."];

		return redirect()->back()->with("danger", $msg);
	}

	public function history(Item $item)
	{
		return view("admin.show.item", compact("item"));
	}
}
