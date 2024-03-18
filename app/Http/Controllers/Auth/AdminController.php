<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() {
        return view("auth.login");
    }

    public function login(Request $request) {
        $request->validate([
            "username" => "required",
            "password" => "required",
        ]);

        if (Auth::guard("web")->attempt(["username"=> $request->username, "password" => $request->password], $request->remember)) {
            return redirect()->intended(route("admin.dashboard"))->withStatus("welcome");
        }

        return redirect()->back()
            ->withInput()
            ->withErrors("login_error", "The provided credentials didn't match any of our records.");
    }

    public function logout() {
        Auth::logout();
        return redirect()->route("admin.login");
    }
}
