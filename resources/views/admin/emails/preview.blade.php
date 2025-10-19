@extends('admin.layouts.app')

@section('title', 'Email Previews')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            Email Previews
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li>Email Previews</li>
            </ol>
        </nav>
    </div>

    <!-- Email Types Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($emailTypes as $type => $name)
        <div class="rounded-lg border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-black dark:text-white">{{ $name }}</h3>
                    <p class="text-sm text-body-color dark:text-body-color-dark">
                        Preview {{ strtolower($name) }} email template
                    </p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.emails.preview', $type) }}" 
                       target="_blank"
                       class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-center font-medium text-white hover:bg-opacity-90">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Instructions -->
    <div class="mt-8 rounded-lg border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">How to Use Email Previews</h3>
        <div class="space-y-3 text-sm text-body-color dark:text-body-color-dark">
            <p><strong>1. Preview Templates:</strong> Click the "Preview" button to see how each email template looks with sample data.</p>
            <p><strong>2. Test Responsiveness:</strong> Open previews in different browser sizes to test mobile responsiveness.</p>
            <p><strong>3. Check Content:</strong> Verify that all dynamic content (order details, product info, etc.) displays correctly.</p>
            <p><strong>4. Brand Consistency:</strong> Ensure all emails maintain consistent branding and styling.</p>
        </div>
    </div>

    <!-- Email Configuration -->
    <div class="mt-6 rounded-lg border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Email Configuration</h3>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <h4 class="mb-2 font-medium text-black dark:text-white">SMTP Settings</h4>
                <p class="text-sm text-body-color dark:text-body-color-dark">
                    Configure your SMTP settings in the <a href="{{ route('admin.settings.email') }}" class="text-primary hover:underline">Email Settings</a> page.
                </p>
            </div>
            <div>
                <h4 class="mb-2 font-medium text-black dark:text-white">Test Email</h4>
                <p class="text-sm text-body-color dark:text-body-color-dark">
                    Send test emails to verify your SMTP configuration is working properly.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

