@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
    Welcome {{ auth()->user()->name }}
    @else
    History of {{ $item->name }}
    @endif
@endsection

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Deped</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
                    <li class="breadcrumb-item active">{{ $item->id }}</li>
                </ol>
            </div>
            <h4 class="page-title">History of {{ $item->name }}</h4>
        </div>
    </div>

		<div class="col-12">
			<div class="timeline">
				<article class="timeline-item">
					<h2 class="m-0 d-none">&nbsp;</h2>
					<div class="time-show mt-0">
							<a href="#" class="btn btn-primary width-lg">Today</a>
					</div>
				</article>

				@foreach ($item->histories as $key => $timeline)
				<article class="{{ isOdd($key + 1) }}">
					<div class="timeline-desk">
							<div class="timeline-box">
									<span class="arrow-alt"></span>
									<span class="timeline-icon"><i class="mdi mdi-adjust"></i></span>
									<h4 class="mt-0 text-primary font-16">{{ $timeline->employee->full_name }}</h4>
									<p class="text-dark mb-0">
										<b>Quantity: </b>
										{{ $timeline->quantity }}
									</p>
									<p class="text-dark mb-0">
										<b>Type: </b>
										{{ $timeline->itemType() }}
									</p>
									<p class="text-dark mb-0">
										<b>Status: </b>
										{{ $timeline->itemStatus() }}
									</p>
									<p class="text-dark mb-0">
										<b>Date: </b>
										{{ $timeline->created_at->format("F d, Y") }}
									</p>
							</div>
					</div>
				</article>
				@endforeach
			</div>
		</div>
</div>

@endsection