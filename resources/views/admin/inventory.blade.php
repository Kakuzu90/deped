@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
    Welcome {{ auth()->user()->name }}
    @else
    List of Items
    @endif
@endsection

@section("links")
		<link rel="stylesheet" href="{{ asset("assets/libs/select2/css/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/libs/flatpickr/flatpickr.min.css") }}">
@endsection

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Deped</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Inventory</a></li>
                    <li class="breadcrumb-item active">Items</li>
                </ol>
            </div>
            <h4 class="page-title">Items</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
							<button type="button" class="btn btn-blue waves-effect waves-light float-end"
								data-bs-toggle="modal" data-bs-target="#add"
							>
							<i class="mdi mdi-plus-circle"></i> Add Item
							</button>
                <h4 class="header-title mb-4">Manage Inventory</h4>
                <div class="table-responsive">
                    <table class="table table-hover m-0 table-centered" id="datatable">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Brand</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Place Origin</th>
                                <th class="text-center">Item Type</th>
                                <th class="text-center">Date Purchased</th>
                                <th class="text-center">Condition</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($items as $item)
													<tr>
															<td>
																<div class="d-flex flex-column">
																	<span class="text-primary fw-semibold">
																		<b class="text-dark">Stock No. </b>
																		{{ $item->stock_no }}
																	</span>
																	<span>
																		<b class="text-dark">Description </b>
																		{{ $item->name }}
																	</span>
																	<span>
																		<b class="text-dark">Unit </b>
																		{{ $item->unit }}
																	</span>
																</div>
															</td>
															<td class="text-center">
																<span>{{ $item->quantity() }}</span>
															</td>
															<td class="text-center">
																<span>{{ $item->brand }}</span>
															</td>
															<td class="text-center">
																<span>&#8369;{{ $item->moneyFormat() }}</span>
															</td>
															<td class="text-center">
																<span>{{ $item->place_origin }}</span>
															</td>
															<td class="text-center">
																	<span class="badge py-1 bg-{{ $item->itemColor() }}">{{ $item->itemType() }}</span>
															</td>
															<td class="text-center">
																<span>{{ $item->purchased_at->format("F d, Y") }}</span>
															</td>
															<td class="text-center">
																<span class="badge py-1 bg-{{ $item->itemStatusColor() }}">{{ $item->itemStatus() }}</span>
															</td>
															<td class="align-middle text-center">
																<div class="btn-group dropstart">
																		<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
																		<div class="dropdown-menu dropdown-menu-end">
																				<a class="dropdown-item" href="data:image/png;base64, {!! base64_encode(QrCode::format("svg")->size(200)->generate($item->id)) !!}" download="{{ $item->id }}.svg">
																						<i class="mdi mdi-qrcode me-2 text-muted font-18 vertical-middle"></i>
																						QrCode
																				</a>
																				<a class="dropdown-item" href="{{ route("admin.items.history", $item->id) }}">
																						<i class="mdi mdi-eye-plus me-2 text-muted font-18 vertical-middle"></i>
																						History
																				</a>
																				<a class="edit dropdown-item" href="javascript:void(0);" data-route="{{ route("admin.items.show", $item->id) }}">
																						<i class="mdi mdi-circle-edit-outline me-2 text-muted font-18 vertical-middle"></i>
																						Edit
																				</a>
																				<a class="delete dropdown-item" href="javascript:void(0);" data-header="{{ $item->name }}" data-route="{{ route("admin.items.show", $item->id) }}">
																						<i class="mdi mdi-delete-empty me-2 text-muted font-18 vertical-middle"></i>
																						Delete
																				</a>
																		</div>
																</div>
															</td>
														</tr>
														@endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include("admin.modal.inventory.add")
@include("admin.modal.inventory.edit")
@include("admin.modal.delete")
@endsection

@section("scripts")
    <script src="{{ asset("assets/js/pages/authentication.init.js") }}"></script>
    <script src="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.js") }}"></script>
    <script src="{{ asset("assets/js/pages/toastr.js") }}"></script>
    <script src="{{ asset("assets/libs/datatables.net/js/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js") }}"></script>
    <script src="{{ asset("assets/libs/jquery-mask-plugin/jquery.mask.min.js") }}"></script>
    <script src="{{ asset("assets/libs/flatpickr/flatpickr.min.js") }}"></script>
		<script src="{{ asset("assets/libs/select2/js/select2.min.js") }}"></script>
    @include("toastr")
    <script>
        $(document).ready(function(){
            $("#datatable").DataTable({
							lengthChange: false,
							pageLength: 15,
							order: [[5, "asc"]]
            })

						@error("stock_no")
                NotificationApp.send("Stock No. Exist", "{{ $message }}","top-right","#ee2b48","error")
            @enderror

						$("[data-toggle=select2]").each(function() {
                const placeholder = $(this).data("placeholder");
                $(this).select2({
                    placeholder: placeholder,
                    minimumResultsForSearch: Infinity,
                })
            })

						$('[data-toggle="input-mask"]').each(function(a,e){
                var t = $(e).data("maskFormat")
                n = $(e).data("reverse")
                null != n ? $(e).mask(t,{reverse:n}) : $(e).mask(t)
            })

						$('.flatpickr-human-friendly').flatpickr({
                altInput: true,
                altFormat: 'F j, Y',
                dateFormat: 'Y-m-d'
            });

						$(document).on("click", ".edit", function() {
                const route = $(this).data("route")

                $("#edit").modal("show")
                $("#edit #form_loader").removeClass("d-none")
                $("#edit #form_loader").addClass("d-block")
                $("#edit #form_container").removeClass("d-block")
                $("#edit #form_container").addClass("d-none")

                $.ajax({
                    url: route,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        $("#edit #form_loader").addClass("d-none")
                        $("#edit #form_loader").removeClass("d-block")
                        $("#edit #form_container").addClass("d-block")
                        $("#edit #form_container").removeClass("d-none")
                        $("#edit form").attr("action", route)

                        $("#edit textarea[name=name]").val(response.name)
												$("#edit input[name=stock_no]").val(response.stock_no)
												$("#edit input[name=unit]").val(response.unit)
                        $("#edit input[name=brand]").val(response.brand)
                        $("#edit input[name=amount]").val(response.amount)
                        $("#edit input[name=date_purchased]").val(response.purchased_at)
												$("#edit textarea[name=place_origin]").val(response.place_origin)

                        $('.flatpickr-human-friendly').flatpickr({
                            altInput: true,
                            altFormat: 'F j, Y',
                            dateFormat: 'Y-m-d'
                        });

												$("#edit select[name=item_type]").val(response.item_type).trigger("change");
                        $("#edit select[name=status]").val(response.status).trigger("change")
                    }
                })
            });

            $(document).on("click", ".delete", function() {
                const route = $(this).data("route");
                const title = $(this).data("header");

                $("#first-prompt").removeClass("d-none");
                $("#second-prompt").addClass("d-none");
                $("#second-prompt").removeClass("d-block");

                $("#delete .delete_data").text(title);
                $("#delete form").attr("action", route);
                $("#delete").modal("show");
            });

            $(document).on("click", ".btn-continue", function() {
                $("#first-prompt").addClass("d-none");
                $("#second-prompt").removeClass("d-none");
                $("#second-prompt").addClass("d-block");
            })
        });
    </script>
@endsection