<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\EmployeeItem;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Request as ModelsRequest;

class RequestController extends Controller
{
    public function index() {
        $requests = ModelsRequest::pending()->latest()->get();
        return view("employee.request", compact("requests"));
    }

    public function destroy(Request $request, ModelsRequest $model) {
        $request->validate([
            "password" => "required"
        ]);

        if (!password_verify($request->password, Auth::guard("employee")->user()->password)) {
            return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
        }

        $model->update(["deleted_at" => Carbon::now()]);

        $msg = ["Request Removed", "The " . $model->id . " has been removed successfully."];

        return redirect()->back()->with("danger", $msg);
    }

    public function item() {
        return view("employee.item");
    }
}
