<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeItem;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index(Request $request)
	{

		$search = $request->input("search");
		$status = $request->input("status");
		$type = $request->input("type");

		$items = EmployeeItem::whereHas("item", function ($query) use ($search, $type) {
			if ($search) {
				$query->where("name", "like", "%$search%");
			}
			if ($type && $type !== "all") {
				$query->where("item_type", $type);
			}
		});

		if ($status && $status !== "all") {
			$items->where("status", $status);
		}

		$items = $items->latest()->paginate(15)->withQueryString();

		return view("employee.home", compact("items"));
	}
}
