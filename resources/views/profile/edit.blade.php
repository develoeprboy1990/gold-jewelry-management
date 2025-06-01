{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
        <p class="mt-2 text-gray-600">Manage your account information and security settings</p>
    </div>

    <!-- Profile Information -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Profile Information</h2>
            <p class="text-sm text-gray-600">Update your account's profile information and email address.</p>
        </div>
        <div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- Update Password -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Update Password</h2>
            <p class="text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
        </div>
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- Delete Account -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Delete Account</h2>
            <p class="text-sm text-gray-600">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
        </div>
        <div class="card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection