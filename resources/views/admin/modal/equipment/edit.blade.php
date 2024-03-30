<div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTitle">Edit Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <form method="POST">
            @csrf
            @method("PUT")
            <div id="form_loader">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border avatar-lg text-danger" role="status"></div>
                </div>
            </div>
            <div class="d-none" id="form_container">
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
                    <div class="mb-2">
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
                    <div class="mb-1">
                        <label class="form-label">
                            Condition <span class="text-danger fw-bolder">*</span>
                        </label>
                        <select 
                            name="status"
                            class="form-control" data-toggle="select2"
                            data-width="100%"
                            data-placeholder="Select a condition"
                            required
                            >
                            <option value=""></option>
                            <option value="1">Working</option>
                            <option value="2">Repair</option>
                            <option value="3">Defective</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-success">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>