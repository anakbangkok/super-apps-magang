@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container py-5">
    <h2>Edit Profile</h2>

    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab">
                Update Profile
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" role="tab">
                Change Password
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete" role="tab">
                Delete Account
            </button>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <div class="tab-pane fade show active" id="profile" role="tabpanel">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ auth()->user()->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ auth()->user()->email }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>

        <div class="tab-pane fade" id="password" role="tabpanel">
            @include('profile.partials.update-password-form')
        </div>

        <div class="tab-pane fade" id="delete" role="tabpanel">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
