@extends("layouts.default")

@section("title")
    @if (Session::get("status"))
        Welcome {{ auth()->guard("employee")->user()->name }}
    @else
        My Requests
    @endif
@endsection

@section("links")
		<link rel="stylesheet" href="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.css") }}">
		<link rel="stylesheet" href="{{ asset("assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css") }}">
@endsection

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Deped</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                    <li class="breadcrumb-item active">My Requests</li>
                </ol>
            </div>
            <h4 class="page-title">My Requests</h4>
        </div>
    </div>
    
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="header-title mb-4">My Requests</h4>
					<div class="table-responsive">
						<table class="table table-centered table-sm table-hover m-0" id="datatable">
							<thead>
								<tr>
									<th>Employee Name</th>
									<th class="text-center">Office</th>
									<th class="text-center">Items</th>
									<th class="text-center">Total Amount</th>
									<th class="text-center">Request Type</th>
									<th class="text-center">Date Released</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach ($requests as $item)
									<tr>
										<td>
											<div class="d-flex justify-content-start align-items-center">
												<div class="avatar avatar-sm">
														<span class="avatar-title rounded-circle bg-primary">{{ $item->employee->full_name[0] }}</span>
												</div>
												<div class="d-flex flex-column ms-2">
														<p class="fw-bolder mb-0">{{ $item->employee->full_name }}</p>
														<span>{{ $item->employee->username }}</span>
												</div>
											</div>
										</td>
										<td class="text-center">
											<span>{{ $item->employee->office->name }}</span>
										</td>
										<td class="text-center">
											<span class="badge px-2 py-1 bg-dark">{{ $item->items->count() }}</span>
										</td>
										<td class="text-center">
											<span class="text-dark fw-semibold">&#8369;{{ $item->moneyFormat() }}</span>
										</td>
										<td class="text-center">
											<span class="badge p-1 bg-{{ $item->requestTypeColor() }}">
												{{ $item->requestType() }}
											</span>
										</td>
										<td class="text-center">
											<span class="text-dark">{{ $item->released_at->format("F d, Y") }}</span>
										</td>
										<td class="text-center">
											@if ($item->isToBarrow())
											<a href="{{ route("employee.requests.generate", $item->id) }}"
												class="action-icon">
												<i class="mdi mdi-printer"></i>
											</a>
											@endif
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
</div>
@include("admin.modal.delete")
@endsection

@section("scripts")
	<script src="{{ asset("assets/js/pages/authentication.init.js") }}"></script>
	<script src="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.js") }}"></script>
	<script src="{{ asset("assets/js/pages/toastr.js") }}"></script>
	<script src="{{ asset("assets/libs/datatables.net/js/jquery.dataTables.min.js") }}"></script>
	<script src="{{ asset("assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js") }}"></script>
	@include("toastr")
	<script>
		$(document).ready(function(){
			$("#datatable").DataTable({
				lengthChange: false,
				pageLength: 15,
				order: [[5, "desc"], [4, "asc"], [0, "asc"]]
			})
			$(document).on("click", ".delete", function() {
					const route = $(this).data("route");
					const title = $(this).data("header");

					$("#first-prompt").removeClass("d-none");
					$("#second-prompt").addClass("d-none");
					$("#second-prompt").removeClass("d-block");

					$("#delete .delete_data").text(title);
					$("#delete form").attr("action", route);
					$("#delete").modal("show");
			});

			$(document).on("click", ".btn-continue", function() {
					$("#first-prompt").addClass("d-none");
					$("#second-prompt").removeClass("d-none");
					$("#second-prompt").addClass("d-block");
			})
		});
	</script>
@endsection