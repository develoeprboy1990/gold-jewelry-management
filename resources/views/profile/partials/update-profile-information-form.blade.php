{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input id="name" name="name" type="text" 
                   class="form-input @error('name') border-red-500 @enderror" 
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" 
                   class="form-input @error('email') border-red-500 @enderror" 
                   value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        Your email address is unverified.
                        <button form="send-verification" class="underline text-yellow-700 hover:text-yellow-900">
                            Click here to re-send the verification email.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-4">
        <button type="submit" class="btn-primary">Save Changes</button>

        @if (session('status') === 'profile-updated')
            <p class="text-sm text-green-600" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                Profile updated successfully!
            </p>
        @endif
    </div>
</form>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>