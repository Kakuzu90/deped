<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use App\Rules\UniqueEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    public function index() {
        $employees = Employee::latest()->get();
        return view("admin.employee", compact("employees"));
    }

    public function store(Request $request) {
        $request->validate([
            "name" => "required",
            "username" => ["required",new UniqueEntry("employees", "username")],
            "password" => "required|confirmed",
            "position" => "required|numeric",
            "office" => "required|numeric",
        ]);

        Employee::create([
            "full_name"=> $request->name,
            "username"=> $request->username,
            "password"=> $request->password,
            "position_id" => $request->position,
            "office_id" => $request->office
        ]);

        $msg = ["Employee Created", $request->name . " has been successfully created."];

        return redirect()->back()->with("success", $msg);
    }

    public function show(Employee $employee) {
        return $employee;
    }

    public function update(Request $request, Employee $employee) {
        $request->validate([
            "name" => "required",
            "username" => ["required",new UniqueEntry("employees", "username", $employee->id)],
            "password" => "confirmed",
            "position" => "required|numeric",
            "office" => "required|numeric",
        ]);

        $update = [
            "full_name"=> $request->name,
            "username"=> $request->username,
            "position_id" => $request->position,
            "office_id" => $request->office
        ];

        if ($request->filled("password")) {
            $update["password"] = $request->password;
        }
        $employee->update($update);

        $redirect = redirect()->back();
        if ($employee->wasChanged()) {            
            $msg = ["Employee Updated", $employee->full_name. " has been successfully updated."];
            $redirect->with("info", $msg);
        }
        return $redirect;
    }

    public function destroy(Request $request, Employee $employee) {
        $request->validate([
            "password" => "required"
        ]);

        if (!verifyMe($request->password)) {
            return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
        }

        $employee->update(["deleted_at" => Carbon::now()]);

        $msg = ["Employee Removed", "The " . $employee->full_name . " has been removed successfully."];

        return redirect()->back()->with("danger", $msg);
    }

    public function profile(Employee $employee) {
        return view("admin.show.employee", compact("employee"));
    }

    public function account(Employee $employee) {
        if ($employee->verified_at) {
            $status = null;
            $msg = ["Account Updated", "You have successfully revert the account of " . $employee->full_name];
        }else {
            $status = Carbon::now();
            $msg = ["Account Updated", "You have successfully verify the account of " . $employee->full_name];
        }

        $employee->update(["verified_at" => $status]);

        return redirect()->back()->with("info", $msg);
    }
}
