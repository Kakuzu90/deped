@extends("layouts.default")

@section("title")
    @if (Session::get("status"))
        Welcome {{ auth()->guard("employee")->user()->name }}
    @else
        My Request
    @endif
@endsection

@section("links")
		<link rel="stylesheet" href="{{ asset("assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css") }}">
@endsection

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Deped</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
                    <li class="breadcrumb-item active">My Request</li>
                </ol>
            </div>
            <h4 class="page-title">My Request</h4>
        </div>
    </div>
    
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="btn-group dropstart float-end">
						<button type="button" class="btn btn-blue waves-effect waves-light dropdown-toggle"
							data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
						>
							<i class="mdi mdi-chevron-left"></i> Make Request
						</button>
						<div class="dropdown-menu">
							<a href="{{ route("employee.requests.new") }}" class="dropdown-item">
								New Request 
							</a>
							<div class="dropdown-divider"></div>
							<a href="{{ route("employee.requests.repair") }}" class="dropdown-item">
								Repair Request
							</a>
							<a href="{{ route("employee.requests.return") }}" class="dropdown-item">
								Return Request
							</a>
						</div>
					</div>
					<h4 class="header-title mb-4">Manage Requests</h4>
					<div class="table-responsive">
						<table class="table table-hover m-0" id="datatable">
							<thead>
								<tr>
									<th class="text-center">Date Requested</th>
									<th class="text-center">Items</th>
									<th class="text-center">Request Type</th>
									<th class="text-center">Status</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach ($requests as $item)
										<tr>
											<td class="text-center">
												<span class="fw-bold text-dark">{{ $item->created_at->format("F d, Y") }}</span>
											</td>
											<td class="text-center">
												<span class="badge p-1 bg-dark">{{ $item->items->count() }}</span>
											</td>
											<td class="text-center">
												<span class="badge p-1 bg-{{ $item->requestTypeColor() }}">{{ $item->requestType() }}</span>
											</td>
											<td class="text-center">
												<span class="badge p-1 bg-{{ $item->requestStatusColor() }}">{{ $item->requestStatus() }}</span>
											</td>
											<td class="text-center">
												<a 
													class="action-icon">
													<i class="mdi mdi-eye-plus"></i>
												</a>
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
@endsection

@section("scripts")
	<script src="{{ asset("assets/libs/datatables.net/js/jquery.dataTables.min.js") }}"></script>
	<script src="{{ asset("assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js") }}"></script>
	<script>
		$(document).ready(function(){
				$("#datatable").DataTable({
						order: [[0, "desc"]]
				})

		});
	</script>
@endsection