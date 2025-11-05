<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Models\Admin;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the user's profile page.
     */
    public function showProfile()
    {
        $admin = Auth::guard('admin')->user();

        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update the user's profile.
     */
    public function updateProfile(ProfileUpdateRequest $request)
    {
        $admin = Auth::guard('admin')->user();
        $oldValues = $admin->only(['first_name', 'last_name', 'phone', 'personal_email', 'avatar']);

        $updateData = [];

        // Update editable fields
        if ($request->has('first_name')) {
            $updateData['first_name'] = $request->first_name;
        }

        if ($request->has('last_name')) {
            $updateData['last_name'] = $request->last_name;
        }

        if ($request->has('phone')) {
            $updateData['phone'] = $request->phone;
        }

        if ($request->has('personal_email')) {
            $updateData['personal_email'] = $request->personal_email;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Get dynamic storage disk (local for development, S3 for production)
            $diskName = Storage::getDynamicDisk();
            $disk = Storage::disk($diskName);

            // Delete old avatar if exists
            if ($admin->avatar) {
                // Try to delete from current dynamic disk
                $disk->delete($admin->avatar);
                // Also try to delete from public disk as fallback (for backward compatibility)
                if ($diskName !== 'public') {
                    Storage::disk('public')->delete($admin->avatar);
                }
            }

            // Store new avatar using dynamic storage
            $path = $request->file('avatar')->store('avatars', $diskName);
            $updateData['avatar'] = $path;
        }

        // Update the admin
        $admin->update($updateData);

        // Log the update
        AuditLog::log('admin_user.profile_updated', $admin, $admin, $oldValues, $admin->only(['first_name', 'last_name', 'phone', 'personal_email', 'avatar']), "Admin user {$admin->first_name} {$admin->last_name} updated their profile");

        return redirect()->to(admin_route('profile.index'))
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the account settings page.
     */
    public function showSettings()
    {
        $admin = Auth::guard('admin')->user();

        return view('admin.profile.settings', compact('admin'));
    }

    /**
     * Update account settings (password, email).
     */
    public function updateSettings(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $oldValues = $admin->only(['email']);

        $updateData = [];
        $validationRules = [];

        // Handle password change
        if ($request->filled('current_password') || $request->filled('new_password')) {
            $validationRules['current_password'] = 'required';
            $validationRules['new_password'] = 'required|string|min:8|confirmed';
            $validationRules['new_password_confirmation'] = 'required';

            // Validate password change
            $request->validate($validationRules);

            // Verify current password
            if (! Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }

            $updateData['password'] = Hash::make($request->new_password);
        }

        // Handle email change
        if ($request->filled('new_email') && $request->new_email !== $admin->email) {
            $emailValidationRules = [
                'new_email' => 'required|email|max:255|unique:employees,email,'.$admin->id,
                'email_current_password' => 'required',
            ];

            $request->validate($emailValidationRules);

            // Verify current password for email change
            if (! Hash::check($request->email_current_password, $admin->password)) {
                return back()->withErrors(['email_current_password' => 'Current password is incorrect.'])->withInput();
            }

            $updateData['email'] = $request->new_email;
        }

        // Update if there are changes
        if (! empty($updateData)) {
            $admin->update($updateData);

            // Log the update
            $changedFields = array_keys($updateData);
            AuditLog::log('admin_user.settings_updated', $admin, $admin, $oldValues, $admin->only(['email']), "Admin user {$admin->first_name} {$admin->last_name} updated their account settings (".implode(', ', $changedFields).')');

            return redirect()->to(admin_route('profile.settings'))
                ->with('success', 'Account settings updated successfully.');
        }

        return back()->with('info', 'No changes were made.');
    }

    /**
     * Show the contacts list page.
     */
    public function showContacts(Request $request)
    {
        $query = Admin::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('personal_email', 'like', "%{$search}%");
            });
        }

        // Get all employees (not just active)
        $admins = $query->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('admin.profile.contacts', compact('admins'));
    }

    /**
     * Show a coworker's profile (read-only).
     */
    public function showContactProfile($username)
    {
        // Find admin by username (email prefix before @dwatelier.co)
        $email = $username.'@dwatelier.co';
        $admin = Admin::where('email', $email)->first();

        if (! $admin) {
            return redirect()->to(admin_route('profile.contacts'))
                ->with('error', 'Contact not found.');
        }

        return view('admin.profile.contact-view', compact('admin'));
    }
}
