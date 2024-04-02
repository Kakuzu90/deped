<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
	public function index()
	{
		$data["requests"] = ModelsRequest::pending()->latest()->get();
		$data["barrow"] = ModelsRequest::pending()->barrow()->count();
		$data["return"] = ModelsRequest::pending()->returned()->count();
		$data["repair"] = ModelsRequest::pending()->repair()->count();
		return view("admin.request.pending", compact("data"));
	}
	public function accepted()
	{
		$data["requests"] = ModelsRequest::accepted()->latest()->get();
		$data["barrow"] = ModelsRequest::accepted()->barrow()->count();
		$data["return"] = ModelsRequest::accepted()->returned()->count();
		$data["repair"] = ModelsRequest::accepted()->repair()->count();
		return view("admin.request.accepted", compact("data"));
	}
	public function rejected()
	{
		$data["requests"] = ModelsRequest::rejected()->latest()->get();
		$data["barrow"] = ModelsRequest::rejected()->barrow()->count();
		$data["return"] = ModelsRequest::rejected()->returned()->count();
		$data["repair"] = ModelsRequest::rejected()->repair()->count();
		return view("admin.request.rejected", compact("data"));
	}
	public function barrow(ModelsRequest $request)
	{
		if (!$request->isToBarrow() || !$request->isPending()) {
			abort(404);
		}
		return view("admin.request.barrow", compact("request"));
	}
	public function repairReturned(ModelsRequest $request)
	{
		if ($request->isToBarrow() || !$request->isPending()) {
			abort(404);
		}
		return view("admin.request.return-repair", compact("request"));
	}
}
