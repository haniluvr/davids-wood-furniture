<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\MagicLinkService;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Generate a unique username from Google user's name.
     */
    private function generateUsername($fullName)
    {
        // Split name into parts
        $nameParts = explode(' ', trim($fullName));
        $firstName = strtolower($nameParts[0]);
        $lastName = isset($nameParts[1]) ? strtolower($nameParts[1]) : '';

        // Create username: first letter of first name + last name
        if (empty($lastName)) {
            // If no last name, use first name
            $baseUsername = $firstName;
        } else {
            $baseUsername = $firstName[0].$lastName;
        }
        $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', $baseUsername); // Remove non-alphanumeric characters

        // Check if username exists
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            // If username exists, add 4 random numbers
            $randomNumbers = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $username = $baseUsername.$randomNumbers;

            // Extra safety: if somehow this still exists, add counter
            if (User::where('username', $username)->exists()) {
                $username = $baseUsername.$randomNumbers.$counter++;
            } else {
                break;
            }
        }

        return $username;
    }

    /**
     * Check if username is available.
     */
    public function checkUsername($username): JsonResponse
    {
        \Log::info('Checking username availability', ['username' => $username]);

        $exists = User::where('username', $username)->exists();

        \Log::info('Username check result', [
            'username' => $username,
            'exists' => $exists,
            'available' => ! $exists,
        ]);

        return response()->json([
            'available' => ! $exists,
            'message' => $exists ? 'Username is already taken' : 'Username is available',
        ], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Check email availability.
     */
    public function checkEmail($email): JsonResponse
    {
        \Log::info('Checking email availability', ['email' => $email]);

        $exists = User::where('email', $email)->exists();

        \Log::info('Email check result', [
            'email' => $email,
            'exists' => $exists,
        ]);

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email is already registered' : 'Email is available',
        ], 200, ['Content-Type' => 'application/json']);
    }

    public function register(Request $request): JsonResponse
    {
        // Capture session ID IMMEDIATELY at the start of the method
        $originalSessionId = session()->getId();

        \Log::info('AuthController: Register method started', [
            'immediate_session_id' => $originalSessionId,
            'authenticated' => \Auth::check(),
            'request_data' => $request->all(),
            'request_method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
        ]);

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|min:3|max:20|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            \Log::error('Registration validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'provider' => 'local',
        ]);

        // Use the immediately captured session ID for migration
        $guestSessionId = $originalSessionId;

        \Log::info('User registration - Guest data migration', [
            'user_id' => $user->id,
            'guest_session_id' => $guestSessionId,
            'has_guest_cart' => \App\Models\CartItem::where('session_id', $guestSessionId)->exists(),
            'has_guest_wishlist' => \App\Models\WishlistItem::where('session_id', $guestSessionId)->exists(),
        ]);

        // Store guest session ID and user ID for later migration after email verification
        session(['pending_guest_session_id' => $guestSessionId]);
        session(['pending_user_id' => $user->id]);
        session(['pending_intended_url' => session()->pull('url.intended', route('home'))]);

        \Log::info('User registered - pending email verification', [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'guest_session_id' => $guestSessionId,
            'authenticated' => false, // Not logged in yet
        ]);

        // Send email verification instead of auto-login
        try {
            $magicLinkService = new MagicLinkService;
            $token = $magicLinkService->generateMagicLink($user, 'email_verification');

            \Log::info('Email verification sent', [
                'user_id' => $user->id,
                'email' => $user->email,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send email verification', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            // Continue with registration even if email fails
        }

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Please check your email to verify your account.',
            'requires_verification' => true,
            'redirect' => route('auth.verify-email-sent').'?email='.urlencode($user->email),
            'email' => $user->email,
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        // Capture session ID IMMEDIATELY at the start of the method
        // This ensures we get the original session ID before any processing
        $originalSessionId = session()->getId();

        \Log::info('AuthController: Login method started - IMMEDIATE session capture', [
            'immediate_session_id' => $originalSessionId,
            'authenticated' => \Auth::check(),
        ]);

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Use the immediately captured session ID for migration
        $guestSessionId = $originalSessionId;

        \Log::info('AuthController: Login method started', [
            'current_session_id' => session()->getId(),
            'original_session_id' => $request->get('original_session_id'),
            'guest_session_id_used' => $guestSessionId,
            'authenticated' => \Auth::check(),
        ]);

        \Log::info('User login - Guest data migration', [
            'guest_session_id' => $guestSessionId,
            'has_guest_cart' => \App\Models\CartItem::where('session_id', $guestSessionId)->exists(),
            'has_guest_wishlist' => \App\Models\WishlistItem::where('session_id', $guestSessionId)->exists(),
        ]);

        // Try to find user by username or email for backward compatibility
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if ($user && Auth::attempt(['id' => $user->id, 'password' => $request->password], $request->boolean('remember'))) {
            // Get user after successful authentication
            $user = Auth::user();

            \Log::info('User login successful - Migrating guest data', [
                'user_id' => $user->id,
                'guest_session_id' => $guestSessionId,
                'session_id_after_auth' => session()->getId(),
            ]);

            // Migrate guest data AFTER authentication to prevent session conflicts
            $cartController = new CartController;
            $cartController->migrateCartToUser($user->id, $guestSessionId);

            $sessionWishlistService = new \App\Services\SessionWishlistService;
            $sessionWishlistService->migrateGuestToUser($user->id, $guestSessionId);

            \Log::info('User login - Session handled by Laravel', [
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'auth_check' => Auth::check(),
            ]);

            // Get intended redirect URL from session, fallback to home
            $intendedUrl = session()->pull('url.intended', route('home'));

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'redirect' => $intendedUrl,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function logout()
    {
        \Log::info('LOGOUT: Starting logout process');

        try {
            // Get user info before logout for Google OAuth handling
            $user = Auth::user();
            $isGoogleUser = $user && $user->provider === 'google';

            \Log::info('LOGOUT: User info', [
                'user_id' => $user ? $user->id : null,
                'provider' => $user ? $user->provider : null,
                'is_google_user' => $isGoogleUser,
                'has_remember_token' => $user && $user->remember_token ? true : false,
            ]);

            // Clear authentication
            Auth::logout();
            \Log::info('LOGOUT: Auth::logout() completed');

            // Clear session data
            session()->flush();
            \Log::info('LOGOUT: Session flushed');

            // Don't regenerate session during logout to avoid affecting other active sessions
            \Log::info('LOGOUT: Session cleared (no regeneration)');

            // Clear remember token for Google OAuth users
            if ($user && $user->remember_token) {
                $user->remember_token = null;
                $user->save();
                \Log::info('LOGOUT: Remember token cleared for user', ['user_id' => $user->id]);
            }

            \Log::info('LOGOUT: Logout process completed successfully');

            // Return JSON response for AJAX requests
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully',
                    'debug' => [
                        'was_google_user' => $isGoogleUser,
                        'user_id' => $user ? $user->id : null,
                    ],
                ]);
            }

            // Redirect to home for regular requests
            return redirect()->route('home')->with('success', 'Logged out successfully');
        } catch (\Exception $e) {
            \Log::error('LOGOUT: Error during logout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Even if there's an error, try to clear session
            try {
                Auth::logout();
                session()->flush();
                // Don't regenerate session during emergency cleanup
                \Log::info('LOGOUT: Emergency cleanup completed');
            } catch (\Exception $cleanupError) {
                \Log::error('LOGOUT: Emergency cleanup failed', [
                    'error' => $cleanupError->getMessage(),
                ]);
            }

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully',
                    'debug' => [
                        'error_occurred' => true,
                        'error_message' => $e->getMessage(),
                    ],
                ]);
            }

            return redirect()->route('home');
        }
    }

    // Google OAuth Methods
    public function redirectToGoogle()
    {
        try {
            // Store the current URL as intended URL before redirecting to Google
            $intendedUrl = request()->input('intended_url', request()->header('referer', route('home')));
            session()->put('url.intended', $intendedUrl);

            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return redirect()->route('home')->withErrors(['error' => 'Google OAuth configuration error: '.$e->getMessage()]);
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        // Capture session ID IMMEDIATELY at the start of the method
        $originalSessionId = session()->getId();

        \Log::info('AuthController: Google OAuth callback started - IMMEDIATE session capture', [
            'immediate_session_id' => $originalSessionId,
            'authenticated' => \Auth::check(),
        ]);

        try {
            // Configure the Socialite driver to handle SSL issues in development
            /** @var \Laravel\Socialite\Two\GoogleProvider $googleDriver */
            $googleDriver = Socialite::driver('google');

            // For local development, disable SSL certificate verification
            if (config('app.env') === 'local' || config('app.debug')) {
                $googleDriver->setHttpClient(new Client([
                    'verify' => false,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ],
                ]));
            }

            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = $googleDriver->user();

            $existingUser = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($existingUser) {
                // Use the immediately captured session ID for migration
                $guestSessionId = $originalSessionId;

                \Log::info('Google OAuth - Existing user login', [
                    'user_id' => $existingUser->id,
                    'guest_session_id' => $guestSessionId,
                    'has_guest_cart' => \App\Models\CartItem::where('session_id', $guestSessionId)->exists(),
                    'has_guest_wishlist' => \App\Models\WishlistItem::where('session_id', $guestSessionId)->exists(),
                ]);

                // Update existing user with Google ID if not already set
                if (! $existingUser->google_id) {
                    $updateData = [
                        'google_id' => $googleUser->id,
                        'provider' => 'google',
                        'avatar' => $googleUser->avatar ?? null,
                    ];

                    // Generate username if user doesn't have one
                    if (empty($existingUser->username)) {
                        $username = $this->generateUsername($googleUser->name ?? 'User');
                        $updateData['username'] = $username;
                    }

                    $existingUser->update($updateData);
                }

                // Migrate guest data BEFORE authentication to prevent session loss
                $cartController = new CartController;
                $cartController->migrateCartToUser($existingUser->id, $guestSessionId);

                $sessionWishlistService = new \App\Services\SessionWishlistService;
                \Log::info('AuthController: Calling migrateGuestToUser (login existing user)', [
                    'user_id' => $existingUser->id,
                    'guest_session_id_passed' => $guestSessionId,
                ]);
                $sessionWishlistService->migrateGuestToUser($existingUser->id, $guestSessionId);

                Auth::login($existingUser);
            } else {
                // Use the immediately captured session ID for migration
                $guestSessionId = $originalSessionId;

                \Log::info('Google OAuth - New user creation', [
                    'guest_session_id' => $guestSessionId,
                    'has_guest_cart' => \App\Models\CartItem::where('session_id', $guestSessionId)->exists(),
                    'has_guest_wishlist' => \App\Models\WishlistItem::where('session_id', $guestSessionId)->exists(),
                ]);

                // Generate username for Google user
                $username = $this->generateUsername($googleUser->name ?? 'User');

                // Split Google name into first and last name
                $nameParts = explode(' ', trim($googleUser->name ?? 'User'));
                $firstName = $nameParts[0] ?? '';
                $lastName = implode(' ', array_slice($nameParts, 1)) ?? '';

                // Create new user from Google data
                $newUser = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $googleUser->email,
                    'username' => $username,
                    'password' => null, // No password for Google OAuth users
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? null,
                    'provider' => 'google',
                ]);

                // Migrate guest data BEFORE authentication to prevent session loss
                $cartController = new CartController;
                $cartController->migrateCartToUser($newUser->id, $guestSessionId);

                $sessionWishlistService = new \App\Services\SessionWishlistService;
                $sessionWishlistService->migrateGuestToUser($newUser->id, $guestSessionId);

                Auth::login($newUser);
            }

            // Get intended redirect URL from session, fallback to home
            $intendedUrl = session()->pull('url.intended', route('home'));

            return redirect($intendedUrl)->with('google_signin_success', 'Welcome! Please remember to add a password in your account settings for added security.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->route('home')->withErrors(['error' => 'Google authentication failed: '.$e->getMessage()]);
        }
    }

    /**
     * Store the intended URL for redirect after login.
     */
    public function storeIntendedUrl(Request $request)
    {
        try {
            $intendedUrl = $request->input('intended_url');

            if ($intendedUrl) {
                session()->put('url.intended', $intendedUrl);

                return response()->json([
                    'success' => true,
                    'message' => 'Intended URL stored successfully',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No intended URL provided',
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Error storing intended URL:', [
                'error' => $e->getMessage(),
                'intended_url' => $request->input('intended_url'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to store intended URL',
            ], 500);
        }
    }

    /**
     * Handle email verification.
     */
    public function verifyEmail($token)
    {
        $magicLinkService = new MagicLinkService;
        $tokenRecord = $magicLinkService->verifyMagicLink($token, 'email_verification');

        if (! $tokenRecord) {
            return redirect()->route('auth.verify-email-sent')
                ->withErrors(['error' => 'Invalid or expired verification link.']);
        }

        // Find the user by email
        $user = User::where('email', $tokenRecord->email)->first();

        if (! $user) {
            return redirect()->route('auth.verify-email-sent')
                ->withErrors(['error' => 'User not found.']);
        }

        // Mark email as verified
        $user->update(['email_verified_at' => now()]);

        // Log in the user
        Auth::login($user);

        // Migrate guest data now that user is verified and logged in
        $guestSessionId = session('pending_guest_session_id');
        if ($guestSessionId) {
            try {
                $cartController = new CartController;
                $cartController->migrateCartToUser($user->id, $guestSessionId);

                $sessionWishlistService = new \App\Services\SessionWishlistService;
                $sessionWishlistService->migrateGuestToUser($user->id, $guestSessionId);

                \Log::info('Guest data migration completed after email verification', [
                    'user_id' => $user->id,
                    'guest_session_id' => $guestSessionId,
                ]);
            } catch (\Exception $e) {
                \Log::error('Guest data migration failed after email verification', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'guest_session_id' => $guestSessionId,
                ]);
            }

            // Clear pending session data
            session()->forget(['pending_guest_session_id', 'pending_user_id']);
        }

        // Get intended redirect URL
        $intendedUrl = session('pending_intended_url', route('home'));
        session()->forget('pending_intended_url');

        \Log::info('Email verification completed', [
            'user_id' => $user->id,
            'email' => $user->email,
            'authenticated' => Auth::check(),
            'redirect_url' => $intendedUrl,
        ]);

        return redirect($intendedUrl)
            ->with('success', 'Email verified successfully! Welcome to David\'s Wood Furniture.');
    }

    /**
     * Handle forgot password request.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['username' => 'required|string']);

        $user = User::where('username', $request->username)->first();

        \Log::info('User lookup for forgot password', [
            'username' => $request->username,
            'user_found' => $user ? true : false,
            'user_email' => $user ? $user->email : null,
            'user_id' => $user ? $user->id : null,
        ]);

        if (! $user) {
            \Log::warning('User not found for forgot password', [
                'username' => $request->username,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No account found with this username.',
            ]);
        }

        try {
            // Simple logging without complex arrays
            \Log::info('Attempting to send password reset email for user: '.$user->username);
            \Log::info('User email: '.$user->email);
            \Log::info('Mail driver: '.config('mail.default'));

            // Test if PasswordResetMail class exists and can be instantiated
            try {
                $testMail = new \App\Mail\PasswordResetMail($user, 'test-token', now());
                \Log::info('PasswordResetMail class instantiated successfully');
            } catch (\Exception $e) {
                \Log::error('Failed to instantiate PasswordResetMail: '.$e->getMessage());

                throw $e;
            }

            $magicLinkService = new MagicLinkService;
            $token = $magicLinkService->generateMagicLink($user, 'password_reset');

            \Log::info('Password reset email sent successfully');
            \Log::info('Token generated: '.$token);

            // For development with log mailer, always return success
            // The email will be logged to storage/logs/laravel.log
            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email!',
                'user_email' => $user->email,
                'debug_info' => config('app.debug') ? [
                    'token' => $token,
                    'reset_url' => route('auth.reset-password', $token),
                    'mail_driver' => config('mail.default'),
                    'note' => 'Email logged to storage/logs/laravel.log (development mode)',
                ] : null,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email');
            \Log::error('Error: '.$e->getMessage());
            \Log::error('File: '.$e->getFile().' Line: '.$e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send password reset email. Please try again.',
            ]);
        }
    }

    /**
     * Show password reset form.
     */
    public function showResetPasswordForm($token)
    {
        // Check if token is valid (without marking it as used)
        $magicLinkService = new MagicLinkService;
        $isValid = $magicLinkService->isValidMagicLink($token, 'password_reset');

        if (! $isValid) {
            return redirect()->route('home')
                ->withErrors(['error' => 'Invalid or expired password reset link.']);
        }

        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle password reset.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $magicLinkService = new MagicLinkService;
        $tokenRecord = $magicLinkService->verifyMagicLink($request->token, 'password_reset');

        if (! $tokenRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired password reset link.',
            ]);
        }

        // Find the user by email
        $user = User::where('email', $tokenRecord->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        \Log::info('Password reset completed', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully!',
        ]);
    }

    /**
     * Resend email verification.
     */
    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with this email address.',
            ]);
        }

        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'This email address is already verified.',
            ]);
        }

        try {
            $magicLinkService = new MagicLinkService;
            $token = $magicLinkService->generateMagicLink($user, 'email_verification');

            return response()->json([
                'success' => true,
                'message' => 'Verification email sent successfully!',
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to resend verification email', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email. Please try again.',
            ]);
        }
    }

    /**
     * Show email verification sent page.
     */
    public function verifyEmailSent(Request $request)
    {
        $email = $request->query('email');

        if (! $email) {
            return redirect()->route('home');
        }

        // Store email in session for resend functionality
        session(['verification_email' => $email]);

        return view('auth.verify-email-sent', compact('email'));
    }
}
