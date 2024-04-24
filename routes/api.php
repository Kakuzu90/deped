<?php

use App\Http\Controllers\Api\Admin\BarrowController;
use App\Http\Controllers\Api\Admin\RepairReturnController;
use App\Http\Controllers\Api\RequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware("auth:employee")->as("api.employee.")->group(function () {
// 	Route::prefix("requests")->controller(RequestController::class)->group(function () {
// 		Route::get("new", "getNew");
// 		Route::get("repair", "getRepair");
// 		Route::get("return", "getReturn");
// 		Route::get("items/{model}", "getUpdate")->name("requests.update");
// 		Route::get("items/{model}/inventory", "edit");
// 		Route::post("{employee}/new/store", "storeNew");
// 		Route::post("repair/store", "storeRepair");
// 		Route::post("return/store", "storeReturn");
// 		Route::post("items/{model}/update", "update");
// 	});
// });

Route::middleware("auth")->as("api.admin.")->group(function () {
	Route::prefix("requests")->group(function () {
		Route::controller(RequestController::class)->group(function () {
			Route::get("new", "getNew");
			Route::get("{employee}/repair", "getRepair");
			Route::get("{employee}/return", "getReturn");
			Route::get("items/{model}", "getUpdate")->name("requests.update");
			Route::get("items/{model}/inventory", "edit");
			Route::post("{employee}/new/store", "storeNew");
			Route::post("{employee}/repair/store", "storeRepair");
			Route::post("{employee}/return/store", "storeReturn");
			Route::post("items/{model}/update", "update");
		});
	});
});
