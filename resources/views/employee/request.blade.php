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
                    <button 
                        type="button" 
                        class="btn btn-blue waves-effect waves-light dropdown-toggle" 
                        data-bs-toggle="dropdown" 
                        aria-haspopup="true" aria-expanded="false">
                        Make Request
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route("employee.requests.items") }}">
                            <i class="mdi mdi-book-arrow-left me-1"></i>
                            Request New
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="mdi mdi-book-cog me-1"></i>
                            Request Repair
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="mdi mdi-book-refresh me-1"></i>
                            Request Return
                        </a>
                    </div>
                </div>
                <h4 class="header-title mb-4">Manage Requests</h4>
                <div class="table-responsive">
                    <table class="table table-hover m-0" id="datatable">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Item Type</th>
                                <th class="text-center">Request Type</th>
                                <th class="text-center">Date Requested</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $item)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $item->item->id }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-dark">{{ $item->item->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-dark">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge p-1 bg-{{ $item->item->itemColor() }}">{{ $item->item->itemType() }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge p-1 bg-{{ $item->requestTypeColor() }}">{{ $item->requestType() }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-dark">{{ $item->created_at->format("F d, Y") }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="javascript:void(0);" 
                                            data-route="{{ route("employee.requests.destroy", $item->id) }}" 
                                            data-header="{{ $item->item->id }}"
                                            class="delete action-icon">
                                            <i class="mdi mdi-delete-empty"></i>
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

@include("admin.modal.delete")
@endsection

@section("scripts")
    <script src="{{ asset("assets/libs/datatables.net/js/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js") }}"></script>
    <script>
        $(document).ready(function() {
             $(document).ready(function(){
                $("#datatable").DataTable({
                    
                })
                $(".dataTables_length select").addClass("form-select")
                $(".dataTables_length select").removeClass("custom-select custom-select-sm")
                $(".dataTables_length label").addClass("form-label")

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
            })

            $(document).on("click", ".btn-continue", function() {
                $("#first-prompt").addClass("d-none");
                $("#second-prompt").removeClass("d-none");
                $("#second-prompt").addClass("d-block");
            })
        })
    </script>
@endsection