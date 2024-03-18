<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Rules\UniqueEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OfficeController extends Controller
{
    public function index() {
        $offices = Office::latest()->get();
        return view("admin.office", compact("offices"));
    }

    public function store(Request $request) {
        $request->validate([
            "name" => ["required", new UniqueEntry("offices", "name")]
        ]);

        Office::create(["name" => $request->name]);

        $msg = ["Office Created", $request->name . " has been successfully created."];

        return redirect()->back()->with("success", $msg);
    }

    public function show(Office $office) {
        return $office;
    }

    public function update(Request $request, Office $office) {
        $request->validate([
            "name" => ["required", new UniqueEntry("offices", "name", $office->id)]
        ]);

        $old = $office->name;

        $office->update(["name" => $request->name]);

        $redirect = redirect()->back();
        if ($office->wasChanged()) {
            $msg = ["Office Updated", "Office updated from " . $old . " to " . $request->name . "."];
            $redirect->with("info", $msg);
        }

        return $redirect;
    }

    public function destroy(Request $request, Office $office) {
        $request->validate([
            "password" => "required"
        ]);

        if (!verifyMe($request->password)) {
            return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
        }

        $office->update(["deleted_at" => Carbon::now()]);

        $msg = ["Office Removed", "The " . $office->name . " has been removed successfully."];

        return redirect()->back()->with("danger", $msg);
    }
}
