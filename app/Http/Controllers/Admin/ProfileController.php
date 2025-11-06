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

        // Get active session IDs for all admins
        $activeSessionIds = $this->getActiveAdminSessionIds();

        return view('admin.profile.contacts', compact('admins', 'activeSessionIds'));
    }

    /**
     * Get active session IDs for admin users from the sessions table.
     */
    private function getActiveAdminSessionIds(): array
    {
        try {
            $sessionTable = config('session.table', 'sessions');
            $currentTime = time();
            $sessionLifetime = config('session.lifetime', 120) * 60; // Convert to seconds

            // Query sessions table for active sessions
            $sessions = \DB::table($sessionTable)
                ->where('last_activity', '>', $currentTime - $sessionLifetime)
                ->get();

            $activeAdminIds = [];

            foreach ($sessions as $session) {
                try {
                    // Decode the session payload - Laravel stores it as base64 encoded serialized data
                    $decoded = base64_decode($session->payload, true);
                    if ($decoded === false) {
                        continue;
                    }

                    // Check if sessions are encrypted (Laravel encrypts by default)
                    $payload = null;
                    if (config('session.encrypt', false)) {
                        try {
                            // Decrypt the payload first - this gives us the serialized data
                            $decrypted = \Illuminate\Support\Facades\Crypt::decrypt($decoded, false);

                            // The decrypted data is a serialized string, so we need to unserialize it
                            $payload = @unserialize($decrypted);

                            // If it's still a string after unserialize, it might be double-serialized
                            if (is_string($payload) && strlen($payload) > 0) {
                                $payload = @unserialize($payload);
                            }
                        } catch (\Exception $e) {
                            // If decryption fails, try without decryption (for unencrypted sessions)
                            $payload = @unserialize($decoded);
                        }
                    } else {
                        // Sessions are not encrypted, just unserialize
                        $payload = @unserialize($decoded);
                    }

                    // If unserialize fails, try to decode as JSON (some Laravel versions)
                    if ($payload === false || $payload === null || (! is_array($payload) && ! is_object($payload))) {
                        // Try JSON decode on the original decoded data
                        $jsonPayload = json_decode($decoded, true);
                        if (is_array($jsonPayload)) {
                            $payload = $jsonPayload;
                        }
                    }

                    if (! is_array($payload)) {
                        continue;
                    }

                    // Laravel stores authentication data with the guard name
                    // For admin guard, it's typically: login_admin_{provider}_id or login_admin_id
                    $adminId = null;

                    // Try standard Laravel session key format
                    $possibleKeys = [
                        'login_admin_id',
                        'login_admin_'.config('auth.guards.admin.provider', 'admins').'_id',
                        'login_admin_web_id',
                        'login_admin_admins_id', // Explicit provider name
                        'login_web_admin_id',
                        '_token', // Sometimes stored separately
                    ];

                    foreach ($possibleKeys as $key) {
                        if (isset($payload[$key]) && $payload[$key]) {
                            if (is_numeric($payload[$key])) {
                                $adminId = (int) $payload[$key];

                                break;
                            }
                        }
                    }

                    // If not found, search through all keys for admin-related authentication
                    if (! $adminId) {
                        foreach ($payload as $key => $value) {
                            // Look for keys that contain 'admin' and 'id' or 'login'
                            if (is_string($key) && is_numeric($value)) {
                                $lowerKey = strtolower($key);
                                if ((str_contains($lowerKey, 'admin') || str_contains($lowerKey, 'login'))
                                    && (str_contains($lowerKey, 'id') || str_contains($lowerKey, 'user'))) {
                                    $adminId = (int) $value;

                                    break;
                                }
                            }
                        }
                    }

                    // Alternative: Check if there's a nested structure
                    if (! $adminId) {
                        // Some Laravel versions nest auth data
                        foreach ($payload as $key => $value) {
                            if (is_array($value) && isset($value['id']) && is_numeric($value['id'])) {
                                // Check if this looks like admin auth data
                                if (str_contains(strtolower($key), 'admin') || str_contains(strtolower($key), 'login')) {
                                    $adminId = (int) $value['id'];

                                    break;
                                }
                            }
                        }
                    }

                    // Last resort: Check all numeric values that might be admin IDs
                    // (This is a fallback - should be refined based on actual payload structure)
                    if (! $adminId) {
                        foreach ($payload as $key => $value) {
                            if (is_numeric($value) && $value > 0 && $value < 1000) { // Reasonable admin ID range
                                $lowerKey = strtolower((string) $key);
                                // If key mentions admin, login, or auth, it's likely an admin ID
                                if (str_contains($lowerKey, 'admin') ||
                                    str_contains($lowerKey, 'login') ||
                                    str_contains($lowerKey, 'auth') ||
                                    str_contains($lowerKey, 'user')) {
                                    $adminId = (int) $value;

                                    break;
                                }
                            }
                        }
                    }

                    if ($adminId && $adminId > 0) {
                        $activeAdminIds[] = $adminId;
                    }
                } catch (\Exception $e) {
                    // Skip invalid session data
                    continue;
                }
            }

            return array_unique($activeAdminIds);
        } catch (\Exception $e) {
            // If sessions table doesn't exist or query fails, return empty array
            \Log::warning('Failed to get active admin sessions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [];
        }
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
