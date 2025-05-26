<section>
    <header class="mb-6">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6" enctype="multipart/form-data"
        id="profile-form">
        @csrf
        @method('patch')

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Profile Picture Column -->
            <div class="w-full md:w-1/3">
                <div class="flex flex-col items-center">
                    <div class="mb-4">
                        @if ($user->profile_picture)
                            <img id="current_image"
                                src="{{ asset('storage/profile_pictures/' . $user->profile_picture) }}"
                                alt="{{ $user->name }}"
                                class="h-32 w-32 object-cover rounded-full border-2 border-gray-200 shadow">
                        @else
                            <div
                                class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 border-2 border-gray-200 shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div id="image_preview_container" class="mb-4 hidden">
                        <p class="text-sm text-gray-600 text-center mb-2">{{ __('Preview:') }}</p>
                        <img id="image_preview" src="#" alt="Preview"
                            class="h-32 w-32 object-cover rounded-full border-2 border-gray-300 shadow">
                    </div>

                    <div class="w-full">
                        <x-input-label for="profile_picture" :value="__('Update Profile Picture')" class="text-center mb-2" />
                        <input id="profile_picture" name="profile_picture" type="file" accept="image/*"
                            class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100" />
                        <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
                    </div>
                </div>
            </div>

            <!-- Personal Information Column -->
            <div class="w-full md:w-2/3 space-y-6">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                        :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    <p class="text-xs text-gray-500 mt-1">
                        {{ __('Changing your email address will require password verification for security.') }}</p>
                </div>

                <div class="pt-4">
                    <x-primary-button x-data="{ originalEmail: '{{ $user->email }}' }"
                        x-on:click.prevent="
                            const currentEmail = document.getElementById('email').value;
                            if (currentEmail !== originalEmail) {
                                $dispatch('open-modal', 'confirm-email-change');
                            } else {
                                document.getElementById('profile-form').submit();
                            }
                        ">{{ __('Save') }}</x-primary-button>

                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="inline-block ml-3 text-sm text-gray-600">{{ __('Saved.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <!-- Password Confirmation Modal for Email Change -->
    <x-modal name="confirm-email-change" :show="$errors->emailChange->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.update') }}" class="p-6">
            @csrf
            @method('patch')

            <!-- Hidden inputs to carry over the form data -->
            <input type="hidden" id="modal_name" name="name">
            <input type="hidden" id="modal_email" name="email">
            <input id="confirm_email_change" name="confirm_email_change" type="hidden" value="1">
            <!-- Include profile_picture if it's been uploaded -->
            <input type="hidden" id="modal_has_profile_picture" name="modal_has_profile_picture" value="0">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Confirm Email Change') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('For security, please enter your password to confirm you want to change your email address.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="confirm_password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input id="confirm_password" name="password" type="password" class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}" required />

                <x-input-error :messages="$errors->emailChange->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Confirm Email Change') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profilePictureInput = document.getElementById('profile_picture');
        const imagePreviewContainer = document.getElementById('image_preview_container');
        const imagePreview = document.getElementById('image_preview');
        const currentImage = document.getElementById('current_image');

        // Add event listener for modal opening
        document.addEventListener('open-modal', function(event) {
            if (event.detail === 'confirm-email-change') {
                // Copy form values to the modal form
                document.getElementById('modal_name').value = document.getElementById('name').value;
                document.getElementById('modal_email').value = document.getElementById('email').value;

                // Check if a new profile picture was selected
                if (profilePictureInput.files && profilePictureInput.files[0]) {
                    document.getElementById('modal_has_profile_picture').value = '1';
                }
            }
        });

        profilePictureInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.setAttribute('src', e.target.result);
                    imagePreviewContainer.classList.remove('hidden');

                    // Hide current image when preview is shown
                    if (currentImage) {
                        currentImage.parentElement.classList.add('hidden');
                    }
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreviewContainer.classList.add('hidden');

                // Show current image again if preview is hidden
                if (currentImage) {
                    currentImage.parentElement.classList.remove('hidden');
                }
            }
        });
    });
</script>
