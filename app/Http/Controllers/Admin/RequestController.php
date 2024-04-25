<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Employee;
use App\Models\EmployeeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\Request as ModelsRequest;
use PhpOffice\PhpWord\TemplateProcessor;

class RequestController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$data["table"] = ModelsRequest::latest()->get();
		return view("admin.request.index", compact("data"));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Employee $employee)
	{
		return view("admin.request.new", compact("employee"));
	}

	public function repair(Employee $employee)
	{
		return view("admin.request.repair", compact("employee"));
	}

	public function returned(Employee $employee)
	{
		return view("admin.request.return", compact("employee"));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function show(ModelsRequest $request)
	{
		if (!$request->isToBarrow() || !$request->isAccepted()) {
			abort(404);
		}

		$ris_no = "RIS" . Carbon::now()->year;
		$ics_no = "ICS" . Carbon::now()->year;
		$requested = $request->employee->full_name;
		$approved = $request->acceptedBy->name;
		$issued = $request->releasedBy->name;
		$received = $request->employee->full_name;
		$req_date = $request->released_at->format("m/d/Y");
		$app_date = $request->accepted_at->format("m/d/Y");
		$iss_date = $request->released_at->format("m/d/Y");
		$rec_date = Carbon::now()->format("m/d/Y");
		$totalAmount = $request->totalSum();

		$temp_dir = storage_path("app/public/template/temp/");
		File::makeDirectory($temp_dir, 0755, true, true);

		if ($totalAmount > 15000) {
			$template = new TemplateProcessor(storage_path("app/public/template/ris.docx"));
			$filename = $temp_dir . "ris.docx";

			$template->setValue("ris_no", $ris_no);
			$template->setValue("requested", $requested);
			$template->setValue("approved", $approved);
			$template->setValue("issued", $issued);
			$template->setValue("received", $received);
			$template->setValue("req_date", $req_date);
			$template->setValue("app_date", $app_date);
			$template->setValue("iss_date", $iss_date);
			$template->setValue("rec_date", $rec_date);

			$template->cloneRow("stock", $request->items->count());
			foreach ($request->items as $index => $item) {
				$template->setValue("stock#" . ($index + 1), $item->item->stock_no);
				$template->setValue("unit#" . ($index + 1), $item->item->unit);
				$template->setValue("name#" . ($index + 1), $item->item->name);
				$template->setValue("brand#" . ($index + 1), $item->item->brand);
				$template->setValue("origin#" . ($index + 1), $item->item->place_origin);
				$template->setValue("quantity#" . ($index + 1), $item->quantity);
			}

			$template->saveAs($filename);
		} else {

			$template = new TemplateProcessor(storage_path("app/public/template/ics.docx"));
			$filename = $temp_dir . "ics.docx";

			$template->setValue("ics_no", $ics_no);
			$template->setValue("accepted", $approved);
			$template->setValue("employee", $requested);

			$overall = 0;

			$template->cloneRow("quantity", $request->items->count());
			foreach ($request->items as $index => $item) {
				$totalCost = ($item->quantity * $item->item->amount);
				$overall += $totalCost;
				$template->setValue("quantity#" . ($index + 1), $item->quantity);
				$template->setValue("cost#" . ($index + 1), $item->item->amount);
				$template->setValue("unit#" . ($index + 1), $item->item->unit);
				$template->setValue("total#" . ($index + 1), $totalCost);
				$template->setValue("brand#" . ($index + 1), $item->item->brand);
				$template->setValue("origin#" . ($index + 1), $item->item->place_origin);
				$template->setValue("stock#" . ($index + 1), $item->item->stock_no);
			}
			$template->setValue("overall", $overall);

			$template->saveAs($filename);

			return response()->download($filename)->deleteFileAfterSend();
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function edit(ModelsRequest $request)
	{
		return view("admin.request.update", compact("request"));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $form
	 * @param  \App\Models\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $form, ModelsRequest $request)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $form, ModelsRequest $request)
	{
		$form->validate([
			"password" => "required"
		]);

		if (!verifyMe($form->password)) {
			return redirect()->back()->withErrors(["verify_password" => "The password is incorrect, please try again!"]);
		}

		foreach ($request->items as $item) {
			Item::where("id", $item->item_id)->update(["status" => Item::WORKING]);
			EmployeeItem::where("employee_id", $request->employee_id)->where("item_id", $item->item_id)->update([
				"status" => EmployeeItem::ON_HAND, "returned_at" => null,
			]);
		}

		$request->delete();

		$msg = ["Request Removed", "The request has been removed successfully."];

		return redirect()->back()->with("danger", $msg);
	}
}
