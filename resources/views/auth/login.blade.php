@extends("layouts.auth")

@section("title")
    Login Page
@endsection

@section("content")
<div class="col-xl-3 col-lg-6 col-md-8 col-11">
    <div class="card bg-pattern">
        <div class="card-body p-4">
            <div class="text-center w-75 m-auto">
                <div class="auth-brand">
                    <a href="{{ route("login") }}" class="logo text-center">
                        <span class="logo-lg">
                            <img src="{{ asset("assets/images/deped.png") }}" height="50" alt="Deped Logo" />
                        </span>
                    </a>
                </div>
                <p class="my-3">
                    Enter your username and password to access your account panel.
                </p>
            </div>

            <form action="{{ route("admin.login.store") }}" method="POST" class="form">
                @csrf
                <div class="mb-2">
                    <label class="form-label" for="username">
                        Username
                    </label>
                    <input 
                        type="text" class="form-control @error("login_error") is-invalid @enderror" name="username"
                        id="username" placeholder="Enter your username"
                        autofocus required />
                    @error("login_error")
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="password" class="form-label">
                        Password
                    </label>
                    <div class="input-group input-group-merge">
                        <input type="password" name="password" id="password"
                            class="form-control" placeholder="Enter your password" required
                        />
                        <div class="input-group-text" data-password="false">
                            <span class="password-eye"></span>
                        </div>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input"
                            name="remember" id="remember" checked />
                        <label for="remember" class="form-check-label">
                            Remember me
                        </label>
                    </div>
                </div>

                <div class="text-center d-grid">
                    <button type="submit" class="btn btn-primary">
                        Login
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection