<div class="modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <form method="POST">
            @csrf
            @method("DELETE")
            <div class="modal-body">
                <div class="text-center">
                    <i class="dripicons-warning display-3 text-danger"></i>
                    <h3 class="my-0">Delete Prompt!</h3>
                </div>
                <div id="first-prompt" class="text-center">
                    <p class="mt-3">Are you certain that you want to delete <span class="delete_data text-danger text-decoration-underline"></span> data?</p>
                    <button type="button" class="btn btn-outline-danger btn-continue">Continue</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                <div id="second-prompt" class="text-center mt-3 d-none">
                    <div class="mb-2">
                        <div class="input-group input-group-merge">
                            <input type="password" name="password"
                                class="form-control" placeholder="Enter your password" required
                            />
                            <div class="input-group-text" data-password="false">
                                <span class="password-eye"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger">Yes, delete it!</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>