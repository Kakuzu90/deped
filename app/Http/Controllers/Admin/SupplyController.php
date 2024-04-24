<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class SupplyController extends Controller
{
	public function index()
	{
		$items = Item::latest()->get();
		return view("admin.supply", compact("items"));
	}

	public function store(Request $request)
	{
		$request->validate([
			"name" => "required",
			"brand" => "required",
			"amount" => "required",
			"quantity" => "required|numeric",
			"date_purchased" => "required|date|date_format:Y-m-d",
		]);

		Item::create([
			"name" => $request->name,
			"brand" => $request->brand,
			"amount" => (float) str_replace(',', '', $request->amount),
			"quantity" => $request->quantity,
			"purchased_at" => $request->date_purchased,
			"item_type" => Item::SUPPLY,
		]);

		$msg = ["Supply Created", $request->name . " has been successfully created."];

		return redirect()->back()->with("success", $msg);
	}

	public function show(Item $supply)
	{
		return $supply;
	}

	public function update(Request $request, Item $supply)
	{
		$request->validate([
			"name" => "required",
			"brand" => "required",
			"amount" => "required",
			"quantity" => "required|numeric",
			"date_purchased" => "required|date|date_format:Y-m-d",
		]);

		$supply->update([
			"name" => $request->name,
			"brand" => $request->brand,
			"amount" => (float) str_replace(',', '', $request->amount),
			"quantity" => $request->quantity,
			"purchased_at" => $request->date_purchased
		]);

		$redirect = redirect()->back();
		if ($supply->wasChanged()) {
			$msg = ["Supply Updated", $supply->name . " has been successfully updated."];
			$redirect->with("info", $msg);
		}

		return $redirect;
	}

	public function destroy(Request $request, Item $supply)
	{
		$request->validate([
			"password" => "required"
		]);

		if (!verifyMe($request->password)) {
			return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
		}

		$supply->update(["deleted_at" => Carbon::now()]);

		$msg = ["Supply Removed", "The " . $supply->name . " has been removed successfully."];

		return redirect()->back()->with("danger", $msg);
	}

	public function history(Item $item)
	{
		return view("admin.show.item", compact("item"));
	}
}
