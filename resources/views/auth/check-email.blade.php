@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-orange-100">
                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Check Your Email
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                We've sent you a secure magic link to complete your login
            </p>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        What's next?
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Check your email inbox (and spam folder)</li>
                            <li>Click the magic link in the email</li>
                            <li>You'll be automatically logged in</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <p class="text-sm text-gray-500">
                Didn't receive the email? 
                <a href="{{ route('user.login.form') }}" class="font-medium text-orange-600 hover:text-orange-500">
                    Try logging in again
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

