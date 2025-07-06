@extends('layouts.default')

@section('content')
    <div class="container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2">{{ __('Profile') }}</h1>
            </div>
        </div>

        <!-- Profile Information Section -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Profile Information') }}</h3>
                        <p class="text-muted mb-0">{{ __("Update your account's profile information and email address.") }}
                        </p>
                    </div>
                    <div class="card-body">
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">{{ __('Name') }}</label>
                                        <input id="name" name="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nomor_identifikasi" class="form-label">

                                            @if (Auth::user()->role == 'mahasiswa')
                                                NIM
                                            @else
                                                NIP / NIDN
                                            @endif

                                        </label>
                                        <input id="nomor_identifikasi" name="nomor_identifikasi" type="text"
                                            class="form-control @error('nomor_identifikasi') is-invalid @enderror"
                                            value="{{ old('nomor_identifikasi', $user->nomor_identifikasi) }}"
                                            autocomplete="off" placeholder="NIK/KTP/Passport" disabled>
                                        @error('nomor_identifikasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">{{ __('Email') }}</label>
                                        <input id="email" name="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required autocomplete="username">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                            <div class="mt-2">
                                                <div class="alert alert-warning py-2" role="alert">
                                                    <small>
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        {{ __('Your email address is unverified.') }}
                                                        <button form="send-verification"
                                                            class="btn btn-link p-0 text-decoration-underline small text-warning">
                                                            {{ __('Click here to re-send the verification email.') }}
                                                        </button>
                                                    </small>
                                                </div>

                                                @if (session('status') === 'verification-link-sent')
                                                    <div class="alert alert-success py-2 mt-2" role="alert">
                                                        <small>
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            {{ __('A new verification link has been sent to your email address.') }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>{{ __('Save Changes') }}
                                </button>

                                @if (session('status') === 'profile-updated')
                                    <div class="alert alert-success py-1 px-3 mb-0 d-flex align-items-center"
                                        id="profile-saved">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <small>{{ __('Profile updated successfully!') }}</small>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Password Section -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Update Password') }}</h3>
                        <p class="text-muted mb-0">
                            {{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="update_password_current_password" class="form-label">
                                            <i class="fas fa-lock me-1"></i>{{ __('Current Password') }}
                                        </label>
                                        <input id="update_password_current_password" name="current_password" type="password"
                                            class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                            autocomplete="current-password" placeholder="Enter your current password">
                                        @error('current_password', 'updatePassword')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="update_password_password" class="form-label">
                                            <i class="fas fa-key me-1"></i>{{ __('New Password') }}
                                        </label>
                                        <input id="update_password_password" name="password" type="password"
                                            class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                            autocomplete="new-password" placeholder="Enter new password">
                                        @error('password', 'updatePassword')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <small>Password minimal 8 karakter, kombinasi huruf dan angka</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="update_password_password_confirmation" class="form-label">
                                            <i class="fas fa-check-double me-1"></i>{{ __('Confirm Password') }}
                                        </label>
                                        <input id="update_password_password_confirmation" name="password_confirmation"
                                            type="password"
                                            class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                            autocomplete="new-password" placeholder="Confirm new password">
                                        @error('password_confirmation', 'updatePassword')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-shield-alt me-1"></i>{{ __('Update Password') }}
                                </button>

                                @if (session('status') === 'password-updated')
                                    <div class="alert alert-success py-1 px-3 mb-0 d-flex align-items-center"
                                        id="password-saved">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <small>{{ __('Password updated successfully!') }}</small>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- Delete Account Section -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title mb-0">{{ __('Delete Account') }}</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="text-danger mb-2">{{ __('Danger Zone') }}</h5>
                            <p class="text-muted mb-0">
                                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                            </p>
                        </div>
                    </div>

                    <hr class="my-3">

                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmUserDeletion"
                    >
                        <i class="fas fa-trash-alt me-1"></i>{{ __('Delete Account') }}
                    </button>
                </div>
            </div>
        </div>
    </div> --}}
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title d-flex align-items-center" id="confirmUserDeletionLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ __('Confirm Account Deletion') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger d-flex align-items-start" role="alert">
                            <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                            <div>
                                <strong>{{ __('Are you sure you want to delete your account?') }}</strong>
                                <p class="mb-0 mt-2">
                                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>{{ __('Current Password') }}
                            </label>
                            <input id="password" name="password" type="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                placeholder="{{ __('Enter your password to confirm') }}" required>
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>{{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-1"></i>{{ __('Yes, Delete My Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-hide success messages after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const savedMessages = document.querySelectorAll('#profile-saved, #password-saved');
            savedMessages.forEach(function(message) {
                if (message) {
                    setTimeout(function() {
                        message.style.display = 'none';
                    }, 3000);
                }
            });

            // Show modal if there are user deletion errors
            @if ($errors->userDeletion->isNotEmpty())
                const modal = new bootstrap.Modal(document.getElementById('confirmUserDeletion'));
                modal.show();
            @endif
        });
    </script>
@endpush
