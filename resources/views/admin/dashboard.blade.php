@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
        Welcome {{ auth()->user()->name }}
    @else
        Dashboard Page
    @endif
@endsection

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Deped</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6 col-xl-4">
        <x-widget
            title="Total Positions" :count="$data['position']" icon="mdi mdi-offer" color="primary"
            />
    </div>
    <div class="col-md-6 col-xl-4">
        <x-widget
            title="Total Offices" :count="$data['office']" icon="mdi mdi-office-building-marker" color="success"
            />
    </div>
    <div class="col-md-6 col-xl-4">
        <x-widget
            title="Total Employees" :count="$data['employee']" icon="mdi mdi-account-arrow-left" color="info"
            />
    </div>
    <div class="col-md-6 col-xl-4">
        <x-widget
            title="Total Supplies" :count="$data['supply']" icon="mdi mdi-box-shadow" color="warning"
            />
    </div>
    <div class="col-md-6 col-xl-4">
        <x-widget
            title="Total Equipments" :count="$data['equipment']" icon="mdi mdi-tools" color="danger"
            />
    </div>
</div>
@endsection