<div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTitle">Add Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <form action="{{ route("admin.equipments.store") }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">
                        Equipment Name <span class="text-danger fw-bolder">*</span>
                    </label>
                    <input type="text"
                            name="name" class="form-control"
                            placeholder="Enter equipment name" required
                        />
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Serial No <span class="text-danger fw-bolder">*</span>
                    </label>
                    <input type="text"
                            name="serial" class="form-control"
                            placeholder="Enter serial no" required
                        />
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Model No <span class="text-danger fw-bolder">*</span>
                    </label>
                    <input type="text"
                            name="model" class="form-control"
                            placeholder="Enter model no" required
                        />
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Brand <span class="text-danger fw-bolder">*</span>
                    </label>
                    <input type="text"
                            name="brand" class="form-control"
                            placeholder="Enter brand" required
                        />
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Amount <span class="text-danger fw-bolder">*</span>
                    </label>
                    <input type="text"
                            name="amount" class="form-control"
                            data-toggle="input-mask" data-mask-format="000,000,000,000,000.00" data-reverse="true"
                            placeholder="Enter amount" required
                        />
                </div>
                <div class="mb-1">
                    <label class="form-label">
                        Date Purchased <span class="text-danger fw-bolder">*</span>
                    </label>
                    <input type="text"
                            name="date_purchased" class="form-control flatpickr-human-friendly"
                            placeholder="Select a date" required
                        />
                </div>
								<div class="mb-1">
									<label class="form-label">
										Description <span class="text-danger fw-bolder">*</span>
									</label>
									<textarea name="description" class="form-control" rows="5" placeholder="Equipment Description" required></textarea>
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