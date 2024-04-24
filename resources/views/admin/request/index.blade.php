@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
        Welcome {{ auth()->user()->fullname}}
    @else
        Request Page
    @endif
@endsection

@section("links")
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
                    <li class="breadcrumb-item active">Request Page</li>
                </ol>
            </div>
            <h4 class="page-title">Request Page</h4>
        </div>
    </div>
    
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="header-title mb-4">Manage Requests</h4>
					<div class="table-responsive">
						<table class="table table-hover m-0" id="datatable">
							<thead>
								<tr>
									<th>Employee Name</th>
									<th class="text-center">Office</th>
									<th class="text-center">Items</th>
									<th class="text-center">Request Type</th>
									<th class="text-center">Date Requested</th>
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
	<script src="{{ asset("assets/libs/datatables.net/js/jquery.dataTables.min.js") }}"></script>
	<script src="{{ asset("assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js") }}"></script>
	<script>
		$(document).ready(function(){
				$("#datatable").DataTable({
					lengthChange: false,
					pageLength: 15,
				})

		});
	</script>
@endsection