@extends('admin.layouts.app')

@section('title', 'Create Customer')

@section('content')
<!-- Header Section -->

<div class="max-w-6xl mx-auto mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                <i data-lucide="user-plus" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Create New Customer</h1>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Add a new customer to your system</p>
            </div>
        </div>
        <a href="{{ admin_route('users.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Customers
        </a>
    </div>
</div>

<div class="max-w-6xl mx-auto">
    <form action="{{ admin_route('users.store') }}" method="POST" class="space-y-6" id="create-customer-form">
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
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Enter the essential details about the customer</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="first_name" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="{{ old('first_name') }}"
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
                            value="{{ old('last_name') }}"
                            placeholder="Enter last name"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('last_name') border-red-300 @enderror"
                            required
                        />
                        @error('last_name')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter email address"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('email') border-red-300 @enderror"
                            required
                        />
                        @error('email')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Phone Number
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="Enter phone number"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('phone') border-red-300 @enderror"
                        />
                        @error('phone')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-green-50 to-blue-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                        <i data-lucide="map-pin" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Address Information</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Enter the customer's address details</p>
            </div>
            <div class="p-8 space-y-6">
            
                <!-- Street Address | Barangay -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="street" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Street Address
                        </label>
                        <input
                            type="text"
                            id="street"
                            name="street"
                            value="{{ old('street') }}"
                            placeholder="Enter street address"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('street') border-red-300 @enderror"
                        />
                        @error('street')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="barangay" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Barangay
                        </label>
                        <select
                            id="barangay"
                            name="barangay"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('barangay') border-red-300 @enderror"
                        >
                            <option value="">Select Barangay</option>
                        </select>
                        @error('barangay')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- City | Zip Code -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="city" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            City
                        </label>
                        <select
                            id="city"
                            name="city"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('city') border-red-300 @enderror"
                        >
                            <option value="">Select City</option>
                        </select>
                        @error('city')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="zip_code" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Zip Code
                        </label>
                        <input
                            type="text"
                            id="zip_code"
                            name="zip_code"
                            value="{{ old('zip_code') }}"
                            placeholder="Enter zip code"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('zip_code') border-red-300 @enderror"
                        />
                        @error('zip_code')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Province | Region -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label for="province" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Province
                        </label>
                        <select
                            id="province"
                            name="province"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('province') border-red-300 @enderror"
                        >
                            <option value="">Select Province</option>
                        </select>
                        @error('province')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="region" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                            Region
                        </label>
                        <select
                            id="region"
                            name="region"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white @error('region') border-red-300 @enderror"
                        >
                            <option value="">Select Region</option>
                        </select>
                        @error('region')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>


        <!-- Newsletter Preferences -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                        <i data-lucide="mail" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Newsletter Preferences</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Configure customer communication preferences</p>
            </div>
            <div class="p-8 space-y-4">
                <div class="flex items-center justify-between p-4 rounded-lg border border-stroke dark:border-strokedark">
                <div>
                        <h5 class="font-medium text-black dark:text-white">Newsletter Subscription</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Subscribe to general newsletter updates</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="newsletter_subscribed"
                            value="1"
                            {{ old('newsletter_subscribed') ? 'checked' : '' }}
                            class="sr-only peer"
                        />
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-slate-400 peer-checked:bg-primary border border-slate-300 dark:border-slate-400 shadow-sm"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 rounded-lg border border-stroke dark:border-strokedark">
                <div>
                        <h5 class="font-medium text-black dark:text-white">Product Updates</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Receive notifications about new products and features</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="newsletter_product_updates"
                            value="1"
                            {{ old('newsletter_product_updates', '1') ? 'checked' : '' }}
                            class="sr-only peer"
                        />
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-slate-400 peer-checked:bg-primary border border-slate-300 dark:border-slate-400 shadow-sm"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 rounded-lg border border-stroke dark:border-strokedark">
                <div>
                        <h5 class="font-medium text-black dark:text-white">Special Offers</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Get exclusive deals and promotional offers</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="newsletter_special_offers"
                            value="1"
                            {{ old('newsletter_special_offers') ? 'checked' : '' }}
                            class="sr-only peer"
                        />
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-slate-400 peer-checked:bg-primary border border-slate-300 dark:border-slate-400 shadow-sm"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 rounded-lg border border-stroke dark:border-strokedark">
                <div>
                        <h5 class="font-medium text-black dark:text-white">Marketing Emails</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Receive marketing communications and promotional content</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="marketing_emails"
                            value="1"
                            {{ old('marketing_emails') ? 'checked' : '' }}
                            class="sr-only peer"
                        />
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-slate-400 peer-checked:bg-primary border border-slate-300 dark:border-slate-400 shadow-sm"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Welcome Email -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl">
                        <i data-lucide="send" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Welcome Email</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Configure welcome email settings</p>
            </div>
            <div class="p-8">
                <div class="flex items-center justify-between p-4 rounded-lg border border-stroke dark:border-strokedark">
                    <div>
                        <h5 class="font-medium text-black dark:text-white">Send Welcome Email</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">The customer will receive a welcome email with their login credentials and account information.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            id="send_welcome_email"
                            name="send_welcome_email"
                            value="1"
                            {{ old('send_welcome_email') ? 'checked' : '' }}
                            class="sr-only peer"
                        />
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/20 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-slate-400 peer-checked:bg-primary border border-slate-300 dark:border-slate-400 shadow-sm"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4 pt-6">
            <a href="{{ admin_route('users.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 border border-stone-200 bg-white text-sm font-medium text-stone-700 rounded-xl transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="x" class="w-4 h-4"></i>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Create Customer
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// PSGC API Integration for Philippine Addresses
const PSGC_API_BASE = 'https://psgc.cloud/api';

// Add CSS for disabled province field
const style = document.createElement('style');
style.textContent = `
    #province:disabled {
        background-color: #f9fafb !important;
        color: #6b7280 !important;
        cursor: not-allowed;
    }
`;
document.head.appendChild(style);

// Load regions on page load
document.addEventListener('DOMContentLoaded', function() {
    loadRegions();
});

async function loadRegions() {
    try {
        const response = await fetch(`${PSGC_API_BASE}/regions`);
        const regions = await response.json();
        
        const regionSelect = document.getElementById('region');
        regions.forEach(region => {
            const option = document.createElement('option');
            option.value = region.name;
            option.textContent = region.name;
            regionSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading regions:', error);
    }
}

// Load provinces when region is selected
document.getElementById('region').addEventListener('change', async function() {
    const regionName = this.value;
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');
    
    // Clear existing options
    provinceSelect.innerHTML = '<option value="">Select Province</option>';
    citySelect.innerHTML = '<option value="">Select City</option>';
    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
    
    if (!regionName) return;
    
    // Special handling for NCR - it doesn't have provinces
    if (regionName.toLowerCase().includes('ncr') || regionName.toLowerCase().includes('national capital region')) {
        // For NCR, load cities directly
        try {
            const response = await fetch(`${PSGC_API_BASE}/regions/${encodeURIComponent(regionName)}/cities`);
            const cities = await response.json();
            
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.name;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
            
            // Disable province field for NCR
            provinceSelect.disabled = true;
            provinceSelect.innerHTML = '<option value="">N/A (NCR)</option>';
            
            // Clear barangay field
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        } catch (error) {
            console.error('Error loading NCR cities:', error);
        }
    } else {
        // For other regions, load provinces normally
        try {
            const response = await fetch(`${PSGC_API_BASE}/regions/${encodeURIComponent(regionName)}/provinces`);
            const provinces = await response.json();
            
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.name;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
            
            // Enable province field for non-NCR regions
            provinceSelect.disabled = false;
        } catch (error) {
            console.error('Error loading provinces:', error);
        }
    }
});

// Load cities when province is selected
document.getElementById('province').addEventListener('change', async function() {
    const provinceName = this.value;
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');
    
    // Skip if province field is disabled (for NCR)
    if (this.disabled) return;
    
    // Clear existing options
    citySelect.innerHTML = '<option value="">Select City</option>';
    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
    
    if (!provinceName) return;
    
    try {
        const response = await fetch(`${PSGC_API_BASE}/provinces/${encodeURIComponent(provinceName)}/cities`);
        const cities = await response.json();
        
        cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city.name;
            option.textContent = city.name;
            citySelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading cities:', error);
    }
});

// Load barangays when city is selected
document.getElementById('city').addEventListener('change', async function() {
    const cityName = this.value;
    const barangaySelect = document.getElementById('barangay');
    const zipCodeInput = document.getElementById('zip_code');
    
    // Clear existing options
    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
    
    if (!cityName) {
        zipCodeInput.value = '';
        return;
    }
    
    try {
        // Load barangays for the selected city
        const barangayResponse = await fetch(`${PSGC_API_BASE}/cities/${encodeURIComponent(cityName)}/barangays`);
        const barangays = await barangayResponse.json();
        
        barangays.forEach(barangay => {
            const option = document.createElement('option');
            option.value = barangay.name;
            option.textContent = barangay.name;
            barangaySelect.appendChild(option);
        });
        
        // Also get city data for zip code
        const cityResponse = await fetch(`${PSGC_API_BASE}/cities/${encodeURIComponent(cityName)}`);
        const cityData = await cityResponse.json();
        
        if (cityData.zipCode) {
            zipCodeInput.value = cityData.zipCode;
        }
    } catch (error) {
        console.error('Error loading city data:', error);
    }
});

// Form validation (simplified since no password fields)
document.getElementById('create-customer-form').addEventListener('submit', function(e) {
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const email = document.getElementById('email').value.trim();
    
    if (!firstName || !lastName || !email) {
        e.preventDefault();
        alert('Please fill in all required fields (First Name, Last Name, Email).');
        return false;
    }
    
    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return false;
    }
});
</script>
@endpush
@endsection
