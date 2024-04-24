<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$data["table"] = ModelsRequest::latest()->get();
		return view("admin.request.index", compact("data"));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Employee $employee)
	{
		return view("admin.request.new", compact("employee"));
	}

	public function repair(Employee $employee)
	{
		return view("admin.request.repair", compact("employee"));
	}

	public function returned(Employee $employee)
	{
		return view("admin.request.return", compact("employee"));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function show(ModelsRequest $request)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function edit(ModelsRequest $request)
	{
		return view("admin.request.update", compact("request"));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $form
	 * @param  \App\Models\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $form, ModelsRequest $request)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(ModelsRequest $request)
	{
		//
	}
}
