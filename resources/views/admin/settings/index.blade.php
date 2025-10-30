@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">Settings</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Manage your application settings and configuration</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Sticky section nav -->
    <aside class="lg:col-span-3">
        <div class="sticky top-24 rounded-xl border border-stroke bg-white p-2 dark:border-strokedark dark:bg-boxdark">
            <nav class="flex flex-col">
                <a href="#general" class="settings-link px-4 py-3 rounded-lg text-sm text-gray-700 hover:bg-primary/5 hover:text-primary dark:text-gray-300">General</a>
                <a href="#email" class="settings-link px-4 py-3 rounded-lg text-sm text-gray-700 hover:bg-primary/5 hover:text-primary dark:text-gray-300">Email</a>
                <a href="#payment" class="settings-link px-4 py-3 rounded-lg text-sm text-gray-700 hover:bg-primary/5 hover:text-primary dark:text-gray-300">Payment</a>
                <a href="#shipping" class="settings-link px-4 py-3 rounded-lg text-sm text-gray-700 hover:bg-primary/5 hover:text-primary dark:text-gray-300">Shipping</a>
            </nav>
        </div>
    </aside>

    <!-- Content -->
    <div class="lg:col-span-9 space-y-8">
        <!-- General -->
        <section id="general" class="rounded-xl border border-stroke bg-white p-6 dark:border-strokedark dark:bg-boxdark">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">General</h3>
            <form method="POST" action="{{ admin_route('settings.update-general') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site Name</label>
                    <input type="text" name="site_name" value="{{ old('site_name', "David's Wood Furniture") }}" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-form-input py-2 px-3" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site Email</label>
                    <input type="email" name="site_email" value="{{ old('site_email', 'info@davidswoodfurniture.com') }}" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-form-input py-2 px-3" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
                    <select name="currency" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-form-input py-2 px-3">
                        <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('currency', 'USD') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="GBP" {{ old('currency', 'USD') == 'GBP' ? 'selected' : '' }}>GBP</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Timezone</label>
                    <select name="timezone" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-form-input py-2 px-3">
                        <option value="UTC" {{ old('timezone', 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                        <option value="America/New_York" {{ old('timezone', 'UTC') == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                        <option value="Europe/London" {{ old('timezone', 'UTC') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site Description</label>
                    <textarea name="site_description" rows="3" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-form-input py-2 px-3">{{ old('site_description', 'Premium wood furniture for your home') }}</textarea>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90">Save General Settings</button>
                </div>
            </form>
        </section>

        <!-- Email -->
        <section id="email" class="rounded-xl border border-stroke bg-white p-6 dark:border-strokedark dark:bg-boxdark">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Email</h3>
            <form method="POST" action="{{ admin_route('settings.update-email') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Driver</label>
                    <select name="mail_driver" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-form-input py-2 px-3">
                        <option value="smtp" {{ old('mail_driver', 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="mailgun" {{ old('mail_driver', 'smtp') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                        <option value="ses" {{ old('mail_driver', 'smtp') == 'ses' ? 'selected' : '' }}>SES</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Host</label>
                    <input type="text" name="mail_host" value="{{ old('mail_host', 'smtp.gmail.com') }}" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-form-input py-2 px-3" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Port</label>
                    <input type="number" name="mail_port" value="{{ old('mail_port', '587') }}" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-form-input py-2 px-3" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Username</label>
                    <input type="text" name="mail_username" value="{{ old('mail_username') }}" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-form-input py-2 px-3" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mail Password</label>
                    <input type="password" name="mail_password" class="mt-1 w-full rounded-lg border border-stroke dark:border-strokedark bg-white dark:bg-form-input py-2 px-3" />
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="mail_encryption" name="mail_encryption" value="tls" {{ old('mail_encryption') ? 'checked' : '' }} class="rounded" />
                    <label for="mail_encryption" class="text-sm text-gray-700 dark:text-gray-300">Enable TLS Encryption</label>
                </div>
                <div class="md:col-span-2 flex justify-end gap-3">
                    <button type="button" onclick="testEmail()" class="px-4 py-2 rounded-lg border border-stroke dark:border-strokedark text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-graydark">Test Email</button>
                    <button class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90">Save Email Settings</button>
                </div>
            </form>
        </section>

        <!-- Payment -->
        <section id="payment" class="rounded-xl border border-stroke bg-white p-6 dark:border-strokedark dark:bg-boxdark">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Payment</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Manage gateways in <a href="{{ admin_route('payment-gateways.index') }}" class="text-primary hover:underline">Payment Gateways</a> and methods in <a href="{{ admin_route('settings.index') }}#payment" class="text-primary hover:underline">Payment Methods</a>.</p>
        </section>

        <!-- Shipping -->
        <section id="shipping" class="rounded-xl border border-stroke bg-white p-6 dark:border-strokedark dark:bg-boxdark">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Shipping</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Manage methods in <a href="{{ admin_route('shipping-methods.index') }}" class="text-primary hover:underline">Shipping Methods</a>.</p>
        </section>
    </div>
</div>

@push('scripts')
<script>
function testEmail(){ alert('Email test functionality will be implemented here.'); }
// Highlight active section on scroll
document.addEventListener('scroll', () => {
  const sections = ['general','email','payment','shipping'];
  let active = 'general';
  sections.forEach(id => {
    const el = document.getElementById(id);
    if(el && el.getBoundingClientRect().top < 120) active = id;
  });
  document.querySelectorAll('.settings-link').forEach(a => {
    a.classList.toggle('bg-primary/10', a.getAttribute('href') === '#' + active);
    a.classList.toggle('text-primary', a.getAttribute('href') === '#' + active);
  });
});
</script>
@endpush
@endsection