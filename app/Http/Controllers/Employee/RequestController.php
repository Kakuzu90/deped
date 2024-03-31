<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Request;

class RequestController extends Controller
{
	public function index()
	{
		$requests = Request::myRequest()->latest()->get();
		return view("employee.request.index", compact("requests"));
	}

	public function new()
	{
		return view("employee.request.new");
	}

	public function repair()
	{
		return view("employee.request.repair");
	}

	public function return()
	{
		return view("employee.request.return");
	}

	public function edit(Request $request)
	{
		return view("employee.request.update", compact("request"));
	}
}
