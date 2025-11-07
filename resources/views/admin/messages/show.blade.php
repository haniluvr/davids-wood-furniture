@extends('admin.layouts.app')

@section('title', 'Message Details')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-stone-900 dark:text-white">
            Message Details
        </h1>
        <p class="mt-2 text-stone-600 dark:text-gray-400">
            View and manage customer message details.
        </p>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ admin_route('messages.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Messages
        </a>
        @if($message->status !== 'responded')
        <button onclick="markAsResponded()" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
            <i data-lucide="check" class="w-4 h-4"></i>
            Mark as Responded
        </button>
        @endif
    </div>
</div>
<!-- Breadcrumb End -->

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Message Content -->
    <div class="lg:col-span-2">
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                        <span class="text-white font-semibold text-lg">{{ substr($message->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-stone-900 dark:text-white">{{ $message->name }}</h3>
                        <p class="text-sm text-stone-600 dark:text-gray-400">{{ $message->email }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                        @if($message->status === 'new') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                        @elseif($message->status === 'read') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                        @elseif($message->status === 'responded') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                        @else bg-stone-100 text-stone-800 dark:bg-stone-700 dark:text-stone-200
                        @endif">
                        {{ ucfirst($message->status) }}
                    </span>
                    <p class="text-xs text-stone-500 dark:text-gray-400 mt-1">{{ $message->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            <div class="mb-6">
                <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-3">Message</h4>
                <div class="rounded-xl bg-stone-50 p-4 dark:bg-stone-800/50">
                    <p class="text-stone-700 dark:text-gray-300 whitespace-pre-wrap">{{ $message->message }}</p>
                </div>
            </div>

            @if($message->internal_notes)
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-3">Internal Notes</h4>
                <div class="rounded-xl bg-amber-50 p-4 dark:bg-amber-900/20">
                    <p class="text-stone-700 dark:text-gray-300 whitespace-pre-wrap">{{ $message->internal_notes }}</p>
                </div>
            </div>
            @endif

            @if($message->tags && count($message->tags) > 0)
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-3">Tags</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($message->tags as $tag)
                    <span class="inline-flex items-center rounded-full bg-stone-100 px-3 py-1 text-sm font-medium text-stone-800 dark:bg-stone-700 dark:text-stone-200">
                        {{ $tag }}
                        <button onclick="removeTag('{{ $tag }}')" class="ml-2 text-stone-400 hover:text-stone-600 dark:hover:text-stone-300">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </button>
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Add Tag Form -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-3">Add Tag</h4>
                <form id="add-tag-form" class="flex gap-2">
                    <input type="text" id="new-tag" placeholder="Enter tag name" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-primary/90">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Add
                    </button>
                </form>
            </div>

            <!-- Reply Section -->
            <div class="mb-6" id="reply">
                <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-3">Reply to Customer</h4>
                <form id="reply-form" class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-1">To</label>
                        <input type="email" id="reply-to" value="{{ $message->email }}" readonly class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2 text-sm text-stone-600 dark:border-strokedark dark:bg-stone-800 dark:text-stone-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-1">Subject</label>
                        <input type="text" id="reply-subject" value="Re: {{ Str::limit($message->message, 50) }}" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 dark:text-stone-300 mb-1">Message</label>
                        <textarea id="reply-message" rows="6" placeholder="Type your reply here..." class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 hover:bg-primary/90">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Send Reply
                        </button>
                        <button type="button" onclick="clearReplyForm()" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                            Clear
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Message Actions & Info -->
    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-4">Customer Information</h4>
            <div class="space-y-3">
                <div>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Name</p>
                    <p class="text-stone-900 dark:text-white">{{ $message->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Email</p>
                    <p class="text-stone-900 dark:text-white">{{ $message->email }}</p>
                </div>
                @if($message->user)
                <div>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Customer Since</p>
                    <p class="text-stone-900 dark:text-white">{{ $message->user->created_at->format('M d, Y') }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Message Date</p>
                    <p class="text-stone-900 dark:text-white">{{ $message->created_at->format('M d, Y h:i A') }}</p>
                </div>
                @if($message->read_at)
                <div>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Read At</p>
                    <p class="text-stone-900 dark:text-white">{{ $message->read_at->format('M d, Y h:i A') }}</p>
                </div>
                @endif
                @if($message->responded_at)
                <div>
                    <p class="text-sm font-medium text-stone-600 dark:text-gray-400">Responded At</p>
                    <p class="text-stone-900 dark:text-white">{{ $message->responded_at->format('M d, Y h:i A') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-4">Quick Actions</h4>
            <div class="space-y-3">
                @if($message->status !== 'responded')
                <button onclick="markAsResponded()" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-all duration-200">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    Mark as Responded
                </button>
                @endif
                
                <button onclick="updateInternalNotes()" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Update Notes
                </button>
                
                <button onclick="deleteMessage()" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-700 transition-all duration-200 hover:bg-red-100 hover:border-red-300 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    Delete Message
                </button>
            </div>
        </div>

        <!-- Message History -->
        @if($message->responded_at)
        <div class="rounded-2xl border border-stone-200/50 bg-white/80 backdrop-blur-sm p-6 shadow-lg shadow-stone-500/5 dark:border-strokedark/50 dark:bg-boxdark/80">
            <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-4">Response History</h4>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center dark:bg-emerald-900/30">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-stone-900 dark:text-white">Message Responded</p>
                        <p class="text-xs text-stone-500 dark:text-gray-400">{{ $message->responded_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                @if($message->respondedBy)
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center dark:bg-blue-900/30">
                        <i data-lucide="user" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-stone-900 dark:text-white">Responded by {{ $message->respondedBy->name }}</p>
                        <p class="text-xs text-stone-500 dark:text-gray-400">Admin</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Update Internal Notes Modal -->
<div id="notes-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-boxdark">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-white">Update Internal Notes</h3>
                <p class="text-sm text-stone-600 dark:text-gray-400">Add or update internal notes for this message</p>
            </div>
            <form id="update-notes-form">
                <div class="mb-4">
                    <textarea id="internal-notes" rows="4" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400" placeholder="Enter internal notes...">{{ $message->internal_notes }}</textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeNotesModal()" class="flex-1 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 rounded-xl bg-primary px-4 py-2 text-sm font-medium text-white transition-all duration-200 hover:bg-primary/90">
                        Update Notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll to reply section if hash is present
    if (window.location.hash === '#reply') {
        setTimeout(function() {
            const replySection = document.getElementById('reply');
            if (replySection) {
                replySection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                // Focus on the message textarea
                setTimeout(function() {
                    document.getElementById('reply-message').focus();
                }, 500);
            }
        }, 100);
    }

    // Add tag form
    document.getElementById('add-tag-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const tag = document.getElementById('new-tag').value.trim();
        if (tag) {
            addTag(tag);
            document.getElementById('new-tag').value = '';
        }
    });

    // Update notes form
    document.getElementById('update-notes-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const notes = document.getElementById('internal-notes').value;
        updateInternalNotes(notes);
    });

    // Reply form
    document.getElementById('reply-form').addEventListener('submit', function(e) {
        e.preventDefault();
        sendReply();
    });
});

function markAsResponded() {
    if (confirm('Are you sure you want to mark this message as responded?')) {
        fetch(`/admin/messages/{{ $message->id }}/respond`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                response_notes: ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload notifications to remove read ones from dropdown
                if (typeof loadNotifications === 'function') {
                    loadNotifications();
                }
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the request.');
        });
    }
}

function addTag(tag) {
    fetch(`/admin/messages/{{ $message->id }}/tags`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            tags: [tag]
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the request.');
    });
}

function removeTag(tag) {
    if (confirm('Are you sure you want to remove this tag?')) {
        fetch(`/admin/messages/{{ $message->id }}/remove-tag`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                tag: tag
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the request.');
        });
    }
}

function updateInternalNotes() {
    document.getElementById('notes-modal').classList.remove('hidden');
}

function closeNotesModal() {
    document.getElementById('notes-modal').classList.add('hidden');
}

function updateInternalNotes(notes) {
    fetch(`/admin/messages/{{ $message->id }}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            internal_notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeNotesModal();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the request.');
    });
}

function deleteMessage() {
    if (confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
        fetch(`/admin/messages/{{ $message->id }}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ admin_route("messages.index") }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the request.');
        });
    }
}

function sendReply() {
    // Always use the message sender's email, not the form value
    const to = '{{ $message->email }}';
    const subject = document.getElementById('reply-subject').value;
    const message = document.getElementById('reply-message').value;

    if (!subject.trim() || !message.trim()) {
        showNotification('Please fill in both subject and message fields.', 'error');
        return;
    }

    // Disable submit button
    const submitBtn = document.querySelector('#reply-form button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Sending...';

    // Hide any previous error messages
    hideNotification();

    fetch('{{ admin_route("messages.reply", $message) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            subject: subject,
            message: message
        })
    })
    .then(async response => {
        // Clone response for potential multiple reads
        const responseClone = response.clone();
        let data;
        const contentType = response.headers.get('content-type');
        
        try {
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                // If not JSON, get text
                const text = await response.text();
                throw new Error(text || `Server returned an invalid response (Status: ${response.status})`);
            }
        } catch (parseError) {
            // If JSON parsing fails, try to get text from cloned response
            try {
                const text = await responseClone.text();
                throw new Error(`Server response error: ${text || 'Unknown error'}`);
            } catch (textError) {
                throw new Error(`Failed to parse server response: ${parseError.message || 'Unknown error'}`);
            }
        }
        
        // Check if response indicates an error
        if (!response.ok) {
            const errorMsg = data?.message || data?.error || `Server error: ${response.status} ${response.statusText}`;
            throw new Error(errorMsg);
        }
        
        // Check if success flag is false
        if (data && data.success === false) {
            throw new Error(data.message || 'Failed to send reply');
        }
        
        return data;
    })
    .then(data => {
        if (data.success) {
            showNotification('Reply sent successfully!', 'success');
            clearReplyForm();
            // Reload notifications to remove read ones from dropdown
            if (typeof loadNotifications === 'function') {
                loadNotifications();
            }
            // Optionally mark as responded (without confirmation)
            // Admin can manually mark if needed
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        } else {
            throw new Error(data.message || 'Failed to send reply');
        }
    })
    .catch(error => {
        console.error('Error sending reply:', error);
        
        // Extract error message
        let errorMessage = 'An error occurred while sending the reply.';
        
        if (error.message) {
            errorMessage = error.message;
        } else if (error.toString && error.toString() !== '[object Object]') {
            errorMessage = error.toString();
        }
        
        // Show error notification prominently
        showNotification(errorMessage, 'error');
        
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        // Also log to console for debugging
        console.error('Full error details:', error);
    });
}

function showNotification(message, type) {
    // Remove existing notification if any
    hideNotification();
    
    const notification = document.createElement('div');
    notification.id = 'reply-notification';
    notification.className = `fixed top-4 right-4 z-[9999] px-6 py-4 rounded-xl shadow-2xl max-w-md animate-in slide-in-from-top-5 ${
        type === 'success' 
            ? 'bg-green-50 border-2 border-green-300 text-green-900 dark:bg-green-900/30 dark:border-green-600 dark:text-green-100' 
            : 'bg-red-50 border-2 border-red-300 text-red-900 dark:bg-red-900/30 dark:border-red-600 dark:text-red-100'
    }`;
    
    // Escape HTML to prevent XSS
    const escapedMessage = message.replace(/</g, '&lt;').replace(/>/g, '&gt;');
    
    notification.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 mt-0.5">
                <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5"></i>
            </div>
            <p class="flex-1 text-sm font-medium leading-relaxed">${escapedMessage}</p>
            <button onclick="hideNotification()" class="flex-shrink-0 text-current opacity-70 hover:opacity-100 transition-opacity" title="Close">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Reinitialize Lucide icons
    if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
    }
    
    // Scroll notification into view if needed
    notification.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Auto-hide after 8 seconds for success messages, 15 seconds for errors (so user can read them)
    const timeout = type === 'success' ? 8000 : 15000;
    setTimeout(() => {
        hideNotification();
    }, timeout);
}

function hideNotification() {
    const notification = document.getElementById('reply-notification');
    if (notification) {
        notification.remove();
    }
}

function clearReplyForm() {
    document.getElementById('reply-subject').value = 'Re: {{ Str::limit($message->message, 50) }}';
    document.getElementById('reply-message').value = '';
}
</script>
@endpush
