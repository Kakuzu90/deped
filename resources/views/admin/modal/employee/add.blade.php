<div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTitle">Add Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <form action="{{ route("admin.employees.store") }}" method="POST">
            @csrf
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
                        Password <span class="text-danger fw-bolder">*</span>
                    </label>
                    <input type="password"
                            name="password" class="form-control"
                            placeholder="Enter password"
                            required
                        />
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Confirm Password <span class="text-danger fw-bolder">*</span>
                    </label>
                    <input type="password"
                            name="password_confirmation" class="form-control"
                            placeholder="Confirm password"
                            required
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
                <button type="submit" class="btn btn-success">Create</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>