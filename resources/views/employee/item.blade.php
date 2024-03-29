@extends("layouts.default")

@section("title")
    @if (Session::get("status"))
        Welcome {{ auth()->guard("employee")->user()->name }}
    @else
        Inventory Request
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
                    <li class="breadcrumb-item active">Request Items</li>
                </ol>
            </div>
            <h4 class="page-title">Request Items</h4>
        </div>
    </div>

    
</div>
@endsection

@section("scripts")
    
@endsection