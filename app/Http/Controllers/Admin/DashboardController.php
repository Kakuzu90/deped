<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Office;
use App\Models\Request;
use App\Models\Employee;
use App\Models\Position;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
	public function __invoke()
	{
		$data = [
			"position" => Position::count(),
			"office" => Office::count(),
			"employee" =>  Employee::count(),
			"equipment" => Item::equipment()->count(),
			"supply" => Item::supply()->count(),
			"requests" => Request::pending()->latest()->get(),
		];
		return view("admin.dashboard", compact("data"));
	}
}
