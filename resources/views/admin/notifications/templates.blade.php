@extends('admin.layouts.app')

@section('title', 'Notification Templates')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Notification Templates
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ route('admin.dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ route('admin.notifications.index') }}">Notifications /</a>
            </li>
            <li class="font-medium text-primary">Templates</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="space-y-6">
    <!-- Send Test Notification -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Send Test Notification</h4>
        
        <form action="{{ route('admin.notifications.test') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div>
                    <label for="type" class="mb-2.5 block text-black dark:text-white">Notification Type</label>
                    <select name="type" id="type" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" required>
                        <option value="">Select Type</option>
                        <option value="order_created">Order Created</option>
                        <option value="order_status_changed">Order Status Changed</option>
                        <option value="low_stock">Low Stock Alert</option>
                        <option value="new_review">New Review</option>
                        <option value="welcome">Welcome Email</option>
                        <option value="password_reset">Password Reset</option>
                    </select>
                </div>
                <div>
                    <label for="email" class="mb-2.5 block text-black dark:text-white">Test Email</label>
                    <input type="email" name="email" id="email" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" placeholder="admin@example.com" required>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-6 py-3 text-white hover:bg-primary/90 transition-colors duration-200">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Send Test
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Template Management -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        @foreach($templates as $key => $template)
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-black dark:text-white">{{ $template['name'] }}</h4>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ ucfirst($key) }}
                </span>
            </div>
            
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $template['description'] }}</p>
            
            <form action="{{ route('admin.notifications.update-template', $key) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="subject_{{ $key }}" class="mb-2.5 block text-black dark:text-white">Subject</label>
                    <input type="text" name="subject" id="subject_{{ $key }}" value="{{ $template['subject'] }}" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" required>
                </div>
                
                <div>
                    <label for="body_{{ $key }}" class="mb-2.5 block text-black dark:text-white">Body</label>
                    <textarea name="body" id="body_{{ $key }}" rows="6" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" placeholder="Enter email body content...">{{ $template['body'] ?? '' }}</textarea>
                </div>
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" class="mr-2 rounded border-stroke dark:border-strokedark" checked>
                        <span class="text-black dark:text-white">Active</span>
                    </label>
                    <button type="submit" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-4 py-2 text-white hover:bg-primary/90 transition-colors duration-200">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Template
                    </button>
                </div>
            </form>
        </div>
        @endforeach
    </div>

    <!-- Send Custom Notification -->
    <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
        <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Send Custom Notification</h4>
        
        <form action="{{ route('admin.notifications.send') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div>
                    <label for="custom_type" class="mb-2.5 block text-black dark:text-white">Type</label>
                    <select name="type" id="custom_type" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" required>
                        <option value="">Select Type</option>
                        <option value="order_created">Order Created</option>
                        <option value="order_status_changed">Order Status Changed</option>
                        <option value="low_stock">Low Stock Alert</option>
                        <option value="new_review">New Review</option>
                        <option value="welcome">Welcome Email</option>
                        <option value="password_reset">Password Reset</option>
                    </select>
                </div>
                <div>
                    <label for="recipients" class="mb-2.5 block text-black dark:text-white">Recipients (comma-separated emails)</label>
                    <input type="text" name="recipients[]" id="recipients" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" placeholder="user1@example.com, user2@example.com" required>
                </div>
            </div>
            
            <div>
                <label for="custom_subject" class="mb-2.5 block text-black dark:text-white">Subject</label>
                <input type="text" name="subject" id="custom_subject" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" required>
            </div>
            
            <div>
                <label for="custom_body" class="mb-2.5 block text-black dark:text-white">Body</label>
                <textarea name="body" id="custom_body" rows="6" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" required></textarea>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-6 py-3 text-white hover:bg-primary/90 transition-colors duration-200">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Send Notification
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle recipients input - convert comma-separated to array
    const recipientsInput = document.getElementById('recipients');
    const form = recipientsInput.closest('form');
    
    form.addEventListener('submit', function(e) {
        const recipients = recipientsInput.value.split(',').map(email => email.trim()).filter(email => email);
        
        // Clear the original input
        recipientsInput.remove();
        
        // Add hidden inputs for each recipient
        recipients.forEach(email => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'recipients[]';
            input.value = email;
            form.appendChild(input);
        });
    });
});
</script>
@endsection
