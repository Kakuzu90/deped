<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Rules\UniqueEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index() {
        $user = Auth::user();
        return view("admin.profile", compact("user"));
    }

    public function general(Request $request) {
        $admin = Admin::where("id", Auth::id())->first();
        $request->validate([
            "name" => "required",
            "username" => ["required", new UniqueEntry("admins", "username", $admin->id)],
            "password" => "required"
        ]);

        if (!verifyMe($request->password)) {
            return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
        }

        $admin->update([
            "name" => $request->name,
            "username" => $request->username
        ]);

        $redirect = redirect()->back();
        if ($admin->wasChanged()) {
            $msg = ["Information Updated", "Your general information was successfully updated."];
            $redirect->with("info", $msg);
        }

        return $redirect;
    }

    public function password(Request $request) {
        $request->validate([
            "password" => "required|confirmed",
            "old" => "required"
        ]);

        if (!verifyMe($request->old)) {
            return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
        }

        $admin = Admin::where("id", Auth::id())->first();
        $admin->update([
            "password" => $request->password
        ]);

        $msg = ["Password Updated", "You have successfully updated your password."];

        return redirect()->back()->with("info", $msg);
    }
}
