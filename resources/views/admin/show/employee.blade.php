@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
    Welcome {{ auth()->user()->name }}
    @else
    {{ $employee->full_name }}
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
                    <li class="breadcrumb-item"><a href="{{ route("admin.employees.index") }}">User</a></li>
                    <li class="breadcrumb-item active">Employees</li>
                </ol>
            </div>
            <h4 class="page-title">{{ $employee->full_name }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                <img src="{{ asset("assets/images/employee.jpg") }}" 
                    class="rounded-circle avatar-lg img-thumbnail" alt="Avatar" />
                
                <h4 class="mb-0">{{ $employee->full_name }}</h4>
                <p class="text-danger fw-bold">@<span>{{ $employee->username }}</span></p>

                <div class="text-start mt-3">
                    <h4 class="font-13 text-uppercase">About :</h4>

                    <p class="text-muted mb-1 font-13">
                        <strong>Employee Position :</strong> <span class="ms-2">{{ $employee->position->name }}</span>
                    </p>
                    <p class="text-muted mb-1 font-13">
                        <strong>Employee Office :</strong> <span class="ms-2">{{ $employee->office->name }}</span>
                    </p>
                    <p class="text-muted mb-1 font-13">
                        <strong>Employee Status :</strong> <span class="ms-2 py-1 badge bg-{{ $employee->accountColor() }}">{{ $employee->accountText() }}</span>
                    </p>
                </div>

                <form action="{{ route("admin.employees.profile.account", $employee->id) }}" method="POST">
                    @csrf
                    @method("PATCH")
                    <div class="d-flex justify-content-start align-items-center mt-2">
                        <button
                            type="submit" class="btn btn-xs btn-success"
                        >
                            <i class="mdi mdi-pencil"></i> Update Status
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="col-xl-9 col-lg-9">
        <div class="card">
            <div class="card-body">
							<div class="btn-group dropstart float-end">
								<button type="button" class="btn btn-blue waves-effect waves-light dropdown-toggle"
									data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
								>
									<i class="mdi mdi-chevron-left"></i> Make Request
								</button>
								<div class="dropdown-menu">
									<a href="{{ route("admin.requests.create", $employee->id) }}" class="dropdown-item">
										New Request 
									</a>
									<div class="dropdown-divider"></div>
									<a href="{{ route("admin.requests.repair", $employee->id) }}" class="dropdown-item">
										Repair Request
									</a>
									<a href="{{ route("admin.requests.returned", $employee->id) }}" class="dropdown-item">
										Return Request
									</a>
								</div>
							</div>
							<h4 class="header-title mb-4">Manage Requests</h4>
							<div class="table-responsive">
								<table class="table table-hover m-0 table-centered" id="datatable">
									<thead>
										<tr>
											<th>Item</th>
											<th class="text-center">Brand</th>
											<th class="text-center">Quantity</th>
											<th class="text-center">Item Type</th>
											<th class="text-center">Date Received</th>
											<th class="text-center">Date Returned</th>
											<th class="text-center">Item Status</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($employee->items as $item)
											<tr>
												<td>
													<div class="d-flex flex-column">
														<span class="text-primary fw-semibold">
															<b class="text-dark">Stock No. </b>
															{{ $item->item->stock_no }}
														</span>
														<span>
															<b class="text-dark">Description </b>
															{{ $item->item->name }}
														</span>
														<span>
															<b class="text-dark">Unit </b>
															{{ $item->item->unit }}
														</span>
													</div>
												</td>
												<td class="text-center">
													<span>{{ $item->item->brand }}</span>
												</td>
												<td class="text-center">
													<span class="text-danger fw-semibold">{{ $item->quantity }}</span>
												</td>
												<td class="text-center">
													<span class="badge py-1 bg-{{ $item->item->itemColor() }}">{{ $item->item->itemType() }}</span>
												</td>
												<td class="text-center">
													<span class="text-dark">{{ $item->created_at->format("F d, Y") }}</span>
												</td>
												<td class="text-center">
													<span class="text-dark">{{ is_null($item->returned_at) ? "-" :$item->returned_at->format("F d, Y") }}</span>
												</td>
												<td class="text-center">
													<span class="badge py-1 bg-{{ $item->statusColor() }}">{{ $item->statusText() }}</span>
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
    <script src="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.js") }}"></script>
    <script src="{{ asset("assets/js/pages/toastr.js") }}"></script>
    <script src="{{ asset("assets/libs/datatables.net/js/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js") }}"></script>
    @include("toastr")
    <script>
        $(document).ready(function(){
            $("#datatable").DataTable({
                order: [[6, "asc"], [3, "asc"]]
            })
        })
    </script>
@endsection