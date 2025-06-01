{{-- resources/views/profile/partials/update-password-form.blade.php --}}
<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <div class="grid grid-cols-1 gap-6">
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" 
                   class="form-input @error('current_password', 'updatePassword') border-red-500 @enderror" 
                   autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input id="update_password_password" name="password" type="password" 
                   class="form-input @error('password', 'updatePassword') border-red-500 @enderror" 
                   autocomplete="new-password">
            @error('password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                   class="form-input @error('password_confirmation', 'updatePassword') border-red-500 @enderror" 
                   autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit" class="btn-primary">Update Password</button>

        @if (session('status') === 'password-updated')
            <p class="text-sm text-green-600" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                Password updated successfully!
            </p>
        @endif
    </div>
</form>