@extends("layouts.default")

@section("title")
    @if (Session::get("status"))
        Welcome {{ auth()->guard("employee")->user()->name }}
    @else
        Home Page
    @endif
@endsection

@section("links")
    <link rel="stylesheet" href="{{ asset("assets/libs/select2/css/select2.min.css") }}">
    <style>
        .content-main {
            min-height: calc(100vh - var(--ct-horizontal-menu-height) - 320px)
        }
    </style>
@endsection

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Deped</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                    <li class="breadcrumb-item active">Owned Items</li>
                </ol>
            </div>
            <h4 class="page-title">Owned Items</h4>
        </div>
    </div>

    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <form action="" class="row gy-1 justify-content-end align-items-center" method="GET">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="search-bar">
                            <div class="position-relative">
                                <input type="text" name="search" class="form-control" placeholder="Search..." id="search" />
                                <span class="mdi mdi-magnify"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-2">
                        <select name="status" class="form-control" data-toggle="select2" data-placeholder="Select a value">
                            <option value="all">All</option>
                            <option value="1">On Hand</option>
                            <option value="2">Returned</option>
                            <option value="3">To Repair</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-2">
                        <select name="type" class="form-control" data-toggle="select2" data-placeholder="Select a value">
                            <option value="all">All</option>
                            <option value="1">Equipment</option>
                            <option value="2">Supply</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 content-main">
        <div class="row justify-content-center">

            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="card h-100 ribbon-box">
                    <div class="card-header d-flex justify-content-end p-2">
                        <span class="badge bg-success me-1">On Hand</span>
                    </div>
                    <div class="card-body pt-2 pb-0">
                        <div class="ribbon-two ribbon-two-blue"><span class="text-light">Dark</span></div>
                        <div class="d-flex justify-content-center align-items-center flex-column text-center mb-2">
                            <div class="avatar-lg mb-1">
                                <span class="avatar-title bg-soft-secondary text-secondary font-20 rounded-circle">
                                    <i class="mdi mdi-tools mdi-24px"></i>
                                </span>
                            </div>
                            <p class="mb-0 text-secondary fw-bold">123456789</p>
                        </div>
                        <p class="mb-0 text-dark">
                            <span class="fw-bold">Item Name:</span> Acer Laptop
                        </p>
                        <p class="mb-0 text-dark">
                            <span class="fw-bold">Brand:</span> ACER
                        </p>
                        <p class="mb-0 text-dark">
                            <span class="fw-bold">Condition:</span> <span class="badge bg-success">Working</span>
                        </p>
                    </div>
                    <div class="card-footer p-2 border-top">
                        <p class="mb-0 fw-bold text-dark font-12 text-end">
                            Date Received: March 3, 2024
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="col-12">
        <ul class="pagination pagination-rounded justify-content-end my-2">
            <li class="page-item">
                <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                    <span aria-hidden="true">«</span>
                    <span class="visually-hidden">Previous</span>
                </a>
            </li>
            <li class="page-item active"><a class="page-link" href="javascript: void(0);">1</a></li>
            <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
            <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
            <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a></li>
            <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a></li>
            <li class="page-item">
                <a class="page-link" href="javascript: void(0);" aria-label="Next">
                    <span aria-hidden="true">»</span>
                    <span class="visually-hidden">Next</span>
                </a>
            </li>
        </ul>
    </div>

</div>

@endsection

@section("scripts")
    <script src="{{ asset("assets/libs/select2/js/select2.min.js") }}"></script>
    <script>
        $("[data-toggle=select2]").each(function() {
            const placeholder = $(this).data("placeholder");
            $(this).select2({
                placeholder: placeholder,
                minimumResultsForSearch: Infinity,
            })
        })
    </script>
@endsection