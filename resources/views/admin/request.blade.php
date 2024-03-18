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
                                <th>Item Code</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">Serial No</th>
                                <th class="text-center">Model No</th>
                                <th class="text-center">Brand</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Date Purchased</th>
                                <th class="text-center">Condition</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
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