<div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTitle">Edit Position</h5>
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
                            Supply Name <span class="text-danger fw-bolder">*</span>
                        </label>
                        <input type="text"
                                name="name" class="form-control"
                                placeholder="Enter supply name" required
                            />
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Quantity <span class="text-danger fw-bolder">*</span>
                        </label>
                        <input type="number"
                                name="quantity" class="form-control"
                                value="1" min="1"
                                placeholder="Enter quantity" required
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