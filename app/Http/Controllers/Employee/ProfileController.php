<?php

namespace App\Http\Controllers\Employee;

use App\Rules\UniqueEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index() {
        $employee = Auth::guard("employee")->user();
        $offices = Office::latest()->get();
        $positions = Position::latest()->get();
        return view("employee.profile", compact("employee", "offices", "positions"));
    }

    public function general(Request $request) {
        $request->validate([
            "name" => "required",
            "username" => ["required",new UniqueEntry("employees", "username", Auth::guard("employee")->id())],
            "password" => "required",
            "position" => "required|numeric",
            "office" => "required|numeric",
        ]);

        if (!password_verify($request->password, Auth::guard("employee")->user()->password)) {
            return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
        }

        $employee = Employee::where("id", Auth::guard("employee")->id())->first();

        $employee->update([
            "full_name"=> $request->name,
            "username"=> $request->username,
            "position_id" => $request->position,
            "office_id" => $request->office
        ]);

        if ($employee->wasChanged()) {
            $msg = ["Account Updated", "You have successfully updated your general information."];
            return redirect()->back()->with("success", $msg);
        }

        return redirect()->back();
    }

    public function password(Request $request) {
        $request->validate([
            "current" => "required",
            "password" => "required|confirmed"
        ]);

        if (!password_verify($request->current, Auth::guard("employee")->user()->password)) {
            return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
        }

        $employee = Employee::where("id", Auth::guard("employee")->id())->first();

        $employee->update(["password" => $request->password]);

        if ($employee->wasChanged()) {
            $msg = ["Account Updated", "You have successfully updated your password."];
            return redirect()->back()->with("success", $msg);
        }

        return redirect()->back();
    }
}
