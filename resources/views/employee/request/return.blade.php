@extends("layouts.default")

@section("title")
    @if (Session::get("status"))
        Welcome {{ auth()->guard("employee")->user()->name }}
    @else
        Return Request
    @endif
@endsection

@section("links")
		<link rel="stylesheet" href="{{ asset("assets/libs/ladda/ladda.min.css") }}">
		<link rel="stylesheet" href="{{ asset("assets/libs/ladda/ladda-themeless.min.css") }}">
    <style>
			.ps__rail-y {
				z-index: 2;
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
                    <li class="breadcrumb-item"><a href="{{ route("employee.requests.index") }}">Request</a></li>
                    <li class="breadcrumb-item active">Return Request</li>
                </ol>
            </div>
            <h4 class="page-title">Return Request</h4>
        </div>
    </div>
    
		<return-request api="http://127.0.0.1:8000/api/requests/" />
</div>
@endsection

@section("scripts")
  <script src="{{ asset("js/app.js") }}"></script>
@endsection