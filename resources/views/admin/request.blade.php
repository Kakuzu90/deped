@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
    Welcome {{ auth()->user()->name }}
    @else
    Request Page
    @endif
@endsection

@section("links")
    <link rel="stylesheet" href="{{ asset("assets/libs/select2/css/select2.min.css") }}">
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
                    <li class="breadcrumb-item active">Requests</li>
                </ol>
            </div>
            <h4 class="page-title">Requests</h4>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6 col-xl-3">
        <x-widget
            title="To Barrow" :count="getRequestBarrow()" icon="mdi mdi-book-arrow-left" color="success"
            />
    </div>
    <div class="col-md-6 col-xl-3">
        <x-widget
            title="To Return" :count="getRequestReturned()" icon="mdi mdi-book-refresh" color="warning"
            />
    </div>
    <div class="col-md-6 col-xl-3">
        <x-widget
            title="To Repair" :count="getRequestRepair()" icon="mdi mdi-book-cog" color="danger"
            />
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4">Manage Requests</h4>
                <div class="table-responsive">
                    <table class="table table-hover m-0" id="datatable">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th class="text-center">Items</th>
                                <th class="text-center">Request Type</th>
                                <th class="text-center">Date Requested</th>
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
															<span class="badge bg-blue p-1">{{ $item->items->count() }}</span>
														</td>
														<td class="text-center">
															<span class="badge p-1 bg-{{ $item->requestTypeColor() }}">{{ $item->requestType() }}</span>
														</td>
														<td class="text-center">
															<a href="" 
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
    <script src="{{ asset("assets/js/pages/authentication.init.js") }}"></script>
    <script src="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.js") }}"></script>
    <script src="{{ asset("assets/js/pages/toastr.js") }}"></script>
    <script src="{{ asset("assets/libs/datatables.net/js/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js") }}"></script>
    <script src="{{ asset("assets/libs/select2/js/select2.min.js") }}"></script>
    @include("toastr")
    <script>
        $(document).ready(function(){
            $("#datatable").DataTable({
                order: [[0, "asc"]]
            })
            $(".dataTables_length select").addClass("form-select")
            $(".dataTables_length select").removeClass("custom-select custom-select-sm")
            $(".dataTables_length label").addClass("form-label")

        })
    </script>

@endsection