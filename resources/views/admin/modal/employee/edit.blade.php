<div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTitle">Edit Employee</h5>
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
                            Employee Name <span class="text-danger fw-bolder">*</span>
                        </label>
                        <input type="text"
                                name="name" class="form-control"
                                placeholder="Enter employee name"
                                required
                            />
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Employee Username <span class="text-danger fw-bolder">*</span>
                        </label>
                        <input type="text"
                                name="username" class="form-control"
                                placeholder="Enter employee username"
                                required
                            />
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Password
                        </label>
                        <input type="password"
                                name="password" class="form-control"
                                placeholder="Enter password"
                                
                            />
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Confirm Password
                        </label>
                        <input type="password"
                                name="password_confirmation" class="form-control"
                                placeholder="Confirm password"
                                
                            />
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Position <span class="text-danger fw-bolder">*</span>
                        </label>
                        <select 
                            name="position"
                            class="form-control" data-toggle="select2"
                            data-width="100%"
                            data-placeholder="Select a position"
                            required
                            >
                            <option value=""></option>
                            @foreach (getPositions() as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">
                            Office <span class="text-danger fw-bolder">*</span>
                        </label>
                        <select 
                            name="office"
                            class="form-control" data-toggle="select2"
                            data-width="100%"
                            data-placeholder="Select a office"
                            required
                            >
                            <option value=""></option>
                            @foreach (getOffices() as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
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