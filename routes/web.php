<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\SupplyController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\EmployeeController;
use App\Http\Controllers\Employee\HomeController;
use App\Http\Controllers\Employee\ProfileController as EmployeeProfileController;
use App\Http\Controllers\Employee\RequestController as EmployeeRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(EmployeeController::class)
	->middleware("guest:employee")
	->group(function () {

		Route::get("/", "index")->name("login");
		Route::get("register", "registerView")->name("register");

		Route::post("store", "login")->name("login.store");
		Route::post("registered", "registered")->name("register.store");
	});

Route::prefix("employee")
	->as("employee.")
	->middleware("auth:employee")
	->group(function () {

		Route::get("/", function () {
			return redirect()->route("employee.home");
		});

		Route::get("logout", [EmployeeController::class, "logout"])->name("logout");
		Route::get("home", [HomeController::class, "index"])->name("home");
		Route::controller(EmployeeProfileController::class)->prefix("my-profile")->as("profile.")->group(function () {
			Route::get("", "index")->name("index");
			Route::put("general", "general")->name("general");
			Route::patch("password", "password")->name("password");
		});

		Route::controller(EmployeeRequestController::class)->prefix("requests")->as("requests.")->group(function () {
			Route::get("", "index")->name("index");
			Route::get("new", "new")->name("new");
			Route::get("repair", "repair")->name("repair");
			Route::get("return", "return")->name("return");
			Route::get("{request}/update", "edit")->name("edit");
		});
	});

Route::prefix("admin")
	->as("admin.")
	->group(function () {

		Route::get("/", function () {
			return redirect()->route("admin.dashboard");
		});
		Route::get("requests", function () {
			return redirect()->route("admin.requests.index");
		});

		Route::middleware("guest")->controller(AdminController::class)->group(function () {
			Route::get("login", "index")->name("login");
			Route::post("login/store", "login")->name("login.store");
		});


		Route::middleware("auth")->group(function () {

			Route::get("logout", [AdminController::class, "logout"])->name("logout");
			Route::get("dashboard", DashboardController::class)->name("dashboard");
			Route::apiResource("offices", OfficeController::class);
			Route::apiResource("positions", PositionController::class);
			Route::get("employees/{employee}/profile", [AdminEmployeeController::class, "profile"])->name("employees.profile");
			Route::patch("employees/{employee}/account", [AdminEmployeeController::class, "account"])->name("employees.profile.account");
			Route::apiResource("employees", AdminEmployeeController::class);
			Route::get("supplies/{item}/history", [SupplyController::class, "history"])->name("supplies.history");
			Route::apiResource("supplies", SupplyController::class);
			Route::get("equipments/{item}/history", [EquipmentController::class, "history"])->name("equipments.history");
			Route::apiResource("equipments", EquipmentController::class);
			Route::controller(RequestController::class)->prefix("requests")->as("requests.")->group(function () {
				Route::get("pending", "index")->name("index");
				Route::get("accepted", "accepted")->name("accepted");
				Route::get("rejected", "rejected")->name("rejected");
				Route::get("pending/{request}", "barrow")->name("barrow");
				Route::get("return-or-repair/{request}", "repairReturned")->name("repair.returned");
				Route::get("accepted/{request}/generate", "generate")->name("generate");
			});
			Route::controller(ProfileController::class)->group(function () {
				Route::get("my-profile", "index")->name("profile");
				Route::put("my-profile", "general")->name("profile.general");
				Route::patch("my-profile", "password")->name("profile.password");
			});
		});
	});

Route::fallback(function () {
	abort(404);
});
