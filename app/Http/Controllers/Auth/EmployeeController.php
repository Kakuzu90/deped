<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Position;
use App\Rules\UniqueEntry;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
	public function index()
	{
		return view("auth.employee.login");
	}

	public function login(Request $request)
	{
		$request->validate([
			"username" => "required",
			"password" => "required",
		]);

		if (Auth::guard("employee")->attempt(["username" => $request->username, "password" => $request->password], $request->remember)) {
			if (!Auth::guard("employee")->user()->verified_at) {
				Auth::guard("employee")->logout();
				return redirect()->back()
					->withInput()
					->withErrors(["login_error" => "The provided credentials are not yet verified by the administrator."]);
			}
			return redirect()->route("employee.home")->withStatus("welcome");
		}

		return redirect()->back()
			->withInput()
			->withErrors(["login_error" => "The provided credentials didn't match any of our records."]);
	}

	public function registerView()
	{
		$positions = Position::latest()->get();
		$offices = Office::latest()->get();
		return view("auth.employee.register", compact("positions", "offices"));
	}

	public function registered(Request $request)
	{
		$request->validate([
			"fullname" => "required",
			"username" => ["required", new UniqueEntry("employees", "username")],
			"password" => "required|confirmed",
			"position" => "required|numeric",
			"office" => "required|numeric"
		]);

		Employee::create([
			"full_name" => $request->fullname,
			"username" => $request->username,
			"password" => $request->password,
			"office_id" => $request->office,
			"position_id" => $request->position
		]);

		$msg = ["Registration Complete", "You have successfully registered to the system, please wait till the administrator approved your account."];
		return redirect()->back()->with("success", $msg);
	}

	public function logout()
	{
		Auth::guard("employee")->logout();

		return redirect()->route("login");
	}
}
