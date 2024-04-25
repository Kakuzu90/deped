@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
    Welcome {{ auth()->user()->name }}
    @else
    Employee Page
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
                    <li class="breadcrumb-item active">Employees</li>
                </ol>
            </div>
            <h4 class="page-title">Employees</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-blue waves-effect waves-light float-end"
                    data-bs-toggle="modal" data-bs-target="#add"
                >
                    <i class="mdi mdi-plus-circle"></i> Add Employee
                </button>
                <h4 class="header-title mb-4">Manage Employees</h4>
                <div class="table-responsive">
                    <table class="table table-hover m-0" id="datatable">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th class="text-center">Position</th>
                                <th class="text-center">Office</th>
                                <th class="text-center">Items</th>
                                <th class="text-center">Account Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="avatar avatar-sm">
                                                <span class="avatar-title rounded-circle bg-primary">{{ $item->full_name[0] }}</span>
                                            </div>
                                            <div class="d-flex flex-column ms-2">
                                                <p class="fw-bolder mb-0">{{ $item->full_name }}</p>
                                                <span>{{ $item->username }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="fw-bolder text-primary">{{ $item->position->name }}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="fw-bolder text-primary">{{ $item->office->name }}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge px-2 py-1 bg-secondary">{{ $item->items->count() }}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge py-1 bg-{{ $item->accountColor() }}">{{ $item->accountText() }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route("admin.employees.profile", $item->id) }}" 
                                            class="action-icon">
                                            <i class="mdi mdi-eye-plus"></i>
                                        </a>
                                        <a href="javascript:void(0);" 
                                            data-route="{{ route("admin.employees.show", $item->id) }}" 
                                            class="edit action-icon">
                                            <i class="mdi mdi-circle-edit-outline"></i>
                                        </a>
                                        <a href="javascript:void(0);" 
                                            data-route="{{ route("admin.employees.show", $item->id) }}" 
                                            data-header="{{ $item->full_name }}"
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
@include("admin.modal.employee.add")
@include("admin.modal.employee.edit")
@include("admin.modal.delete")
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

            @error("username")
             NotificationApp.send("Username Exist!", "{{ $message }}","top-right","#ee2b48","error")
            @enderror

            $("[data-toggle=select2]").each(function() {
                const placeholder = $(this).data("placeholder");
                $(this).select2({
                    placeholder: placeholder,
                    minimumResultsForSearch: Infinity,
                })
            })

            $(document).on("click", ".edit", function() {
                const route = $(this).data("route")

                $("#edit").modal("show")
                $("#edit #form_loader").removeClass("d-none")
                $("#edit #form_loader").addClass("d-block")
                $("#edit #form_container").removeClass("d-block")
                $("#edit #form_container").addClass("d-none")

                $.ajax({
                    url: route,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        $("#edit #form_loader").addClass("d-none")
                        $("#edit #form_loader").removeClass("d-block")
                        $("#edit #form_container").addClass("d-block")
                        $("#edit #form_container").removeClass("d-none")
                        $("#edit form").attr("action", route)

                        $("#edit input[name=name]").val(response.full_name)
                        $("#edit input[name=username]").val(response.username)
                        $("#edit select[name=office]").val(response.office_id).trigger("change")
                        $("#edit select[name=position]").val(response.position_id).trigger("change")
                    }
                })
            });

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