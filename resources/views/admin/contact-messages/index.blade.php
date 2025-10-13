@extends('admin.layouts.app')

@section('title', 'Contact Messages')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Contact Messages</h1>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">
                {{ $messages->total() }} total messages
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter tabs -->
    <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <li class="mr-2">
                <a href="{{ route('admin.contact-messages.index', ['status' => 'all']) }}" 
                   class="inline-block p-4 {{ request('status', 'all') == 'all' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300' }}">
                    All Messages
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('admin.contact-messages.index', ['status' => 'new']) }}" 
                   class="inline-block p-4 {{ request('status') == 'new' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300' }}">
                    New ({{ \App\Models\ContactMessage::where('status', 'new')->count() }})
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" 
                   class="inline-block p-4 {{ request('status') == 'read' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300' }}">
                    Read
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('admin.contact-messages.index', ['status' => 'responded']) }}" 
                   class="inline-block p-4 {{ request('status') == 'responded' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-600 hover:border-gray-300' }}">
                    Responded
                </a>
            </li>
        </ul>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Message Preview
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($messages as $message)
                    <tr class="{{ $message->status === 'new' ? 'bg-blue-50' : '' }} hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $message->status === 'new' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $message->status === 'read' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $message->status === 'responded' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $message->status === 'archived' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ ucfirst($message->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $message->name }}</div>
                            @if($message->user)
                                <div class="text-xs text-gray-500">User #{{ $message->user_id }}</div>
                            @else
                                <div class="text-xs text-gray-500">Guest</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $message->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-md truncate">
                                {{ Str::limit($message->message, 100) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $message->created_at->format('M d, Y') }}
                            <div class="text-xs text-gray-400">{{ $message->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.contact-messages.show', $message) }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                View
                            </a>
                            <form action="{{ route('admin.contact-messages.destroy', $message) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this message?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No contact messages found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $messages->links() }}
    </div>
</div>
@endsection

