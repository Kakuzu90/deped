<div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
					<div class="modal-header">
							<h5 class="modal-title" id="addTitle">Add Item</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
			<form action="{{ route("admin.items.store") }}" method="POST">
					@csrf
					<div class="modal-body">
						<div class="row g-1">
							<div class="col-md-12">
								<label class="form-label">
									Item Description <span class="text-danger fw-bolder">*</span>
								</label>
								<textarea name="name" class="form-control" rows="2" placeholder="Item Description" required></textarea>
							</div>

							<div class="col-md-6">
								<label class="form-label">
									Stock No. <span class="text-danger fw-bolder">*</span>
								</label>
								<input type="text"
									name="stock_no" class="form-control"
									placeholder="Enter stock no" required
								/>
							</div>

							<div class="col-md-6">
								<label class="form-label">
									Unit <span class="text-danger fw-bolder">*</span>
								</label>
								<input type="text"
									name="unit" class="form-control"
									placeholder="Enter unit" required
								/>
							</div>	

							<div class="col-md-6">
								<label class="form-label">
										Brand <span class="text-danger fw-bolder">*</span>
								</label>
								<input type="text"
												name="brand" class="form-control"
												placeholder="Enter brand" required
										/>
							</div>

							<div class="col-md-6">
								<label class="form-label">
										Quantity <span class="text-danger fw-bolder">*</span>
								</label>
								<input type="number"
												name="quantity" class="form-control"
												value="1" min="1"
												placeholder="Enter quantity" required
										/>
						</div>

						<div class="col-md-6">
							<label class="form-label">
									Amount <span class="text-danger fw-bolder">*</span>
							</label>
							<input type="text"
											name="amount" class="form-control"
											data-toggle="input-mask" data-mask-format="000,000,000,000,000.00" data-reverse="true"
											placeholder="Enter amount" required
									/>
						</div>

						<div class="col-md-6">
							<label class="form-label">
									Date Purchased <span class="text-danger fw-bolder">*</span>
							</label>
							<input type="text"
											name="date_purchased" class="form-control flatpickr-human-friendly"
											placeholder="Select a date" required
									/>
						</div>

						<div class="col-md-6">
							<label class="form-label">
								Place of Origin <span class="text-danger fw-bolder">*</span>
							</label>
							<textarea name="place_origin" class="form-control h-auto" rows="2" placeholder="Item place of origin" required></textarea>
						</div>

						<div class="col-md-6">
							<label class="form-label">
								Item Type <span class="text-danger fw-bolder">*</span>
							</label>
							<select 
									name="item_type"
									class="form-control" data-toggle="select2"
									data-width="100%"
									data-placeholder="Select a value"
									required
									>
									<option value=""></option>
									<option value="1">Equipment</option>
									<option value="2">Supply</option>
							</select>
						</div>

						</div>	
					</div>
					<div class="modal-footer">
							<button type="submit" class="btn btn-success">Create</button>
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					</div>
			</form>
			</div>
	</div>
</div>