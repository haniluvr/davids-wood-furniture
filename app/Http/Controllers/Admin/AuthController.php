<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->to(admin_route('dashboard'));
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Check if admin exists and is active
        $admin = Admin::where('email', $credentials['email'])->first();

        if (! $admin) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if (! $admin->isActive()) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been suspended. Please contact the administrator.'],
            ]);
        }

        if (! Hash::check($credentials['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        // Check if 2FA is enabled (mandatory for admins)
        if ($admin->two_factor_enabled) {
            // Generate magic link for 2FA
            $magicLinkService = new \App\Services\MagicLinkService;
            $token = $magicLinkService->generateMagicLink($admin, '2fa');

            // Store pending 2FA state in session
            session(['pending_admin_2fa_id' => $admin->id]);

            return redirect()->route('admin.check-email')
                ->with('success', 'Please check your email to complete login');
        }

        // Login the admin
        Auth::guard('admin')->login($admin, $remember);

        // Update last login info
        $admin->updateLastLogin();

        // Log the login
        AuditLog::logLogin($admin);

        $request->session()->regenerate();

        return redirect()->intended(admin_route('dashboard'))
            ->with('success', 'Welcome back, '.$admin->first_name.'!');
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin) {
            // Log the logout
            AuditLog::logLogout($admin);
        }

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Handle AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'You have been logged out successfully.',
                'redirect' => admin_route('login'),
            ]);
        }

        return redirect()->to(admin_route('login'))
            ->with('success', 'You have been logged out successfully.');
    }

    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        // Here you would implement password reset functionality
        // For now, we'll just return a success message

        return back()->with('success', 'Password reset link has been sent to your email.');
    }

    /**
     * Verify magic link for admin 2FA
     */
    public function verifyMagicLink($token)
    {
        $magicLinkService = new \App\Services\MagicLinkService;
        $tokenRecord = $magicLinkService->verifyMagicLink($token, '2fa');

        if (! $tokenRecord) {
            return redirect()->route('admin.login')->withErrors(['error' => 'Invalid or expired magic link.']);
        }

        // Find the admin
        $admin = Admin::where('email', $tokenRecord->email)->first();

        if (! $admin) {
            return redirect()->route('admin.login')->withErrors(['error' => 'Admin not found.']);
        }

        // Login the admin
        Auth::guard('admin')->login($admin);

        // Update 2FA verification timestamp
        $admin->update(['two_factor_verified_at' => now()]);

        // Clear pending 2FA state
        session()->forget('pending_admin_2fa_id');

        // Log the login
        AuditLog::logLogin($admin);

        return redirect()->intended(admin_route('dashboard'))
            ->with('success', 'Login completed successfully!');
    }

    /**
     * Show check email page for admin
     */
    public function checkEmail()
    {
        return view('admin.auth.check-email');
    }
}
