<?php

use App\Models\Item;
use App\Models\Office;
use App\Models\Request;
use App\Models\Position;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

if (!function_exists("verifyMe")) {
	function verifyMe(string $password): bool
	{
		return password_verify($password, Auth::user()->password);
	}
}

if (!function_exists("getPositions")) {
	function getPositions()
	{
		return Position::latest()->get();
	}
}

if (!function_exists("getOffices")) {
	function getOffices()
	{
		return Office::latest()->get();
	}
}

if (!function_exists("getWorking")) {
	function getWorking()
	{
		return Item::equipment()->working()->count();
	}
}

if (!function_exists("getRepair")) {
	function getRepair()
	{
		return Item::equipment()->repair()->count();
	}
}

if (!function_exists("getDefective")) {
	function getDefective()
	{
		return Item::equipment()->defective()->count();
	}
}

if (!function_exists("isOdd")) {
	function isOdd(int $n)
	{
		return $n % 2 === 0 ? "timeline-item timeline-item-left" : "timeline-item";
	}
}
