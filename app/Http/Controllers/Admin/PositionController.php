<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Position;
use App\Rules\UniqueEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PositionController extends Controller
{
    public function index() {
        $positions = Position::latest()->get();
        return view("admin.position", compact("positions"));
    }

    public function store(Request $request) {
        $request->validate([
            "name" => ["required", new UniqueEntry("positions", "name")],
        ]);

        Position::create(["name" => $request->name]);

        $msg = ["Position Created", $request->name . " has been successfully created."];

        return redirect()->back()->with("success", $msg);
    }

    public function show(Position $position) {
        return $position;
    }

    public function update(Request $request, Position $position) {
        $request->validate([
            "name" => ["required", new UniqueEntry("positions", "name", $position->id)],
        ]);

        $old = $position->name;

        $position->update(["name"=> $request->name]);

        $redirect = redirect()->back();
        if ($position->wasChanged()) {
            $msg = ["Position Updated", "Position updated from " . $old . " to " . $request->name . "."];
            $redirect->with("info", $msg);
        }

        return $redirect;
    }

    public function destroy(Request $request, Position $position) {
        $request->validate([
            "password" => "required"
        ]);

        if (!verifyMe($request->password)) {
            return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
        }

        $position->update(["deleted_at" => Carbon::now()]);
        $msg = ["Position Removed", "The " . $position->name . " has been removed successfully."];

        return redirect()->back()->with("danger", $msg);
    }
}
 