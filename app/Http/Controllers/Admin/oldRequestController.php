<?php

namespace App\Http\Controllers\Admin;

use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\Request as ModelsRequest;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpWord\TemplateProcessor;

class RequestController extends Controller
{
	public function index()
	{
		$data["requests"] = ModelsRequest::pending()->latest()->get();
		$data["barrow"] = ModelsRequest::pending()->barrow()->count();
		$data["return"] = ModelsRequest::pending()->returned()->count();
		$data["repair"] = ModelsRequest::pending()->repair()->count();
		return view("admin.request.pending", compact("data"));
	}
	public function accepted()
	{
		$data["requests"] = ModelsRequest::accepted()->latest()->get();
		$data["barrow"] = ModelsRequest::accepted()->barrow()->count();
		$data["return"] = ModelsRequest::accepted()->returned()->count();
		$data["repair"] = ModelsRequest::accepted()->repair()->count();
		return view("admin.request.accepted", compact("data"));
	}
	public function rejected()
	{
		$data["requests"] = ModelsRequest::rejected()->latest()->get();
		$data["barrow"] = ModelsRequest::rejected()->barrow()->count();
		$data["return"] = ModelsRequest::rejected()->returned()->count();
		$data["repair"] = ModelsRequest::rejected()->repair()->count();
		return view("admin.request.rejected", compact("data"));
	}
	public function barrow(ModelsRequest $request)
	{
		if (!$request->isToBarrow() || !$request->isPending()) {
			abort(404);
		}
		return view("admin.request.barrow", compact("request"));
	}
	public function repairReturned(ModelsRequest $request)
	{
		if ($request->isToBarrow() || !$request->isPending()) {
			abort(404);
		}
		return view("admin.request.return-repair", compact("request"));
	}
	public function generate(ModelsRequest $request)
	{
		if (!$request->isToBarrow() || !$request->isAccepted()) {
			abort(404);
		}

		$ris_no = "RIS" . Carbon::now()->year; // for supply
		$ics_no = "ICS" . Carbon::now()->year; // for equipment
		$requested = $request->employee->full_name;
		$approved = $request->acceptedBy->name;
		$issued = $request->releasedBy->name;
		$received = $request->employee->full_name;
		$req_date = $request->created_at->format("m/d/Y");
		$app_date = $request->accepted_at->format("m/d/Y");
		$iss_date = $request->released_at->format("m/d/Y");
		$rec_date = Carbon::now()->format("m/d/Y");

		$temp_dir = storage_path("app/public/template/temp/");
		File::makeDirectory($temp_dir, 0755, true, true);
		$supply_temp = $temp_dir . "supply.docx";
		$equipment_temp = $temp_dir . "equipment.docx";

		$supply_array = $request->items->filter(function ($item) {
			return $item->item->isSupply();
		});
		$equipment_array = $request->items->filter(function ($item) {
			return $item->item->isEquipment();
		});

		if ($supply_array->count() > 0) {
			/* For Supply */
			$files[] = $supply_temp;
			$supply = new TemplateProcessor(storage_path("app/public/template/supply.docx"));

			$supply->setValue("ris_no", $ris_no);
			$supply->setValue("requested", $requested);
			$supply->setValue("approved", $approved);
			$supply->setValue("issued", $issued);
			$supply->setValue("received", $received);
			$supply->setValue("req_date", $req_date);
			$supply->setValue("app_date", $app_date);
			$supply->setValue("iss_date", $iss_date);
			$supply->setValue("rec_date", $rec_date);

			$supply->cloneRow("stock", $supply_array->count());
			$supply_index = 1;
			foreach ($supply_array as $item) {
				$supply->setValue("stock#" . $supply_index, $item->item_id);
				$supply->setValue("brand#" . $supply_index, $item->item->brand);
				$supply->setValue("quantity#" . $supply_index, $item->quantity);
				$supply->setValue("yes#" . $supply_index, $item->isAccepted() ? "|" : "");
				$supply->setValue("no#" . $supply_index, $item->isAccepted() ? "" : "|");
				$supply_index++;
			}

			$supply->saveAs($supply_temp);
		}

		if ($equipment_array->count() > 0) {
			/* For Equipment */
			$files[] = $equipment_temp;
			$equipment = new TemplateProcessor(storage_path("app/public/template/equipment.docx"));

			$equipment->setValue("ics_no", $ics_no);
			$equipment->setValue("accepted", $approved);
			$equipment->setValue("employee", $requested);

			$overall = 0;

			$equipment->cloneRow("quantity", $equipment_array->count());
			$equipment_index = 1;
			foreach ($equipment_array as $item) {
				$totalCost = ($item->quantity * $item->item->amount);
				$overall += $totalCost;
				$equipment->setValue("quantity#" . $equipment_index, $item->quantity);
				$equipment->setValue("cost#" . $equipment_index, $item->item->amount);
				$equipment->setValue("total#" . $equipment_index, $totalCost);
				$equipment->setValue("brand#" . $equipment_index, $item->item->brand);
				$equipment->setValue("id#" . $equipment_index, $item->item_id);
				$equipment_index++;
			}
			$equipment->setValue("overall", $overall);

			$equipment->saveAs($equipment_temp);
		}

		$zipFileName = "download.zip";
		$zip = new ZipArchive();

		if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
			$zip->addFile($supply_temp, basename($supply_temp));
			$zip->addFile($equipment_temp, basename($equipment_temp));
			$zip->close();
		}

		return Response::download(public_path($zipFileName))->deleteFileAfterSend();
	}
}
