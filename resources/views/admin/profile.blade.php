@extends("layouts.app")

@section("title")
    @if (Session::get("status"))
    Welcome {{ auth()->user()->name }}
    @else
    My Profile
    @endif
@endsection

@section("links")
    <link rel="stylesheet" href="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.css") }}">
@endsection

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Deped</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
            <h4 class="page-title">Profile</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-lg-4">
        <div class="card text-center">
            <div class="card-body">
                <img src="{{ asset("assets/images/avatar.jpg") }}" 
                    class="rounded-circle avatar-lg img-thumbnail" alt="Avatar" />
                
                <h4 class="mb-0">{{ auth()->user()->name }}</h4>
                <p class="text-danger fw-bold">Administrator</p>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title fw-bolder text-dark mb-0">General Information</h4>
            </div>
            <div class="card-body">
                <form action="{{ route("admin.profile.general") }}" method="POST" class="form-horizontal">
                    @csrf
                    @method("PUT")
                    <div class="row mb-2">
                        <label for="name" class="col-4 col-xl-3 col-form-label">Admin Name</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" 
                                name="name" id="name" 
                                value="{{ $user->name }}"
                                placeholder="Enter your name" required />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="username" class="col-4 col-xl-3 col-form-label">Admin Username</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" 
                                name="username" id="username" 
                                value="{{ $user->username }}"
                                placeholder="Enter your username" required />
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="password" class="col-4 col-xl-3 col-form-label">Admin Password</label>
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
                                <strong>Note: </strong> For security purpose since your updating your credentials please enter your password for verification.
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
            <div class="card-header border-bottom">
                <h4 class="card-title fw-bolder text-dark mb-0">Password</h4>
            </div>
            <div class="card-body">
                <form action="{{ route("admin.profile.password") }}" method="POST" class="form-horizontal">
                    @csrf
                    @method("PATCH")
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
                    <div class="row mb-2">
                        <label for="password3" class="col-4 col-xl-3 col-form-label">Admin Password</label>
                        <div class="col-8 col-xl-9">
                            <div class="input-group input-group-merge">
                                <input type="password" name="old" id="password3"
                                    class="form-control" placeholder="Enter your password" required
                                />
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                            <small class="text-danger">
                                <strong>Note: </strong> For security purpose since your updating your credentials please enter your password for verification.
                            </small>
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
    <script src="{{ asset("assets/libs/jquery-toast-plugin/jquery.toast.min.js") }}"></script>
    <script src="{{ asset("assets/js/pages/toastr.js") }}"></script>
    
    @include("toastr")
@endsection