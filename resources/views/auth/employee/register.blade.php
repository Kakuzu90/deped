@extends("layouts.auth")

@section("title")
    Register Page
@endsection

@section("links")
    <link rel="stylesheet" href="{{ asset("assets/libs/select2/css/select2.min.css") }}">
@endsection

@section("content")
    <div class="account-pages mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-6 col-md-8">
                    <div class="card bg-pattern">
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <div class="auth-brand">
                                    <a href="" class="logo text-center">
                                        <span class="logo-lg">
                                            <img src="{{ asset("assets/images/deped.png") }}" height="50" alt="Deped Logo" />
                                        </span>
                                    </a>
                                </div>
                                <p class="my-3">
                                    Don't have an account? Create your account, it takes less than a minute
                                </p>
                            </div>

                            <form action="{{ route("register.store") }}" method="POST" class="form">
                                @csrf
                                <div class="row g-2 mb-3">

                                    <div class="col-sm-6">
                                        <label for="fullname" class="form-label">
                                            Fullname
                                        </label>
                                        <input 
                                            type="text" class="form-control @error("fullname") is-invalid @enderror" name="fullname"
                                            id="fullname" placeholder="Enter your fullname" value="{{ old("fullname") }}"
                                            autofocus required />
                                        @error("fullname")
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="form-label" for="username">
                                            Username
                                        </label>
                                        <input 
                                            type="text" class="form-control @error("username") is-invalid @enderror" name="username"
                                            id="username" placeholder="Enter your username"
                                            value="{{ old("username") }}"
                                            required />
                                        @error("username")
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="password" class="form-label">
                                            Password
                                        </label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" name="password" id="password"
                                                class="form-control @error("password") is-invalid @enderror" placeholder="Enter your password" required
                                            />
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                            @error("password")
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="password_confirmation" class="form-label">
                                            Confirm Password
                                        </label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="form-control @error("password") is-invalid @enderror" placeholder="Enter your password" required
                                            />
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="position" class="form-label">
                                            Position
                                        </label>
                                        <select 
                                            name="position" id="position" 
                                            class="form-control" data-toggle="select2"
                                            data-width="100%"
                                            data-placeholder="Select a position"
                                            required
                                            >
                                            <option value=""></option>
                                            @foreach ($positions as $position)
                                                <option value="{{ $position->id }}">
                                                    {{ $position->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
    
                                    <div class="col-sm-6">
                                        <label for="office" class="form-label">
                                            Office
                                        </label>
                                        <select 
                                            name="office" id="office" 
                                            class="form-control" data-toggle="select2"
                                            data-width="100%"
                                            data-placeholder="Select a office"
                                            required
                                            >
                                            <option value=""></option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}">
                                                    {{ $office->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="text-center d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>

                            </form>



                            <p class="text-center mt-3 mb-0">
                                Already have an account?
                                <a href="{{ route("login") }}" class="ms-1">
                                    <b>Login here</b>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script src="{{ asset("assets/libs/select2/js/select2.min.js") }}"></script>
    <script>
        $("[data-toggle=select2]").each(function() {
            const placeholder = $(this).data("placeholder");
            $(this).select2({
                placeholder: placeholder,
                minimumResultsForSearch: Infinity,
            })
        })
    </script>
@endsection