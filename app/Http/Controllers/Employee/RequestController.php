<?php

namespace App\Http\Controllers\Employee;

use App\Models\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;

class RequestController extends Controller
{
	public function index()
	{
		$requests = Request::myRequest()->latest()->get();
		return view("employee.request", compact("requests"));
	}

	public function generate(Request $request)
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
}
