<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\MagicLinkService;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

        // Ensure minimum length of 3 characters
        if (strlen($baseUsername) < 3) {
            // If too short, pad with random numbers
            $randomNumbers = str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
            $baseUsername = $baseUsername.$randomNumbers;
        }

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
        $exists = User::where('username', $username)->exists();

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
        $exists = User::where('email', $email)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email is already registered' : 'Email is available',
        ], 200, ['Content-Type' => 'application/json']);
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|min:3|max:20|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
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

        $guestSessionId = session()->getId();

        // Store guest session ID and user ID for later migration after email verification
        session(['pending_guest_session_id' => $guestSessionId]);
        session(['pending_user_id' => $user->id]);
        session(['pending_intended_url' => session()->pull('url.intended', route('home'))]);

        // Send email verification instead of auto-login
        try {
            $magicLinkService = new MagicLinkService;
            $magicLinkService->generateMagicLink($user, 'email_verification');
        } catch (\Exception $e) {
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

        $guestSessionId = session()->getId();

        // Try to find user by username or email for backward compatibility
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        // Check if user exists and is a Google OAuth user without a password
        if ($user && $user->isSsoUser() && ! $user->hasPassword()) {
            return response()->json([
                'success' => false,
                'message' => 'This account uses Google sign-in. Please use "Sign in with Google" instead.',
                'error_type' => 'google_oauth_required',
            ], 401);
        }

        if ($user && Auth::attempt(['id' => $user->id, 'password' => $request->password], $request->boolean('remember'))) {
            // Get user after successful authentication
            $user = Auth::user();

            // Migrate guest data AFTER authentication to prevent session conflicts
            $cartController = new CartController;
            $cartController->migrateCartToUser($user->id, $guestSessionId);

            $sessionWishlistService = new \App\Services\SessionWishlistService;
            $sessionWishlistService->migrateGuestToUser($user->id, $guestSessionId);

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
        try {
            $user = Auth::user();

            // Clear authentication
            Auth::logout();

            // Clear session data
            session()->flush();

            // Clear remember token
            if ($user && $user->remember_token) {
                $user->remember_token = null;
                $user->save();
            }

            // Return JSON response for AJAX requests
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully',
                ]);
            }

            // Redirect to home for regular requests
            return redirect()->route('home')->with('success', 'Logged out successfully');
        } catch (\Exception $e) {
            // Even if there's an error, try to clear session
            try {
                Auth::logout();
                session()->flush();
            } catch (\Exception $cleanupError) {
                // Ignore cleanup errors
            }

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully',
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

            // Validate that the intended URL is not an API endpoint
            // If it's an API route, fallback to home to prevent redirecting to JSON responses
            if ($intendedUrl && str_starts_with(parse_url($intendedUrl, PHP_URL_PATH) ?? '', '/api')) {
                $intendedUrl = route('home');
            }

            session()->put('url.intended', $intendedUrl);

            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return redirect()->route('home')->withErrors(['error' => 'Google OAuth configuration error: '.$e->getMessage()]);
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        // Check for OAuth errors from Google
        if ($request->has('error')) {
            return redirect()->route('home')->withErrors([
                'error' => 'Google authentication failed: '.($request->input('error_description') ?? $request->input('error')),
            ]);
        }

        try {
            // Verify Google OAuth configuration
            $clientId = config('services.google.client_id');
            $clientSecret = config('services.google.client_secret');

            if (empty($clientId) || empty($clientSecret)) {
                throw new \Exception('Google OAuth is not properly configured. Please check your environment variables.');
            }

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

            $guestSessionId = session()->getId();

            if ($existingUser) {
                $updateData = [];

                // Update existing user with Google ID if not already set
                if (! $existingUser->google_id) {
                    $updateData['google_id'] = $googleUser->id;
                    $updateData['provider'] = 'google';
                    $updateData['avatar'] = $googleUser->avatar ?? null;
                }

                // Generate username if user doesn't have one
                if (empty($existingUser->username)) {
                    $username = $this->generateUsername($googleUser->name ?? 'User');
                    $updateData['username'] = $username;
                }

                // Verify email if not already verified
                if (! $existingUser->email_verified_at) {
                    $updateData['email_verified_at'] = now();
                }

                if (! empty($updateData)) {
                    $existingUser->update($updateData);
                }

                // Migrate guest data BEFORE authentication to prevent session loss
                $cartController = new CartController;
                $cartController->migrateCartToUser($existingUser->id, $guestSessionId);

                $sessionWishlistService = new \App\Services\SessionWishlistService;
                $sessionWishlistService->migrateGuestToUser($existingUser->id, $guestSessionId);

                // Login the user
                Auth::login($existingUser);

                // Force session save to ensure authentication persists after redirect
                session()->save();
            } else {
                // Generate username for Google user
                $username = $this->generateUsername($googleUser->name ?? 'User');

                // Ensure username is not empty (fallback to email-based username if needed)
                if (empty($username)) {
                    $emailParts = explode('@', $googleUser->email);
                    $username = $this->generateUsername($emailParts[0] ?? 'user'.rand(1000, 9999));
                }

                // Split Google name into first and last name
                $nameParts = explode(' ', trim($googleUser->name ?? 'User'));
                $firstName = $nameParts[0] ?? '';
                $lastName = implode(' ', array_slice($nameParts, 1)) ?? '';

                // Create new user from Google data with email verified
                $newUser = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $googleUser->email,
                    'username' => $username,
                    'password' => null, // No password for Google OAuth users
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? null,
                    'provider' => 'google',
                    'email_verified_at' => now(), // Email is already verified through Google
                ]);

                // Migrate guest data BEFORE authentication to prevent session loss
                $cartController = new CartController;
                $cartController->migrateCartToUser($newUser->id, $guestSessionId);

                $sessionWishlistService = new \App\Services\SessionWishlistService;
                $sessionWishlistService->migrateGuestToUser($newUser->id, $guestSessionId);

                // Login the user
                Auth::login($newUser);

                // Force session save to ensure authentication persists after redirect
                session()->save();

                // Send welcome email to new Google OAuth users
                try {
                    Mail::to($newUser->email)->send(new WelcomeMail($newUser));
                } catch (\Exception $e) {
                    // Continue even if welcome email fails
                }
            }

            // Get intended redirect URL from session, fallback to home
            $intendedUrl = session()->pull('url.intended', route('home'));

            // Validate that the intended URL is not an API endpoint
            // If it's an API route, fallback to home to prevent redirecting to JSON responses
            if (str_starts_with(parse_url($intendedUrl, PHP_URL_PATH) ?? '', '/api')) {
                $intendedUrl = route('home');
            }

            // Final session save before redirect
            session()->save();

            return redirect($intendedUrl)->with('google_signin_success', 'Welcome! Please remember to add a password in your account settings for added security.');
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            return redirect()->route('home')->withErrors([
                'error' => 'Session expired. Please try signing in with Google again.',
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return redirect()->route('home')->withErrors([
                'error' => 'Google authentication failed. Please check your Google OAuth configuration.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('home')->withErrors([
                'error' => 'Google authentication failed: '.$e->getMessage(),
            ]);
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
                // Don't store API endpoints as intended URLs to prevent redirecting to JSON responses
                $path = parse_url($intendedUrl, PHP_URL_PATH) ?? '';
                if (str_starts_with($path, '/api')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'API endpoints cannot be stored as intended URLs',
                    ], 400);
                }

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
            } catch (\Exception $e) {
                // Continue even if migration fails
            }

            // Clear pending session data
            session()->forget(['pending_guest_session_id', 'pending_user_id']);
        }

        // Get intended redirect URL
        $intendedUrl = session('pending_intended_url', route('home'));
        session()->forget('pending_intended_url');

        // Validate that the intended URL is not an API endpoint
        // If it's an API route, fallback to home to prevent redirecting to JSON responses
        if (str_starts_with(parse_url($intendedUrl, PHP_URL_PATH) ?? '', '/api')) {
            $intendedUrl = route('home');
        }

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

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with this username.',
            ]);
        }

        try {
            $magicLinkService = new MagicLinkService;
            $token = $magicLinkService->generateMagicLink($user, 'password_reset');

            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email!',
                'user_email' => $user->email,
            ]);
        } catch (\Exception $e) {
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
