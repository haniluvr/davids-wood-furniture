<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminOtpMail;
use App\Models\Admin;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            // Check if admin has personal email for OTP
            if (! $admin->personal_email) {
                throw ValidationException::withMessages([
                    'email' => ['Personal email is required for two-factor authentication. Please contact administrator.'],
                ]);
            }

            // Generate OTP code
            $otpCode = $admin->generateOtpCode();

            // Send OTP email
            try {
                Mail::to($admin->personal_email)->send(new AdminOtpMail($admin, $otpCode));

                \Log::info('Admin OTP sent', [
                    'admin_id' => $admin->id,
                    'admin_email' => $admin->email,
                    'personal_email' => $admin->personal_email,
                    'otp_code' => $otpCode,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send admin OTP email', [
                    'admin_id' => $admin->id,
                    'personal_email' => $admin->personal_email,
                    'error' => $e->getMessage(),
                ]);

                throw ValidationException::withMessages([
                    'email' => ['Failed to send verification code. Please try again.'],
                ]);
            }

            // Store pending 2FA state in session
            session(['pending_admin_2fa_id' => $admin->id]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Verification code sent to your email']);
            }

            return redirect()->to(admin_route('verify-otp'))
                ->with('success', 'Verification code sent to your email');
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
            'email' => 'required|email|exists:employees,email',
        ]);

        // Find the admin by email
        $admin = Admin::where('email', $request->email)->first();

        if (! $admin) {
            return back()->withErrors(['email' => 'No admin account found with this email address.']);
        }

        // Check if admin has personal email for sending reset link
        if (! $admin->personal_email) {
            return back()->withErrors(['email' => 'No personal email configured for this account. Please contact administrator.']);
        }

        try {
            // Generate magic link for password reset
            $magicLinkService = new \App\Services\MagicLinkService;
            $token = $magicLinkService->generateMagicLink($admin, 'password_reset');

            // Build the reset URL using the current request's domain and port
            $scheme = $request->getScheme();
            $host = $request->getHost();
            $port = $request->getPort();

            // Construct URL with proper port handling
            $portString = '';
            if ($port && $port !== 80 && $port !== 443) {
                if (($scheme === 'http' && $port !== 80) || ($scheme === 'https' && $port !== 443)) {
                    $portString = ':'.$port;
                }
            }

            $resetUrl = $scheme.'://'.$host.$portString.'/reset-password/'.$token;

            // Send password reset email to personal email
            Mail::to($admin->personal_email)->send(new \App\Mail\AdminPasswordResetMail($admin, $resetUrl, now()->addHours(1)));

            \Log::info('Admin password reset link sent', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'personal_email' => $admin->personal_email,
            ]);

            return back()->with('success', 'Password reset link has been sent to your email address.');
        } catch (\Exception $e) {
            \Log::error('Failed to send admin password reset email', [
                'admin_id' => $admin->id,
                'personal_email' => $admin->personal_email,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['email' => 'Failed to send password reset email. Please try again.']);
        }
    }

    /**
     * Verify magic link for admin 2FA.
     */
    public function verifyMagicLink($token)
    {
        $magicLinkService = new \App\Services\MagicLinkService;
        $tokenRecord = $magicLinkService->verifyMagicLink($token, '2fa');

        if (! $tokenRecord) {
            return redirect()->to(admin_route('login'))->withErrors(['error' => 'Invalid or expired magic link.']);
        }

        // Find the admin
        $admin = Admin::where('email', $tokenRecord->email)->first();

        if (! $admin) {
            return redirect()->to(admin_route('login'))->withErrors(['error' => 'Admin not found.']);
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
     * Show check email page for admin.
     */
    public function checkEmail()
    {
        return view('admin.auth.check-email');
    }

    /**
     * Show OTP verification page for admin.
     */
    public function showOtpVerification()
    {
        if (! session('pending_admin_2fa_id')) {
            return redirect()->to(admin_route('login'));
        }

        return view('admin.auth.verify-otp');
    }

    /**
     * Verify OTP code for admin 2FA.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $adminId = session('pending_admin_2fa_id');

        if (! $adminId) {
            return redirect()->to(admin_route('login'))
                ->withErrors(['error' => 'Session expired. Please login again.']);
        }

        $admin = Admin::find($adminId);

        if (! $admin) {
            return redirect()->to(admin_route('login'))
                ->withErrors(['error' => 'Admin not found.']);
        }

        if (! $admin->verifyOtpCode($request->code)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid or expired verification code.'], 422);
            }

            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        // Login the admin
        Auth::guard('admin')->login($admin);
        $admin->update(['two_factor_verified_at' => now()]);
        session()->forget('pending_admin_2fa_id');
        AuditLog::logLogin($admin);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Login completed successfully!']);
        }

        return redirect()->intended(admin_route('dashboard'))
            ->with('success', 'Login completed successfully!');
    }

    /**
     * Resend OTP code for admin 2FA.
     */
    public function resendOtp(Request $request)
    {
        $adminId = session('pending_admin_2fa_id');

        if (! $adminId) {
            return redirect()->to(admin_route('login'))
                ->withErrors(['error' => 'Session expired. Please login again.']);
        }

        $admin = Admin::find($adminId);

        if (! $admin) {
            return redirect()->to(admin_route('login'))
                ->withErrors(['error' => 'Admin not found.']);
        }

        // Generate new OTP code
        $otpCode = $admin->generateOtpCode();

        // Send new OTP email
        try {
            Mail::to($admin->personal_email)->send(new AdminOtpMail($admin, $otpCode));

            \Log::info('Admin OTP resent', [
                'admin_id' => $admin->id,
                'personal_email' => $admin->personal_email,
                'otp_code' => $otpCode,
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Verification code resent to your email']);
            }

            return back()->with('success', 'Verification code resent to your email');
        } catch (\Exception $e) {
            \Log::error('Failed to resend admin OTP email', [
                'admin_id' => $admin->id,
                'personal_email' => $admin->personal_email,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Failed to resend verification code. Please try again.']);
        }
    }

    /**
     * Show password setup form for new admin users.
     */
    public function showSetupPasswordForm($token)
    {
        // Check if token is valid (without marking it as used)
        $magicLinkService = new \App\Services\MagicLinkService;
        $isValid = $magicLinkService->isValidMagicLink($token, 'password-setup');

        if (! $isValid) {
            return redirect()->to(admin_route('login'))
                ->withErrors(['error' => 'Invalid or expired password setup link. Please contact your administrator.']);
        }

        // Get the token record to find the admin
        $tokenRecord = DB::table('magic_link_tokens')
            ->where('token', $token)
            ->where('type', 'password-setup')
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $tokenRecord) {
            return redirect()->to(admin_route('login'))
                ->withErrors(['error' => 'Invalid or expired password setup link.']);
        }

        // Find the admin
        $admin = Admin::where('email', $tokenRecord->email)->first();

        if (! $admin) {
            return redirect()->to(admin_route('login'))
                ->withErrors(['error' => 'Admin account not found.']);
        }

        return view('admin.auth.setup-password', ['token' => $token, 'admin' => $admin]);
    }

    /**
     * Handle password setup for new admin users.
     */
    public function setupPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $magicLinkService = new \App\Services\MagicLinkService;
        $tokenRecord = $magicLinkService->verifyMagicLink($request->token, 'password-setup');

        if (! $tokenRecord) {
            return back()->withErrors(['token' => 'Invalid or expired password setup link. Please contact your administrator.']);
        }

        // Find the admin
        $admin = Admin::where('email', $tokenRecord->email)->first();

        if (! $admin) {
            return back()->withErrors(['error' => 'Admin account not found.']);
        }

        // Update password and verify email
        $admin->password = Hash::make($request->password);
        $admin->email_verified_at = now();
        $admin->save();

        // Log password setup
        AuditLog::log('admin_user.password_setup', $admin, $admin, [], [], "Admin user {$admin->first_name} {$admin->last_name} set up their password");

        return redirect()->to(admin_route('login'))
            ->with('success', 'Password set successfully! You can now login with your credentials.');
    }

    /**
     * Show password reset form for admin users.
     */
    public function showResetPasswordForm($token)
    {
        // Check if token is valid (without marking it as used)
        $magicLinkService = new \App\Services\MagicLinkService;
        $isValid = $magicLinkService->isValidMagicLink($token, 'password_reset');

        if (! $isValid) {
            return redirect()->to(admin_route('login'))
                ->withErrors(['error' => 'Invalid or expired password reset link. Please request a new one.']);
        }

        // Get the token record to find the admin
        $tokenRecord = DB::table('magic_link_tokens')
            ->where('token', $token)
            ->where('type', 'password_reset')
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $tokenRecord) {
            return redirect()->to(admin_route('login'))
                ->withErrors(['error' => 'Invalid or expired password reset link.']);
        }

        // Find the admin
        $admin = Admin::where('email', $tokenRecord->email)->first();

        if (! $admin) {
            return redirect()->to(admin_route('login'))
                ->withErrors(['error' => 'Admin account not found.']);
        }

        return view('admin.auth.setup-password', ['token' => $token, 'admin' => $admin, 'isReset' => true]);
    }

    /**
     * Handle password reset for admin users.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $magicLinkService = new \App\Services\MagicLinkService;
        $tokenRecord = $magicLinkService->verifyMagicLink($request->token, 'password_reset');

        if (! $tokenRecord) {
            return back()->withErrors(['token' => 'Invalid or expired password reset link. Please request a new one.']);
        }

        // Find the admin
        $admin = Admin::where('email', $tokenRecord->email)->first();

        if (! $admin) {
            return back()->withErrors(['error' => 'Admin account not found.']);
        }

        // Update password
        $admin->password = Hash::make($request->password);
        $admin->save();

        // Log password reset
        AuditLog::log('admin_user.password_reset', $admin, $admin, [], [], "Admin user {$admin->first_name} {$admin->last_name} reset their password");

        return redirect()->to(admin_route('login'))
            ->with('success', 'Password reset successfully! You can now login with your new password.');
    }
}
