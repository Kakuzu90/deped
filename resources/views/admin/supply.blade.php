@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
    Welcome {{ auth()->user()->name }}
    @else
    Supply Page
    @endif
@endsection

@section("links")
    <link rel="stylesheet" href="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/libs/flatpickr/flatpickr.min.css") }}">
@endsection

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Deped</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
                    <li class="breadcrumb-item active">Supplies</li>
                </ol>
            </div>
            <h4 class="page-title">Supplies</h4>
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
                    <i class="mdi mdi-plus-circle"></i> Add Supply
                </button>

                <button type="button" class="btn btn-icon btn-outline-blue waves-effect waves-light float-end me-1"
                >
                    <i class="mdi mdi-qrcode"></i>
                </button>

                <h4 class="header-title mb-4">Manage Supplies</h4>
                <div class="table-responsive">
                    <table class="table table-hover m-0" id="datatable">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Brand</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Date Purchased</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supplies as $item)
                                <tr>
                                    <td>
                                        <span class="fw-bolder text-primary">
                                            {{ $item->id }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span>{{ $item->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{{ $item->brand }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>&#8369;{{ $item->moneyFormat() }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span>{{ $item->purchased_at->format("F d, Y") }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route("admin.supplies.history", $item->id) }}" 
                                            class="action-icon">
                                            <i class="mdi mdi-eye-plus"></i>
                                        </a>
                                        <a href="javascript:void(0);" 
                                            data-route="{{ route("admin.supplies.show", $item->id) }}" 
                                            class="edit action-icon">
                                            <i class="mdi mdi-circle-edit-outline"></i>
                                        </a>
                                        <a href="javascript:void(0);" 
                                            data-route="{{ route("admin.supplies.show", $item->id) }}" 
                                            data-header="{{ $item->id }}"
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

@include("admin.modal.supply.add")
@include("admin.modal.supply.edit")
@include("admin.modal.delete")
@endsection

@section("scripts")
    <script src="{{ asset("assets/js/pages/authentication.init.js") }}"></script>
    <script src="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.js") }}"></script>
    <script src="{{ asset("assets/js/pages/toastr.js") }}"></script>
    <script src="{{ asset("assets/libs/datatables.net/js/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js") }}"></script>
    <script src="{{ asset("assets/libs/jquery-mask-plugin/jquery.mask.min.js") }}"></script>
    <script src="{{ asset("assets/libs/flatpickr/flatpickr.min.js") }}"></script>
    @include("toastr")
    <script>
        $(document).ready(function(){
            $("#datatable").DataTable({
                order: [[5, "desc"], [1, "asc"], [3, "asc"]]
            })
            $(".dataTables_length select").addClass("form-select")
            $(".dataTables_length select").removeClass("custom-select custom-select-sm")
            $(".dataTables_length label").addClass("form-label")

            $('[data-toggle="input-mask"]').each(function(a,e){
                var t = $(e).data("maskFormat")
                n = $(e).data("reverse")
                null != n ? $(e).mask(t,{reverse:n}) : $(e).mask(t)
            })

            $('.flatpickr-human-friendly').flatpickr({
                altInput: true,
                altFormat: 'F j, Y',
                dateFormat: 'Y-m-d'
            });

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
                        $("#edit input[name=quantity]").val(response.quantity)
                        $("#edit input[name=brand]").val(response.brand)
                        $("#edit input[name=amount]").val(response.amount)
                        $("#edit input[name=date_purchased]").val(response.purchased_at)

                        $('.flatpickr-human-friendly').flatpickr({
                            altInput: true,
                            altFormat: 'F j, Y',
                            dateFormat: 'Y-m-d'
                        });
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