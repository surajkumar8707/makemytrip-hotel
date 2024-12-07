@extends('vendors.layout.app')
@section('title', 'App Settings Page')

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span>
            {{ Route::currentRouteName() == 'vendor.profile' ? 'Profile' : 'Change Password' }}</h4>
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item">
                <a class="nav-link {{ isActiveRoute(['vendor.profile']) }}" href="{{ route('vendor.profile') }}">
                    <i class="bx bx-user me-1"></i> Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ isActiveRoute(['vendor.password']) }}" href="{{ route('vendor.password') }}">
                    <i class="bx bx-key me-1"></i> Change Password
                </a>
            </li>
        </ul>

        <div class="card mb-4">
            @if (Route::currentRouteName() == 'vendor.profile')
                <h5 class="card-header">Profile Details</h5>
                <hr class="my-0">
                <div class="card-body">
                    <div class="form-group display-vendor-info" style="display: block">
                        <p><strong>Name:</strong> {{ $vendor->name }}</p>
                        <p><strong>E-mail:</strong> {{ $vendor->email }}</p>
                        <p><strong>Country:</strong> {{ $vendor->country }}</p>
                        <p><strong>State:</strong> {{ $vendor->state }}</p>
                        <p><strong>City:</strong> {{ $vendor->city }}</p>
                        <p><strong>Phone:</strong> {{ $vendor->phone }}</p>
                        <p><strong>Address:</strong> {{ $vendor->address }}</p>
                        <p><strong>Profile Image:</strong> {{ $vendor->image }}</p>
                    </div>
                    <div class="form-group display-vendor-form" style="display: none">
                        <form action="{{ route('vendor.profile.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text"
                                            id="name" name="name" value="{{ old('name', $vendor->name) }}"
                                            autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input class="form-control @error('email') is-invalid @enderror" type="email"
                                            id="email" name="email" value="{{ old('email', $vendor->email) }}"
                                            placeholder="email@example.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input class="form-control" type="text" id="phone" name="phone"
                                            placeholder="phone" value="{{ old('phone', $vendor->phone) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <input class="form-control" type="text" id="city" name="city"
                                            placeholder="city" value="{{ old('city', $vendor->city) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="country" class="form-label">Country</label>
                                        <input class="form-control" type="text" id="country" name="country"
                                            placeholder="country" value="{{ old('country', $vendor->country) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="state" class="form-label">State</label>
                                        <input class="form-control" type="text" id="state" name="state"
                                            placeholder="state" value="{{ old('state', $vendor->state) }}">
                                    </div>
                                </div>
                            </div>




                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input class="form-control" type="text" id="address" name="address"
                                    placeholder="address" value="{{ old('address', $vendor->address) }}">
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                <button type="button"
                                    class="btn btn-outline-secondary display-vendor-form-button">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary display-vendor-info-button">Edit</button>
                    </div>
                </div>
            @else
                <h5 class="card-header">Change Password</h5>
                <hr class="my-0">
                <div class="card-body">
                    <form action="{{ route('vendor.update.password') }}" method="POST">
                        @csrf

                        <div class="mb-3 form-group">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input class="form-control @error('current_password') is-invalid @enderror" type="password"
                                id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-group">
                            <label for="password" class="form-label">New Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" type="password"
                                id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-group">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input class="form-control" type="password" id="password_confirmation"
                                name="password_confirmation">
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Update Password</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.display-vendor-info-button').click(function() {
                $('.display-vendor-info').hide();
                $('.display-vendor-form').show();
                $(this).hide();
            });

            $('.display-vendor-form-button').click(function() {
                $('.display-vendor-form').hide();
                $('.display-vendor-info').show();
                $('.display-vendor-info-button').show();
            });
        });
    </script>
@endpush
