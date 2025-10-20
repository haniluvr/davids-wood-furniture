<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Store a new contact message
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            // Create the contact message
            $contactMessage = ContactMessage::create([
                'user_id' => Auth::id(), // Will be null if guest
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => $validated['message'],
                'status' => 'new',
            ]);

            // Log the submission
            Log::info('Contact form submitted', [
                'contact_message_id' => $contactMessage->id,
                'email' => $validated['email'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message! We\'ll respond within 1-2 business days.',
            ]);

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'email' => $validated['email'] ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'There was an error sending your message. Please try again later.',
            ], 500);
        }
    }

    /**
     * Display all contact messages (admin only)
     */
    public function index(Request $request)
    {
        // For now, allow all admin users to view contact messages
        // TODO: Add proper authorization policy

        $query = ContactMessage::with('user')->latest();

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(20)->appends($request->query());

        return view('admin.contact-messages.index', compact('messages'));
    }

    /**
     * Display a specific contact message (admin only)
     */
    public function show(ContactMessage $contactMessage)
    {
        // For now, allow all admin users to view contact messages
        // TODO: Add proper authorization policy

        // Mark as read when admin views it
        $contactMessage->markAsRead();

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    /**
     * Update contact message status or admin notes (admin only)
     */
    public function update(Request $request, ContactMessage $contactMessage)
    {
        // For now, allow all admin users to update contact messages
        // TODO: Add proper authorization policy

        $validated = $request->validate([
            'status' => 'sometimes|in:new,read,responded,archived',
            'admin_notes' => 'sometimes|nullable|string',
        ]);

        $contactMessage->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Contact message updated successfully.');
    }

    /**
     * Delete a contact message (admin only)
     */
    public function destroy(ContactMessage $contactMessage)
    {
        // For now, allow all admin users to delete contact messages
        // TODO: Add proper authorization policy

        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Contact message deleted successfully.');
    }
}
