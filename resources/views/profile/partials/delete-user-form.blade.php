{{-- resources/views/profile/partials/delete-user-form.blade.php --}}
<div class="space-y-6">
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Danger Zone</h3>
                <p class="mt-1 text-sm text-red-700">
                    Once your account is deleted, all of its resources and data will be permanently deleted. 
                    Before deleting your account, please download any data or information that you wish to retain.
                </p>
            </div>
        </div>
    </div>

    <button type="button" 
            class="btn-danger" 
            x-data="" 
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        Delete Account
    </button>

    <!-- Delete Account Modal -->
    <div x-data="{ show: false }" 
         x-on:open-modal.window="$event.detail == 'confirm-user-deletion' ? show = true : null" 
         x-on:close.stop="show = false" 
         x-on:keydown.escape.window="show = false" 
         x-show="show" 
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto">
        
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Delete Account
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete your account? Once your account is deleted, 
                                        all of its resources and data will be permanently deleted. Please enter your 
                                        password to confirm you would like to permanently delete your account.
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <label for="password" class="sr-only">Password</label>
                                    <input id="password" name="password" type="password" 
                                           class="form-input w-full" 
                                           placeholder="Password" required>
                                    @error('password', 'userDeletion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="btn-danger ml-3">
                            Delete Account
                        </button>
                        <button type="button" 
                                class="btn-secondary" 
                                x-on:click="show = false">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-user-deletion' }));
        });
    </script>
@endif