@extends("layouts.default")

@section("title")
    @if (Session::get("status"))
        Welcome {{ auth()->guard("employee")->user()->name }}
    @else
        My Profile
    @endif
@endsection

@section("links")
    <link rel="stylesheet" href="{{ asset("assets/libs/select2/css/select2.min.css") }}">
@endsection

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Deped</a></li>
                    <li class="breadcrumb-item active">My Profile</li>
                </ol>
            </div>
            <h4 class="page-title">My Profile</h4>
        </div>
    </div>

    <div class="col-xl-5 col-lg-5 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ asset("assets/images/employee.jpg") }}" 
                    class="rounded-circle avatar-lg img-thumbnail" alt="Avatar" />
                
                <h5 class="mb-0">{{ $employee->full_name }}</h5>
                <p class="text-danger fw-bold">Employee</p>

                <div class="text-start mt-3">
                    <h4 class="font-15 text-uppercase text-dark fw-bold">About Me:</h4>

                    <p class="text-dark mb-1 font-14">
                        <strong>Position :</strong> <span class="ms-2">{{ $employee->position->name }}</span>
                    </p>
                    <p class="text-dark mb-1 font-14">
                        <strong>Office :</strong> <span class="ms-2">{{ $employee->office->name }}</span>
                    </p>
                    <p class="text-dark mb-1 font-14">
                        <strong>Date Joined :</strong> <span class="ms-2">{{ $employee->created_at->format("F d, Y") }}</span>
                    </p>
                    <p class="text-dark mb-1 font-14">
                        <strong>Status :</strong> <span class="ms-2 py-1 badge bg-{{ $employee->accountColor() }}">{{ $employee->accountText() }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-7 col-lg-7 col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title text-dark">Account Information</h4>
                <form action="{{ route("employee.profile.general") }}" method="POST" class="form-horizontal">
                    @csrf
                    @method("PUT")
                    <div class="row mb-2">
                        <label for="name" class="col-4 col-xl-3 col-form-label">Full Name</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" 
                                name="name" id="name" 
                                value="{{ $employee->full_name }}"
                                placeholder="Enter your name" required />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="username" class="col-4 col-xl-3 col-form-label">Username</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" 
                                name="username" id="username" 
                                value="{{ $employee->username }}"
                                placeholder="Enter your username" required />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-4 col-xl-3 col-form-label">Office</label>
                        <div class="col-8 col-xl-9">
                            <select name="office" class="form-control" data-toggle="select2" data-placeholder="Select a value" required>
                                @foreach ($offices as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="username" class="col-4 col-xl-3 col-form-label">Position</label>
                        <div class="col-8 col-xl-9">
                            <select name="position" class="form-control" data-toggle="select2" data-placeholder="Select a value" required>
                                @foreach ($positions as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="password" class="col-4 col-xl-3 col-form-label">Password</label>
                        <div class="col-8 col-xl-9">
                            <div class="input-group input-group-merge">
                                <input type="password" name="password" id="password"
                                    class="form-control" placeholder="Enter your password" required
                                />
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                            <small class="text-danger">
                                <strong>Note: </strong> For security purposes since your updating your credentials please enter your password for verification.
                            </small>
                        </div>
                    </div>

                    <div class="offset-lg-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save-all"></i>
                            <span class="ms-1">Save Changes</span>
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-dark">Change Password</h4>
                <form action="{{ route("employee.profile.password") }}" method="POST" class="form-horizontal">
                    @csrf
                    @method("PATCH")
                    <div class="row mb-2">
                        <label for="password3" class="col-4 col-xl-3 col-form-label">Current Password</label>
                        <div class="col-8 col-xl-9">
                            <div class="input-group input-group-merge">
                                <input type="password" name="current" id="password3"
                                    class="form-control" placeholder="Enter your password" required
                                />
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="password1" class="col-4 col-xl-3 col-form-label">New Password</label>
                        <div class="col-8 col-xl-9">
                            <div class="input-group input-group-merge">
                                <input type="password" name="password" id="password1"
                                    class="form-control" placeholder="Enter your new password" required
                                />
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="password2" class="col-4 col-xl-3 col-form-label">Confirm Password</label>
                        <div class="col-8 col-xl-9">
                            <div class="input-group input-group-merge">
                                <input type="password" name="password_confirmation" id="password2"
                                    class="form-control" placeholder="Confirm your new password" required
                                />
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="offset-lg-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save-all"></i>
                            <span class="ms-1">Save New Password</span>
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
    <script src="{{ asset("assets/js/pages/authentication.init.js") }}"></script>
    <script src="{{ asset("assets/libs/select2/js/select2.min.js") }}"></script>
    <script>
        $("[data-toggle=select2]").each(function() {
            const placeholder = $(this).data("placeholder");
            $(this).select2({
                placeholder: placeholder,
                minimumResultsForSearch: Infinity,
            })
        })

        $("select[name=office]").val('{{ $employee->office_id }}').trigger("change");
        $("select[name=position]").val('{{ $employee->position_id }}').trigger("change");
    </script>
@endsection