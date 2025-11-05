@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                    <i data-lucide="user" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">My Profile</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Manage your personal information</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ admin_route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Personal Information -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                        <i data-lucide="user" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Personal Information</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update your personal details</p>
            </div>
            <div class="p-8 space-y-6">
                <!-- Avatar Section -->
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <img src="{{ $admin->avatar_url }}" alt="{{ $admin->full_name }}" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-stone-200 dark:border-strokedark">
                            <label for="avatar" class="absolute bottom-0 right-0 flex items-center justify-center w-8 h-8 bg-primary rounded-full cursor-pointer hover:bg-primary/90 transition-colors shadow-lg">
                                <i data-lucide="camera" class="w-4 h-4 text-white"></i>
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                            </label>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-2">
                            Profile Picture
                        </label>
                        <p class="text-xs text-stone-500 dark:text-gray-400">Click the camera icon to upload a new profile picture. Maximum file size: 2MB</p>
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <div id="avatar-preview" class="mt-2 hidden">
                            <p class="text-sm text-green-600 dark:text-green-400">New avatar selected</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="first_name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="{{ old('first_name', $admin->first_name) }}"
                            placeholder="Enter first name"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('first_name') border-red-300 @enderror"
                            required
                        />
                        @error('first_name')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="last_name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="{{ old('last_name', $admin->last_name) }}"
                            placeholder="Enter last name"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('last_name') border-red-300 @enderror"
                            required
                        />
                        @error('last_name')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Phone Number
                        </label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $admin->phone) }}"
                            placeholder="Enter phone number"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('phone') border-red-300 @enderror"
                        />
                        @error('phone')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="personal_email" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Personal Email
                        </label>
                        <input
                            type="email"
                            id="personal_email"
                            name="personal_email"
                            value="{{ old('personal_email', $admin->personal_email) }}"
                            placeholder="Enter personal email address"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('personal_email') border-red-300 @enderror"
                        />
                        @error('personal_email')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-stone-500 dark:text-gray-400">This email is used for password resets and 2FA codes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Information (Read-Only) -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl">
                        <i data-lucide="briefcase" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Work Information</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">These fields can only be changed by a super admin</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="role" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Role
                        </label>
                        <input
                            type="text"
                            id="role"
                            value="{{ ucfirst(str_replace('_', ' ', $admin->role)) }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="department" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Department
                        </label>
                        <input
                            type="text"
                            id="department"
                            value="{{ $admin->department ?? 'N/A' }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="position" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Position
                        </label>
                        <input
                            type="text"
                            id="position"
                            value="{{ $admin->position ?? 'N/A' }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Login Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            value="{{ $admin->email }}"
                            class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                            disabled
                            readonly
                        />
                        <p class="text-xs text-stone-500 dark:text-gray-400">To change your login email, visit <a href="{{ admin_route('profile.settings') }}" class="text-primary hover:underline">Account Settings</a></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ admin_route('dashboard') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 border border-stone-200 bg-white text-sm font-medium text-stone-700 rounded-xl transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Changes
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.querySelector('img[alt="{{ $admin->full_name }}"]');
            img.src = e.target.result;
            document.getElementById('avatar-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection

