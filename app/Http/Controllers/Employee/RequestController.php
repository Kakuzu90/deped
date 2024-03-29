<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

class RequestController extends Controller
{
	public function __invoke()
	{
		return view("employee.item");
	}
}
