@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
        Welcome {{ auth()->user()->name }}
    @else
        Repair Request
    @endif
@endsection

@section("links")
		<link rel="stylesheet" href="{{ asset("assets/libs/ladda/ladda.min.css") }}">
		<link rel="stylesheet" href="{{ asset("assets/libs/ladda/ladda-themeless.min.css") }}">
		<link rel="stylesheet" href="{{ asset("assets/css/theme.css") }}">
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
								<li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
								<li class="breadcrumb-item"><a href="{{ route("admin.employees.profile", $employee->id) }}">{{ $employee->full_name }}</a></li>
								<li class="breadcrumb-item active">Repair Request</li>
						</ol>
				</div>
				<h4 class="page-title">Repair Request</h4>
		</div>
	</div>
    
		<repair-request api="http://127.0.0.1:8000/api/requests/{{ $employee->id }}/" />
</div>
@endsection

@section("scripts")
  <script src="{{ asset("js/app.js") }}"></script>
@endsection