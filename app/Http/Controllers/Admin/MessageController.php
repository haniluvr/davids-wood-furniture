<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of messages with status-based filtering.
     */
    public function index(Request $request)
    {
        $query = ContactMessage::with(['user', 'assignedTo', 'respondedBy'])->latest();

        // Status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('message', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', '%'.$search.'%')
                            ->orWhere('last_name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $messages = $query->paginate(20)->appends($request->query());

        // Get statistics
        $stats = [
            'new_messages' => ContactMessage::where('status', 'new')->count(),
            'read_messages' => ContactMessage::where('status', 'read')->count(),
            'responded_messages' => ContactMessage::where('status', 'responded')->count(),
            'total_messages' => ContactMessage::count(),
        ];

        return view('admin.messages.index', compact('messages', 'stats'));
    }

    /**
     * Display the specified message.
     */
    public function show(ContactMessage $message)
    {
        $message->load(['user', 'assignedTo', 'respondedBy']);

        // Mark as read when admin views it
        if ($message->status === 'new') {
            $message->update(['status' => 'read', 'read_at' => now()]);
        }

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Update message status and add internal notes.
     */
    public function update(Request $request, ContactMessage $message)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:new,read,responded,archived',
            'internal_notes' => 'sometimes|nullable|string|max:2000',
            'tags' => 'sometimes|nullable|array',
            'assigned_to' => 'sometimes|nullable|exists:admins,id',
        ]);

        // Handle status changes
        if (isset($validated['status'])) {
            if ($validated['status'] === 'responded' && $message->status !== 'responded') {
                $validated['responded_at'] = now();
                $validated['responded_by'] = auth('admin')->id();
            }
        }

        $message->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Message updated successfully',
        ]);
    }

    /**
     * Mark message as responded.
     */
    public function markAsResponded(Request $request, ContactMessage $message)
    {
        $request->validate([
            'response_notes' => 'nullable|string|max:2000',
        ]);

        $message->markAsResponded(auth('admin')->id());

        if ($request->response_notes) {
            $message->update(['internal_notes' => $request->response_notes]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Message marked as responded',
        ]);
    }

    /**
     * Assign message to admin.
     */
    public function assign(Request $request, ContactMessage $message)
    {
        $request->validate([
            'assigned_to' => 'required|exists:admins,id',
        ]);

        $message->update(['assigned_to' => $request->assigned_to]);

        return response()->json([
            'success' => true,
            'message' => 'Message assigned successfully',
        ]);
    }

    /**
     * Add tags to message.
     */
    public function addTags(Request $request, ContactMessage $message)
    {
        $request->validate([
            'tags' => 'required|array',
            'tags.*' => 'string|max:50',
        ]);

        $currentTags = $message->tags ?? [];
        $newTags = array_unique(array_merge($currentTags, $request->tags));

        $message->update(['tags' => $newTags]);

        return response()->json([
            'success' => true,
            'message' => 'Tags added successfully',
        ]);
    }

    /**
     * Remove tag from message.
     */
    public function removeTag(Request $request, ContactMessage $message)
    {
        $request->validate([
            'tag' => 'required|string',
        ]);

        $currentTags = $message->tags ?? [];
        $newTags = array_values(array_filter($currentTags, function ($tag) use ($request) {
            return $tag !== $request->tag;
        }));

        $message->update(['tags' => $newTags]);

        return response()->json([
            'success' => true,
            'message' => 'Tag removed successfully',
        ]);
    }

    /**
     * Bulk update message statuses.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:contact_messages,id',
            'status' => 'required|in:new,read,responded,archived',
        ]);

        $messageIds = $request->message_ids;
        $newStatus = $request->status;

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'responded') {
            $updateData['responded_at'] = now();
            $updateData['responded_by'] = auth('admin')->id();
        }

        ContactMessage::whereIn('id', $messageIds)->update($updateData);

        return response()->json([
            'success' => true,
            'message' => count($messageIds).' messages updated successfully',
        ]);
    }

    /**
     * Delete a message.
     */
    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully',
        ]);
    }

    /**
     * Get messages by status for tabs.
     */
    public function getByStatus($status)
    {
        $query = ContactMessage::with(['user', 'assignedTo', 'respondedBy'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $messages = $query->paginate(20);

        return response()->json([
            'success' => true,
            'messages' => $messages->items(),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
        ]);
    }
}
