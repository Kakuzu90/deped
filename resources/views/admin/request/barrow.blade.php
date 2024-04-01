@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
    Welcome {{ auth()->user()->name }}
    @else
    Request for {{ $request->employee->full_name }}
    @endif
@endsection

@section("links")
	<link rel="stylesheet" href="{{ asset("assets/libs/ladda/ladda.min.css") }}">
	<link rel="stylesheet" href="{{ asset("assets/libs/ladda/ladda-themeless.min.css") }}">
	<style>
		.table-height {
			min-height: 500px;
		}
	</style>
@endsection

@section("content")
<div class="row">
	<div class="col-12">
			<div class="page-title-box">
					<div class="page-title-right">
							<ol class="breadcrumb m-0">
									<li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
									<li class="breadcrumb-item"><a href="{{ route("admin.requests.index") }}">Pending Request</a></li>
									<li class="breadcrumb-item active">{{ $request->requestType() }}</li>
							</ol>
					</div>
					<h4 class="page-title">{{ $request->requestType() }}</h4>
			</div>
	</div>

	<pending-request api="{{ route("api.admin.requests.barrow.index", $request->id) }}" />
</div>
@endsection

@section("scripts")
  <script src="{{ asset("js/app.js") }}"></script>
@endsection