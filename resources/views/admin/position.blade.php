@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
    Welcome {{ auth()->user()->name }}
    @else
    Position Page
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Informations</a></li>
                    <li class="breadcrumb-item active">Positions</li>
                </ol>
            </div>
            <h4 class="page-title">Positions</h4>
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
                    <i class="mdi mdi-plus-circle"></i> Add Position
                </button>
                <h4 class="header-title mb-4">Manage Positions</h4>
                <div class="table-responsive">
                    <table class="table table-hover m-0" id="datatable">
                        <thead>
                            <tr>
                                <th>Position Name</th>
                                <th class="text-center">Employees</th>
                                <th class="text-center">Date Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($positions as $item)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">
                                            {{ $item->name }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">
                                            {{ $item->employees->count() }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span>{{ $item->created_at->format("F d, Y") }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="javascript:void(0);" 
                                            data-route="{{ route("admin.positions.show", $item->id) }}" 
                                            class="edit action-icon">
                                            <i class="mdi mdi-circle-edit-outline"></i>
                                        </a>
                                        <a href="javascript:void(0);" 
                                            data-route="{{ route("admin.positions.show", $item->id) }}" 
                                            data-header="{{ $item->name }}"
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
@include("admin.modal.position.add")
@include("admin.modal.position.edit")
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
                order: [[0, "asc"]]
            })
            $(".dataTables_length select").addClass("form-select")
            $(".dataTables_length select").removeClass("custom-select custom-select-sm")
            $(".dataTables_length label").addClass("form-label")

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

                        $("#edit input[name=name]").val(response.name)
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