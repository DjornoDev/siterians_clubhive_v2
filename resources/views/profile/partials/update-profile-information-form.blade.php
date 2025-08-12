<section>
    <header class="mb-6">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information, contact details, and emergency contacts.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6" enctype="multipart/form-data"
        id="profile-form">
        @csrf
        @method('patch')

        <div class="space-y-8">
            <!-- Profile Picture and Basic Info Section -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-md font-semibold text-gray-900 mb-4">Basic Information</h3>
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

                    <!-- Basic Info Column -->
                    <div class="w-full md:w-2/3 space-y-4">
                        <div>
                            <x-input-label for="name" :value="__('Full Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email Address')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            <p class="text-xs text-gray-500 mt-1">
                                {{ __('Changing your email address will require password verification for security.') }}
                            </p>
                        </div>

                        <div>
                            <x-input-label for="sex" :value="__('Gender')" />
                            <select id="sex" name="sex"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('sex', $user->sex) === 'Male' ? 'selected' : '' }}>Male
                                </option>
                                <option value="Female" {{ old('sex', $user->sex) === 'Female' ? 'selected' : '' }}>
                                    Female</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('sex')" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="text-md font-semibold text-gray-900 mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="contact_no" :value="__('Contact Number')" />
                        <x-text-input id="contact_no" name="contact_no" type="tel" class="mt-1 block w-full"
                            :value="old('contact_no', $user->contact_no)" placeholder="09XXXXXXXXX" />
                        <x-input-error class="mt-2" :messages="$errors->get('contact_no')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="address" :value="__('Address')" />
                        <textarea id="address" name="address" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Complete address">{{ old('address', $user->address) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>
                </div>
            </div>

            <!-- Emergency Contacts Section -->
            <div class="bg-red-50 rounded-lg p-6">
                <h3 class="text-md font-semibold text-gray-900 mb-4">Emergency Contacts (Parents/Guardian)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mother's Information -->
                    <div class="bg-white rounded-lg p-4 border border-pink-200">
                        <h4 class="font-medium text-pink-800 mb-3">Mother's Information</h4>
                        <div class="space-y-3">
                            <div>
                                <x-input-label for="mother_name" :value="__('Mother\'s Full Name')" />
                                <x-text-input id="mother_name" name="mother_name" type="text"
                                    class="mt-1 block w-full" :value="old('mother_name', $user->mother_name)" />
                                <x-input-error class="mt-2" :messages="$errors->get('mother_name')" />
                            </div>
                            <div>
                                <x-input-label for="mother_contact_no" :value="__('Mother\'s Contact Number')" />
                                <x-text-input id="mother_contact_no" name="mother_contact_no" type="tel"
                                    class="mt-1 block w-full" :value="old('mother_contact_no', $user->mother_contact_no)" placeholder="09XXXXXXXXX" />
                                <x-input-error class="mt-2" :messages="$errors->get('mother_contact_no')" />
                            </div>
                        </div>
                    </div>

                    <!-- Father's Information -->
                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                        <h4 class="font-medium text-blue-800 mb-3">Father's Information</h4>
                        <div class="space-y-3">
                            <div>
                                <x-input-label for="father_name" :value="__('Father\'s Full Name')" />
                                <x-text-input id="father_name" name="father_name" type="text"
                                    class="mt-1 block w-full" :value="old('father_name', $user->father_name)" />
                                <x-input-error class="mt-2" :messages="$errors->get('father_name')" />
                            </div>
                            <div>
                                <x-input-label for="father_contact_no" :value="__('Father\'s Contact Number')" />
                                <x-text-input id="father_contact_no" name="father_contact_no" type="tel"
                                    class="mt-1 block w-full" :value="old('father_contact_no', $user->father_contact_no)" placeholder="09XXXXXXXXX" />
                                <x-input-error class="mt-2" :messages="$errors->get('father_contact_no')" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-4">
                <x-primary-button x-data="{ originalEmail: '{{ $user->email }}' }"
                    x-on:click.prevent="
                        const currentEmail = document.getElementById('email').value;
                        if (currentEmail !== originalEmail) {
                            $dispatch('open-modal', 'confirm-email-change');
                        } else {
                            document.getElementById('profile-form').submit();
                        }
                    ">{{ __('Save Profile') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="inline-block ml-3 text-sm text-gray-600">{{ __('Profile updated successfully!') }}</p>
                @endif
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
            <input type="hidden" id="modal_sex" name="sex">
            <input type="hidden" id="modal_contact_no" name="contact_no">
            <input type="hidden" id="modal_address" name="address">
            <input type="hidden" id="modal_mother_name" name="mother_name">
            <input type="hidden" id="modal_mother_contact_no" name="mother_contact_no">
            <input type="hidden" id="modal_father_name" name="father_name">
            <input type="hidden" id="modal_father_contact_no" name="father_contact_no">
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
                // Copy all form values to the modal form
                document.getElementById('modal_name').value = document.getElementById('name').value;
                document.getElementById('modal_email').value = document.getElementById('email').value;
                document.getElementById('modal_sex').value = document.getElementById('sex').value;
                document.getElementById('modal_contact_no').value = document.getElementById(
                    'contact_no').value;
                document.getElementById('modal_address').value = document.getElementById('address')
                    .value;
                document.getElementById('modal_mother_name').value = document.getElementById(
                    'mother_name').value;
                document.getElementById('modal_mother_contact_no').value = document.getElementById(
                    'mother_contact_no').value;
                document.getElementById('modal_father_name').value = document.getElementById(
                    'father_name').value;
                document.getElementById('modal_father_contact_no').value = document.getElementById(
                    'father_contact_no').value;

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
