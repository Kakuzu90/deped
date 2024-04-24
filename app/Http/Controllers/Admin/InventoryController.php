<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Rules\UniqueEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$items = Item::latest()->get();
		return view("admin.inventory", compact("items"));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$request->validate([
			"name" => "required",
			"stock_no" => ["required", new UniqueEntry("items", "stock_no")],
			"unit" => "required",
			"quantity" => "required|numeric",
			"brand" => "required",
			"amount" => "required",
			"item_type" => "required|numeric",
			"place_origin" => "required",
			"date_purchased" => "required|date|date_format:Y-m-d",
		]);

		Item::create([
			"name" => $request->name,
			"stock_no" => $request->stock_no,
			"unit" => $request->unit,
			"place_origin" => $request->place_origin,
			"brand" => $request->brand,
			"amount" => (float) str_replace(',', '', $request->amount),
			"quantity" => $request->item_type === 1 ? 1 : $request->quantity,
			"purchased_at" => $request->date_purchased,
			"description" => $request->description,
			"item_type" => $request->item_type,
		]);

		$msg = ["Item Created", $request->name . " has been successfully created."];

		return redirect()->back()->with("success", $msg);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Item  $item
	 * @return \Illuminate\Http\Response
	 */
	public function show(Item $item)
	{
		return $item;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Item  $item
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Item $item)
	{
		$request->validate([
			"name" => "required",
			"stock_no" => ["required", new UniqueEntry("items", "stock_no", $item->id)],
			"unit" => "required",
			"quantity" => "required|numeric",
			"brand" => "required",
			"amount" => "required",
			"item_type" => "required|numeric",
			"place_origin" => "required",
			"date_purchased" => "required|date|date_format:Y-m-d",
			"status" => "required|numeric"
		]);

		$item->update([
			"name" => $request->name,
			"stock_no" => $request->stock_no,
			"unit" => $request->unit,
			"place_origin" => $request->place_origin,
			"brand" => $request->brand,
			"amount" => (float) str_replace(',', '', $request->amount),
			"quantity" => $request->item_type === 1 ? 1 : $request->quantity,
			"purchased_at" => $request->date_purchased,
			"description" => $request->description,
			"item_type" => $request->item_type,
			"status" => $request->status
		]);

		$redirect = redirect()->back();
		if ($item->wasChanged()) {
			$msg = ["Item Updated", $item->name . " has been successfully updated."];
			$redirect->with("info", $msg);
		}

		return $redirect;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Item  $item
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, Item $item)
	{
		$request->validate([
			"password" => "required"
		]);

		if (!verifyMe($request->password)) {
			return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
		}

		$item->update(["deleted_at" => Carbon::now()]);
		$msg = ["Item Removed", "The " . $item->name . " has been removed successfully."];
		return redirect()->back()->with("danger", $msg);
	}

	public function history(Item $item)
	{
		return view("admin.show.item", compact("item"));
	}
}
