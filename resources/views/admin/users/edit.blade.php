@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Edit User
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ route('admin.dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ route('admin.users.index') }}">Users /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ route('admin.users.show', $user) }}">{{ $user->first_name }} {{ $user->last_name }} /</a>
            </li>
            <li class="font-medium text-primary">Edit</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- User Info Card -->
    <div class="lg:col-span-1">
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex flex-col items-center text-center">
                <!-- Avatar -->
                <div class="relative mb-4">
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-2xl">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </span>
                    </div>
                </div>

                <!-- User Details -->
                <h3 class="text-xl font-bold text-black dark:text-white mb-2">
                    {{ $user->first_name }} {{ $user->last_name }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $user->email }}</p>

                <!-- Status Badge -->
                <div class="mb-6">
                    @if($user->email_verified_at)
                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                            <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                            Unverified
                        </span>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="w-full space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Total Orders</span>
                        <span class="font-semibold text-black dark:text-white">{{ $user->orders->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Total Spent</span>
                        <span class="font-semibold text-black dark:text-white">${{ number_format($user->orders->where('status', '!=', 'cancelled')->sum('total_amount'), 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Member Since</span>
                        <span class="font-semibold text-black dark:text-white">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="lg:col-span-2">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Personal Information</h4>
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="mb-2.5 block text-black dark:text-white">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="{{ old('first_name', $user->first_name) }}"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('first_name') border-red-500 @enderror"
                            required
                        />
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="mb-2.5 block text-black dark:text-white">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="{{ old('last_name', $user->last_name) }}"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('last_name') border-red-500 @enderror"
                            required
                        />
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-2.5 block text-black dark:text-white">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('email') border-red-500 @enderror"
                            required
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="mb-2.5 block text-black dark:text-white">
                            Phone Number
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('phone') border-red-500 @enderror"
                        />
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="mb-2.5 block text-black dark:text-white">
                            Date of Birth
                        </label>
                        <input
                            type="date"
                            id="date_of_birth"
                            name="date_of_birth"
                            value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('date_of_birth') border-red-500 @enderror"
                        />
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="mb-2.5 block text-black dark:text-white">
                            Gender
                        </label>
                        <select
                            id="gender"
                            name="gender"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('gender') border-red-500 @enderror"
                        >
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Account Settings</h4>
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Status -->
                    <div>
                        <label for="status" class="mb-2.5 block text-black dark:text-white">
                            Account Status
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('status') border-red-500 @enderror"
                        >
                            <option value="active" {{ old('status', $user->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $user->status ?? 'active') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Verified -->
                    <div>
                        <label class="mb-2.5 block text-black dark:text-white">
                            Email Verification
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    name="email_verified"
                                    value="1"
                                    {{ old('email_verified', $user->email_verified_at ? '1' : '0') === '1' ? 'checked' : '' }}
                                    class="mr-2"
                                />
                                <span class="text-black dark:text-white">Verified</span>
                            </label>
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    name="email_verified"
                                    value="0"
                                    {{ old('email_verified', $user->email_verified_at ? '1' : '0') === '0' ? 'checked' : '' }}
                                    class="mr-2"
                                />
                                <span class="text-black dark:text-white">Unverified</span>
                            </label>
                        </div>
                        @error('email_verified')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Password Reset -->
            <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Password Reset</h4>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            id="reset_password"
                            name="reset_password"
                            value="1"
                            class="mr-2"
                        />
                        <span class="text-black dark:text-white">Reset user's password</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">If checked, a password reset email will be sent to the user.</p>
                </div>

                <div id="password_fields" class="hidden">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="mb-2.5 block text-black dark:text-white">
                                New Password
                            </label>
                            <input
                                type="password"
                                id="new_password"
                                name="new_password"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('new_password') border-red-500 @enderror"
                            />
                            @error('new_password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="new_password_confirmation" class="mb-2.5 block text-black dark:text-white">
                                Confirm Password
                            </label>
                            <input
                                type="password"
                                id="new_password_confirmation"
                                name="new_password_confirmation"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('new_password_confirmation') border-red-500 @enderror"
                            />
                            @error('new_password_confirmation')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.users.show', $user) }}" class="flex items-center gap-2 rounded-lg border border-stroke bg-white px-6 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-strokedark dark:bg-boxdark dark:text-gray-300 dark:hover:bg-gray-800">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Cancel
                </a>
                <button type="submit" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-6 py-3 text-white hover:bg-primary/90 transition-colors duration-200">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resetPasswordCheckbox = document.getElementById('reset_password');
    const passwordFields = document.getElementById('password_fields');
    
    resetPasswordCheckbox.addEventListener('change', function() {
        if (this.checked) {
            passwordFields.classList.remove('hidden');
        } else {
            passwordFields.classList.add('hidden');
        }
    });
});
</script>
@endsection
