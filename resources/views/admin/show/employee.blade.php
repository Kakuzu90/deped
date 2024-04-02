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
                <ul class="nav nav-pills navtab-bg nav-justified">
                    <li class="nav-item">
                        <a href="#onhand" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                            On-Hand
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#returned" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            Returned
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane show active" id="onhand">
                        <div class="table-responsive">
                            <table class="table table-hover m-0" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Item Type</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Date Received</th>
                                    </tr>
                                </thead>
                                <tbody>
																	@foreach ($employee->onHand as $item)
																			<tr>
																				<td>
																					<span class="fw-bold text-primary">{{ $item->item_id }}</span>
																				</td>
																				<td class="text-center">
																					<span class="text-dark">{{ $item->item->name }}</span>
																				</td>
																				<td class="text-center">
																					<span class="badge p-1 bg-{{ $item->item->itemColor() }}">{{ $item->item->itemType() }}</span>
																				</td>
																				<td class="text-center">
																					<span class="fw-bold text-dark">{{ $item->quantity }}</span>
																				</td>
																				<td class="text-center">
																					<span class="text-dark">{{ $item->created_at->format("F d, Y") }}</span>
																				</td>
																			</tr>
																	@endforeach
																</tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="returned">
                        <div class="table-responsive">
                            <table class="table table-hover m-0" id="datatable1">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Item Type</th>
                                        <th class="text-center">Date Returned</th>
                                    </tr>
                                </thead>
                                <tbody>
																	@foreach ($employee->returned as $item)
																			<tr>
																				<td>
																					<span class="fw-bold text-primary">{{ $item->item_id }}</span>
																				</td>
																				<td class="text-center">
																					<span class="text-dark">{{ $item->item->name }}</span>
																				</td>
																				<td class="text-center">
																					<span class="badge p-1 bg-{{ $item->item->itemColor() }}">{{ $item->item->itemType() }}</span>
																				</td>
																				<td class="text-center">
																					<span class="text-dark">{{ $item->returned_at->format("F d, Y") }}</span>
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
                order: [[4, "desc"], [2, "asc"]]
            })
            $("#datatable1").DataTable({
                order: [[3, "desc"]]
            })
            $(".dataTables_length select").addClass("form-select")
            $(".dataTables_length select").removeClass("custom-select custom-select-sm")
            $(".dataTables_length label").addClass("form-label")
        })
    </script>
@endsection