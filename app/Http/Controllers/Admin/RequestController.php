<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
	public function index()
	{
		$requests = ModelsRequest::pending()->latest()->get();
		return view("admin.request", compact("requests"));
	}
}
