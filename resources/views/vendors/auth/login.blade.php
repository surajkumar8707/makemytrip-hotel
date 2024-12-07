@extends('vendors.blank.app')

@section('content')
    <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
            <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <a href="" class="">
                        <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>Vendor</h3>
                    </a>
                    <h3>Sign In</h3>
                </div>
                <form action="{{ route('vendor.login.form') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="floatingInput" placeholder="name@example.com" required value="{{ old('email') }}">
                        <label for="floatingInput">Email address</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            id="floatingPassword" placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember">
                            <label class="form-check-label" for="exampleCheck1">Remember me</label>
                        </div>
                        <a href="#">Forgot Password</a>
                    </div>

                    <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Sign In</button>
                    <p class="text-center mb-0">Don't have an Account? <a href="{{ route('vendor.register.form') }}">Sign
                            Up</a></p>
                </form>
            </div>
        </div>
    </div>
@endsection
