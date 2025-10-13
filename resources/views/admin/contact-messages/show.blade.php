@extends('admin.layouts.app')

@section('title', 'Contact Message Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.contact-messages.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Messages
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $contactMessage->name }}</h1>
                    <p class="text-gray-600 mt-1">{{ $contactMessage->email }}</p>
                    @if($contactMessage->user)
                        <p class="text-sm text-gray-500 mt-1">
                            Registered User: 
                            <a href="{{ route('admin.users.show', $contactMessage->user) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $contactMessage->user->name }}
                            </a>
                        </p>
                    @else
                        <p class="text-sm text-gray-500 mt-1">Guest User</p>
                    @endif
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                        {{ $contactMessage->status === 'new' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $contactMessage->status === 'read' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $contactMessage->status === 'responded' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $contactMessage->status === 'archived' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                        {{ ucfirst($contactMessage->status) }}
                    </span>
                    <p class="text-sm text-gray-500 mt-2">
                        Received: {{ $contactMessage->created_at->format('M d, Y h:i A') }}
                    </p>
                    @if($contactMessage->read_at)
                        <p class="text-xs text-gray-400 mt-1">
                            Read: {{ $contactMessage->read_at->format('M d, Y h:i A') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Message Content -->
        <div class="px-6 py-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Message</h3>
            <div class="bg-gray-50 rounded-lg p-4 whitespace-pre-wrap">
                {{ $contactMessage->message }}
            </div>
        </div>

        <!-- Admin Notes -->
        <div class="px-6 py-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Admin Notes</h3>
            <form action="{{ route('admin.contact-messages.update', $contactMessage) }}" method="POST">
                @csrf
                @method('PATCH')
                <textarea name="admin_notes" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Add internal notes about this message...">{{ old('admin_notes', $contactMessage->admin_notes) }}</textarea>
                
                <div class="mt-4 flex items-center gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" 
                                id="status" 
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="new" {{ $contactMessage->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="read" {{ $contactMessage->status === 'read' ? 'selected' : '' }}>Read</option>
                            <option value="responded" {{ $contactMessage->status === 'responded' ? 'selected' : '' }}>Responded</option>
                            <option value="archived" {{ $contactMessage->status === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    
                    <div class="flex gap-3 mt-6">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Update Message
                        </button>
                        
                        <a href="mailto:{{ $contactMessage->email }}?subject=Re: Your message to David's Wood" 
                           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Reply via Email
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" 
                  method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this message? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                    Delete Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

