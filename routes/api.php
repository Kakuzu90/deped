<?php

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

Route::middleware("auth:employee")->as("api.employee.")->group(function () {
	Route::prefix("requests")->controller(RequestController::class)->group(function () {
		Route::get("new", "getNew");
		Route::get("repair", "getRepair");
		Route::get("return", "getReturn");
		Route::post("new/store", "storeNew");
		Route::post("repair/store", "storeRepair");
		Route::post("return/store", "storeReturn");
	});
});
