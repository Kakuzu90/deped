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

<div class="card">
	<div class="card-body">
		<h5 class="card-title">Recent Requests</h5>
		<div class="table-responsive">
			<table class="table table-hover m-0">
				<thead>
					<tr>
						<th>Employee Name</th>
						<th class="text-center">Items</th>
						<th class="text-center">Request Type</th>
						<th class="text-center">Date Requested</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@forelse ($data["requests"] as $item)
						<tr>
							<td>
								<div class="d-flex justify-content-start align-items-center">
									<div class="avatar avatar-sm">
											<span class="avatar-title rounded-circle bg-primary">{{ $item->employee->full_name[0] }}</span>
									</div>
									<div class="d-flex flex-column ms-2">
											<p class="fw-bolder mb-0">{{ $item->employee->full_name }}</p>
											<span>{{ $item->employee->username }}</span>
									</div>
								</div>
							</td>
							<td class="text-center">
								<span class="badge bg-blue p-1">{{ $item->items->count() }}</span>
							</td>
							<td class="text-center">
								<span class="badge bg-{{ $item->requestTypeColor() }}">{{ $item->requestType() }}</span>
							</td>
							<td class="text-center">
								<a href="" 
									class="action-icon">
									<i class="mdi mdi-eye-plus"></i>
								</a>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="5" class="text-center"><span class="text-dark">No Items Available</span></td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
