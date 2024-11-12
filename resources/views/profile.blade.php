@extends('layouts.layout')

@section('content')
    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                <div class="pb-24 ms-16 mb-24 me-16 mt--100">
                    <div class="text-center border border-top-0 border-start-0 border-end-0">
                        <h6 class="mb-0 mt-16">{{ $user->name }}</h6>
                        <span class="text-secondary-light mb-16">{{ $user->email }}</span>
                    </div>
                    <div class="mt-24">
                        <h6 class="text-xl mb-16">Personal Info</h6>
                        <ul>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">NIK</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ $user->nik }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Full Name</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ $user->name }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Email</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ $user->email }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Phone Number</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ $user->phone_number }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Jabatan</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ $user->jabatan }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Address</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ $user->address }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1">
                                <span class="w-30 text-md fw-semibold text-primary-light">Role</span>
                                <span class="w-70 text-secondary-light fw-medium">: {{ ucfirst($user->role) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body p-24">
                    <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab"
                                aria-controls="pills-edit-profile" aria-selected="true">
                                Edit Profile
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-change-password-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-change-password" type="button" role="tab"
                                aria-controls="pills-change-password" aria-selected="false">
                                Change Password
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-upload-signature-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-upload-signature" type="button" role="tab"
                                aria-controls="pills-upload-signature" aria-selected="false">
                                Upload Signature
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- Edit Profile Tab -->
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
                            aria-labelledby="pills-edit-profile-tab">

                            <form action="{{ route('profile.update', $user->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="nik"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Full
                                                NIK</label>
                                            <input type="text" name="nik" class="form-control radius-8"
                                                id="nik" placeholder="Enter nik" value="{{ $user->nik }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Full
                                                Name</label>
                                            <input type="text" name="name" class="form-control radius-8"
                                                id="name" placeholder="Enter Full Name" value="{{ $user->name }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="email"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Email</label>
                                            <input type="email" name="email" class="form-control radius-8"
                                                id="email" placeholder="Enter email address"
                                                value="{{ $user->email }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="phone_number"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Phone
                                                Number</label>
                                            <input type="text" name="phone_number" class="form-control radius-8"
                                                id="phone_number" placeholder="Enter phone number"
                                                value="{{ $user->phone_number }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="jabatan"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Jabatan</label>
                                            <input type="text" name="jabatan" class="form-control radius-8"
                                                id="jabatan" placeholder="Enter Jabatan" value="{{ $user->jabatan }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="address"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Address</label>
                                            <textarea name="address" class="form-control radius-8" id="address" placeholder="Enter address">{{ $user->address }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="button"
                                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Change Password Tab -->
                        <div class="tab-pane fade" id="pills-change-password" role="tabpanel"
                            aria-labelledby="pills-change-password-tab">
                            <form action="{{ route('profile.change_password', $user->id) }}" method="POST">
                                @csrf
                                <div class="mb-20">
                                    <label for="new-password"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">New Password</label>
                                    <input type="password" name="password" class="form-control radius-8"
                                        id="new-password" placeholder="Enter New Password">
                                </div>
                                <div class="mb-20">
                                    <label for="confirm-password"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Confirm
                                        Password</label>
                                    <input type="password" name="password_confirmation" class="form-control radius-8"
                                        id="confirm-password" placeholder="Confirm Password">
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-upload-signature" role="tabpanel"
                            aria-labelledby="pills-upload-signature-tab">
                            <h3>Upload Tanda Tangan</h3>
                            <form action="{{ route('profile.upload.signature') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="signature">Upload Tanda Tangan (PNG):</label>
                                    <input type="file" name="signature" accept="image/png" required>
                                </div>
                                <button type="submit" class="btn btn-success">Upload Tanda Tangan</button>
                            </form>

                            @if ($user->signature_path)
                                <h4>Tanda Tangan Saat Ini:</h4>
                                <img src="{{ asset('storage/' . $user->signature_path) }}" alt="Tanda Tangan"
                                    style="width: 200px;">
                            @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
