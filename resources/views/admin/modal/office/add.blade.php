<div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTitle">Add Office</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <form action="{{ route("admin.offices.store") }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-1">
                    <label class="form-label">
                        Office Name <span class="text-danger fw-bolder">*</span>
                    </label>
                    <input type="text"
                            name="name" class="form-control"
                            placeholder="Enter position name" required
                        />
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