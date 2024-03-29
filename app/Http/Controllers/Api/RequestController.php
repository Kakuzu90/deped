<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PendingResource;
use App\Models\EmployeeItem;
use App\Models\Item;
use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
	public function getPending(Request $request)
	{
		$perPage = 4;
		$currentPage = $request->query("page", 1);

		$offset = ($currentPage - 1) * $perPage;

		$scope = ModelsRequest::pending()->barrow();
		$items = $scope->skip($offset)->take($perPage)->latest()->get();
		$total = ModelsRequest::pending()->barrow()->count();
		$pages = ceil($total / $perPage);
		$startRange = ($currentPage - 1) * $perPage + 1;
		$endRange = min($currentPage * $perPage, $total);

		$onFirstPage = $currentPage === 1;
		$next = $pages > $currentPage ? route("api.employee.pending", ["page" => $currentPage + 1]) : false;
		$prev = !$onFirstPage ? route("api.employee.pending", ["page" => $currentPage - 1]) : false;
		$showing = "Showing $startRange to $endRange of $total";

		return response()->json([
			"data" => $items->map(function ($item) {
				return [
					"image" => asset("assets/images/add-item.png"),
					"id" => $item->id,
					"name" => $item->item->name,
					"quantity" => $item->quantity,
					"code" => $item->item_id,
					"color" => "bg-" . $item->item->itemColor(),
					"text" => $item->item->itemType(),
				];
			}),
			"pagination" => [
				"current_page" => $currentPage,
				"next" => $next,
				"prev" => $prev,
				"showing" => $showing
			]
		]);
	}

	public function getRepair()
	{
		$items = ModelsRequest::pending()->repair()->latest()->get();
	}

	public function fetchPending(Request $request)
	{
		$search = $request->input("search");

		$items = ModelsRequest::pending()->barrow()->pluck("item_id");
		$inventory = Item::working()->whereNotIn("id", $items);

		if ($search) {
			$inventory->where("name", "LIKE", "%$search%");
		}

		$inventory = $inventory->latest()->get();
	}

	public function fetchRepair(Request $request)
	{
	}
}
