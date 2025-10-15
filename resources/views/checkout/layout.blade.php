<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Checkout') - David's Wood Furniture</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/assets/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('frontend/assets/favicon.png') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('frontend/style.css') }}">
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Checkout Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-[#8b7355]">
                        David's Wood Furniture
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Need help?</span>
                    <a href="tel:+1234567890" class="text-[#8b7355] hover:text-[#6b5b47] font-medium">
                        Call us
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Progress Indicator -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center py-6">
                <div class="flex items-center space-x-8">
                    <!-- Step 1: Shipping -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $currentStep >= 1 ? 'bg-[#8b7355] text-white' : 'bg-gray-200 text-gray-500' }}">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $currentStep >= 1 ? 'text-[#8b7355]' : 'text-gray-500' }}">Shipping</span>
                    </div>
                    
                    <!-- Arrow -->
                    <div class="w-8 h-px bg-gray-300"></div>
                    
                    <!-- Step 2: Payment -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $currentStep >= 2 ? 'bg-[#8b7355] text-white' : 'bg-gray-200 text-gray-500' }}">
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $currentStep >= 2 ? 'text-[#8b7355]' : 'text-gray-500' }}">Payment</span>
                    </div>
                    
                    <!-- Arrow -->
                    <div class="w-8 h-px bg-gray-300"></div>
                    
                    <!-- Step 3: Review -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $currentStep >= 3 ? 'bg-[#8b7355] text-white' : 'bg-gray-200 text-gray-500' }}">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                        </div>
                        <span class="ml-2 text-sm font-medium {{ $currentStep >= 3 ? 'text-[#8b7355]' : 'text-gray-500' }}">Review</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                @yield('content')
            </div>
            
            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                    
                    <!-- Cart Items -->
                    <div class="space-y-3 mb-4">
                        @foreach($cartItems as $item)
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                @if($item->product && $item->product->images)
                                    @php
                                        $images = is_string($item->product->images) ? json_decode($item->product->images, true) : $item->product->images;
                                        $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                                    @endphp
                                    @if($firstImage)
                                        <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                                    @endif
                                @else
                                    <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product_name }}</p>
                                <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                ₱{{ number_format($item->total_price, 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pricing Breakdown -->
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-gray-900">
                                @if($shippingCost == 0)
                                    <span class="text-green-600">Free</span>
                                @else
                                    ₱{{ number_format($shippingCost, 2) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">VAT (12%)</span>
                            <span class="text-gray-900">₱{{ number_format($taxAmount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold border-t pt-2">
                            <span class="text-gray-900">Total</span>
                            <span class="text-[#8b7355]">₱{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                    
                    @if($shippingCost == 0)
                    <div class="mt-4 p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i data-lucide="truck" class="w-5 h-5 text-green-600 mr-2"></i>
                            <span class="text-sm text-green-800 font-medium">Free shipping on orders over ₱5,000</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="{{ asset('frontend/js/app.js') }}"></script>
    <script src="{{ asset('frontend/js/checkout.js') }}"></script>
    
    @stack('scripts')
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
    
    <!-- PSGC Cloud API Integration for Address Selection -->
    <script>
        // Philippine Address API Integration (PSGC Cloud v2)
        const PSGC_API = 'https://psgc.cloud/api/v2';
        let regionsData = [];
        let currentRegionCode = '';
        let currentProvinceCode = '';
        let billingRegionsData = [];
        let billingCurrentRegionCode = '';
        let billingCurrentProvinceCode = '';
        
        // Load all regions
        async function loadRegions() {
            try {
                const response = await fetch(`${PSGC_API}/regions`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                
                const data = await response.json();
                console.log('API Response:', data);
                console.log('API Response Type:', typeof data);
                console.log('Is Array?', Array.isArray(data));
                if (typeof data === 'object') {
                    console.log('Object keys:', Object.keys(data));
                }
                
                // Extract array from response
                if (Array.isArray(data)) {
                    regionsData = data;
                } else if (data.data && Array.isArray(data.data)) {
                    regionsData = data.data;
                } else if (typeof data === 'object') {
                    // Try to find the array in the object
                    const possibleArrays = Object.values(data).filter(v => Array.isArray(v));
                    if (possibleArrays.length > 0) {
                        regionsData = possibleArrays[0];
                    } else {
                        throw new Error('No array found in API response');
                    }
                } else {
                    throw new Error('API response is not in expected format');
                }
                
                if (!Array.isArray(regionsData)) {
                    throw new Error('API response is not in expected format');
                }
                
                const regionSelect = document.getElementById('region');
                if (regionSelect) {
                    regionSelect.innerHTML = '<option value="">Select Region</option>';
                    regionsData.forEach(region => {
                        const option = document.createElement('option');
                        option.value = region.name;
                        option.setAttribute('data-code', region.code);
                        option.textContent = region.name;
                        regionSelect.appendChild(option);
                    });
                }
                
                console.log('✅ Regions loaded:', regionsData.length);
            } catch (error) {
                console.error('❌ Error loading regions:', error);
            }
        }
        
        // Load provinces for selected region using v2 nested endpoint
        async function loadProvinces(regionCodeOrName) {
            try {
                currentRegionCode = regionCodeOrName;
                const url = `${PSGC_API}/regions/${encodeURIComponent(regionCodeOrName)}/provinces`;
                
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                const provinces = data.data || data;
                
                // Special case: Some regions (like NCR) have no provinces, go directly to cities
                if (!Array.isArray(provinces) || provinces.length === 0) {
                    const provinceSelect = document.getElementById('province');
                    if (provinceSelect) {
                        provinceSelect.innerHTML = '<option value="">No provinces (loading cities...)</option>';
                        provinceSelect.disabled = true;
                    }
                    
                    // Load cities directly for this region
                    await loadCitiesDirectly(regionCodeOrName);
                    return;
                }
                
                const provinceSelect = document.getElementById('province');
                if (provinceSelect) {
                    provinceSelect.innerHTML = '<option value="">Select Province</option>';
                    provinceSelect.disabled = false;
                    
                    if (Array.isArray(provinces)) {
                        provinces.forEach(province => {
                            const option = document.createElement('option');
                            option.value = province.name;
                            option.setAttribute('data-code', province.code);
                            option.textContent = province.name;
                            provinceSelect.appendChild(option);
                        });
                    }
                }
                
                console.log('✅ Provinces loaded:', provinces.length);
            } catch (error) {
                console.error('❌ Error loading provinces:', error);
            }
        }
        
        // Load cities/municipalities directly for a region (for regions without provinces like NCR)
        async function loadCitiesDirectly(regionCodeOrName) {
            try {
                const url = `${PSGC_API}/regions/${encodeURIComponent(regionCodeOrName)}/cities-municipalities`;
                
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                const cities = data.data || data;
                
                const citySelect = document.getElementById('city');
                if (citySelect) {
                    citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                    citySelect.disabled = false;
                    
                    if (Array.isArray(cities)) {
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name;
                            option.setAttribute('data-code', city.code);
                            option.textContent = `${city.name} (${city.type})`;
                            citySelect.appendChild(option);
                        });
                    }
                }
                
                console.log('✅ Cities/Municipalities loaded:', cities.length);
            } catch (error) {
                console.error('❌ Error loading cities:', error);
            }
        }
        
        // Load cities/municipalities for selected province using v2 nested endpoint
        async function loadCities(provinceCodeOrName) {
            try {
                currentProvinceCode = provinceCodeOrName;
                const url = `${PSGC_API}/regions/${encodeURIComponent(currentRegionCode)}/provinces/${encodeURIComponent(provinceCodeOrName)}/cities-municipalities`;
                
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                const cities = data.data || data;
                
                const citySelect = document.getElementById('city');
                if (citySelect) {
                    citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                    citySelect.disabled = false;
                    
                    if (Array.isArray(cities)) {
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name;
                            option.setAttribute('data-code', city.code);
                            option.textContent = `${city.name} (${city.type})`;
                            citySelect.appendChild(option);
                        });
                    }
                }
                
                console.log('✅ Cities/Municipalities loaded:', cities.length);
            } catch (error) {
                console.error('❌ Error loading cities:', error);
            }
        }
        
        // Load barangays for selected city using v2 endpoint
        async function loadBarangays(cityCodeOrName) {
            try {
                const url = `${PSGC_API}/cities-municipalities/${encodeURIComponent(cityCodeOrName)}/barangays`;
                
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                const barangays = data.data || data;
                
                const barangaySelect = document.getElementById('barangay');
                if (barangaySelect) {
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    barangaySelect.disabled = false;
                    
                    if (Array.isArray(barangays)) {
                        barangays.forEach(barangay => {
                            const option = document.createElement('option');
                            option.value = barangay.name;
                            option.textContent = barangay.name;
                            barangaySelect.appendChild(option);
                        });
                    }
                }
                
                console.log('✅ Barangays loaded:', barangays.length);
            } catch (error) {
                console.error('❌ Error loading barangays:', error);
            }
        }
        
        // Billing Address PSGC Functions
        async function loadBillingRegions() {
            try {
                const response = await fetch(`${PSGC_API}/regions`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                
                const data = await response.json();
                console.log('Billing API Response:', data);
                
                // Extract array from response
                if (Array.isArray(data)) {
                    billingRegionsData = data;
                } else if (data.data && Array.isArray(data.data)) {
                    billingRegionsData = data.data;
                } else if (typeof data === 'object') {
                    // Try to find the array in the object
                    const possibleArrays = Object.values(data).filter(v => Array.isArray(v));
                    if (possibleArrays.length > 0) {
                        billingRegionsData = possibleArrays[0];
                    } else {
                        throw new Error('No array found in billing API response');
                    }
                } else {
                    throw new Error('Billing API response is not in expected format');
                }
                
                if (!Array.isArray(billingRegionsData)) {
                    throw new Error('Billing API response is not in expected format');
                }
                
                const regionSelect = document.getElementById('billing-region');
                if (regionSelect) {
                    regionSelect.innerHTML = '<option value="">Select Region</option>';
                    billingRegionsData.forEach(region => {
                        const option = document.createElement('option');
                        option.value = region.name;
                        option.setAttribute('data-code', region.code);
                        option.textContent = region.name;
                        regionSelect.appendChild(option);
                    });
                }
                
                console.log('✅ Billing Regions loaded:', billingRegionsData.length);
            } catch (error) {
                console.error('❌ Error loading billing regions:', error);
            }
        }
        
        async function loadBillingProvinces(regionCodeOrName) {
            try {
                billingCurrentRegionCode = regionCodeOrName;
                const url = `${PSGC_API}/regions/${encodeURIComponent(regionCodeOrName)}/provinces`;
                
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                const provinces = data.data || data;
                
                // Special case: Some regions (like NCR) have no provinces, go directly to cities
                if (!Array.isArray(provinces) || provinces.length === 0) {
                    const provinceSelect = document.getElementById('billing-province');
                    if (provinceSelect) {
                        provinceSelect.innerHTML = '<option value="">No provinces (loading cities...)</option>';
                        provinceSelect.disabled = true;
                    }
                    
                    // Load cities directly for this region
                    await loadBillingCitiesDirectly(regionCodeOrName);
                    return;
                }
                
                const provinceSelect = document.getElementById('billing-province');
                if (provinceSelect) {
                    provinceSelect.innerHTML = '<option value="">Select Province</option>';
                    provinceSelect.disabled = false;
                    
                    if (Array.isArray(provinces)) {
                        provinces.forEach(province => {
                            const option = document.createElement('option');
                            option.value = province.name;
                            option.setAttribute('data-code', province.code);
                            option.textContent = province.name;
                            provinceSelect.appendChild(option);
                        });
                    }
                }
                
                console.log('✅ Billing Provinces loaded:', provinces.length);
            } catch (error) {
                console.error('❌ Error loading billing provinces:', error);
            }
        }
        
        async function loadBillingCitiesDirectly(regionCodeOrName) {
            try {
                const url = `${PSGC_API}/regions/${encodeURIComponent(regionCodeOrName)}/cities-municipalities`;
                
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                const cities = data.data || data;
                
                const citySelect = document.getElementById('billing-city');
                if (citySelect) {
                    citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                    citySelect.disabled = false;
                    
                    if (Array.isArray(cities)) {
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name;
                            option.setAttribute('data-code', city.code);
                            option.textContent = `${city.name} (${city.type})`;
                            citySelect.appendChild(option);
                        });
                    }
                }
                
                console.log('✅ Billing Cities/Municipalities loaded:', cities.length);
            } catch (error) {
                console.error('❌ Error loading billing cities:', error);
            }
        }
        
        async function loadBillingCities(provinceCodeOrName) {
            try {
                billingCurrentProvinceCode = provinceCodeOrName;
                const url = `${PSGC_API}/regions/${encodeURIComponent(billingCurrentRegionCode)}/provinces/${encodeURIComponent(provinceCodeOrName)}/cities-municipalities`;
                
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                const cities = data.data || data;
                
                const citySelect = document.getElementById('billing-city');
                if (citySelect) {
                    citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                    citySelect.disabled = false;
                    
                    if (Array.isArray(cities)) {
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name;
                            option.setAttribute('data-code', city.code);
                            option.textContent = `${city.name} (${city.type})`;
                            citySelect.appendChild(option);
                        });
                    }
                }
                
                console.log('✅ Billing Cities/Municipalities loaded:', cities.length);
            } catch (error) {
                console.error('❌ Error loading billing cities:', error);
            }
        }
        
        async function loadBillingBarangays(cityCodeOrName) {
            try {
                const url = `${PSGC_API}/cities-municipalities/${encodeURIComponent(cityCodeOrName)}/barangays`;
                
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                const barangays = data.data || data;
                
                const barangaySelect = document.getElementById('billing-barangay');
                if (barangaySelect) {
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    barangaySelect.disabled = false;
                    
                    if (Array.isArray(barangays)) {
                        barangays.forEach(barangay => {
                            const option = document.createElement('option');
                            option.value = barangay.name;
                            option.textContent = barangay.name;
                            barangaySelect.appendChild(option);
                        });
                    }
                }
                
                console.log('✅ Billing Barangays loaded:', barangays.length);
            } catch (error) {
                console.error('❌ Error loading billing barangays:', error);
            }
        }
        
        // Set up cascading dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            // Load regions on page load
            loadRegions();
            
            // Load billing regions on page load
            loadBillingRegions();
            
            // Region change handler
            const regionSelect = document.getElementById('region');
            if (regionSelect) {
                regionSelect.addEventListener('change', function() {
                    const selectedRegion = this.value;
                    if (selectedRegion) {
                        loadProvinces(selectedRegion);
                        // Reset dependent dropdowns
                        const citySelect = document.getElementById('city');
                        const barangaySelect = document.getElementById('barangay');
                        if (citySelect) {
                            citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                            citySelect.disabled = true;
                        }
                        if (barangaySelect) {
                            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                            barangaySelect.disabled = true;
                        }
                    }
                });
            }
            
            // Province change handler
            const provinceSelect = document.getElementById('province');
            if (provinceSelect) {
                provinceSelect.addEventListener('change', function() {
                    const selectedProvince = this.value;
                    if (selectedProvince) {
                        loadCities(selectedProvince);
                        // Reset dependent dropdowns
                        const barangaySelect = document.getElementById('barangay');
                        if (barangaySelect) {
                            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                            barangaySelect.disabled = true;
                        }
                    }
                });
            }
            
            // City change handler
            const citySelect = document.getElementById('city');
            if (citySelect) {
                citySelect.addEventListener('change', function() {
                    const selectedCity = this.value;
                    if (selectedCity) {
                        loadBarangays(selectedCity);
                    }
                });
            }
            
            // Set up billing address cascading dropdowns
            const billingRegionSelect = document.getElementById('billing-region');
            const billingProvinceSelect = document.getElementById('billing-province');
            const billingCitySelect = document.getElementById('billing-city');
            const billingBarangaySelect = document.getElementById('billing-barangay');
            
            if (billingRegionSelect) {
                billingRegionSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const regionName = selectedOption.value;
                    const regionCode = selectedOption.getAttribute('data-code');
                    
                    console.log('🌏 Billing Region selected:', { name: regionName, code: regionCode });
                    
                    // Reset province code (important for regions without provinces)
                    billingCurrentProvinceCode = '';
                    
                    // Reset province, city and barangay
                    if (billingProvinceSelect) {
                        billingProvinceSelect.innerHTML = '<option value="">Select Province</option>';
                        billingProvinceSelect.disabled = true;
                    }
                    if (billingCitySelect) {
                        billingCitySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                        billingCitySelect.disabled = true;
                    }
                    if (billingBarangaySelect) {
                        billingBarangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                        billingBarangaySelect.disabled = true;
                    }
                    
                    if (regionName) {
                        loadBillingProvinces(regionName);
                    }
                });
            }
            
            if (billingProvinceSelect) {
                billingProvinceSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const provinceName = selectedOption.value;
                    const provinceCode = selectedOption.getAttribute('data-code');
                    
                    console.log('🏛️ Billing Province selected:', { name: provinceName, code: provinceCode });
                    
                    // Reset city and barangay
                    if (billingCitySelect) {
                        billingCitySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                        billingCitySelect.disabled = true;
                    }
                    if (billingBarangaySelect) {
                        billingBarangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                        billingBarangaySelect.disabled = true;
                    }
                    
                    if (provinceName) {
                        loadBillingCities(provinceName);
                    }
                });
            }
            
            if (billingCitySelect) {
                billingCitySelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const cityName = selectedOption.value;
                    const cityCode = selectedOption.getAttribute('data-code');
                    
                    console.log('🏙️ Billing City selected:', { name: cityName, code: cityCode });
                    
                    // Reset barangay
                    if (billingBarangaySelect) {
                        billingBarangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                        billingBarangaySelect.disabled = true;
                    }
                    
                    if (cityName) {
                        loadBillingBarangays(cityName);
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
