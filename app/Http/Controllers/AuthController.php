<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;

class AuthController extends Controller
{
    /**
     * Generate a unique username from Google user's name
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
            $baseUsername = $firstName[0] . $lastName;
        }
        $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', $baseUsername); // Remove non-alphanumeric characters
        
        // Check if username exists
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            // If username exists, add 4 random numbers
            $randomNumbers = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $username = $baseUsername . $randomNumbers;
            
            // Extra safety: if somehow this still exists, add counter
            if (User::where('username', $username)->exists()) {
                $username = $baseUsername . $randomNumbers . $counter++;
            } else {
                break;
            }
        }
        
        return $username;
    }
    
    /**
     * Check if username is available
     */
    public function checkUsername($username): JsonResponse
    {
        \Log::info('Checking username availability', ['username' => $username]);
        
        $exists = User::where('username', $username)->exists();
        
        \Log::info('Username check result', [
            'username' => $username,
            'exists' => $exists,
            'available' => !$exists
        ]);
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Username is already taken' : 'Username is available'
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
            'content_type' => $request->header('Content-Type')
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
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
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
            'has_guest_wishlist' => \App\Models\WishlistItem::where('session_id', $guestSessionId)->exists()
        ]);
        
        // Migrate guest data BEFORE authentication to prevent session loss
        try {
            $cartController = new CartController();
            $cartController->migrateCartToUser($user->id, $guestSessionId);
            
            $sessionWishlistService = new \App\Services\SessionWishlistService();
            $sessionWishlistService->migrateGuestToUser($user->id, $guestSessionId);
            
            \Log::info('Guest data migration completed successfully');
        } catch (\Exception $e) {
            \Log::error('Guest data migration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Continue with registration even if migration fails
        }
        
        Auth::login($user);
        
        // Session regeneration is handled by Laravel's regenerate_on_login config

        $user = Auth::user();
        
        \Log::info('User registered and logged in', [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'authenticated' => Auth::check(),
            'session_id' => session()->getId()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ],
            'authenticated' => Auth::check(),
            'redirect' => route('home')
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        // Capture session ID IMMEDIATELY at the start of the method
        // This ensures we get the original session ID before any processing
        $originalSessionId = session()->getId();
        
        \Log::info('AuthController: Login method started - IMMEDIATE session capture', [
            'immediate_session_id' => $originalSessionId,
            'authenticated' => \Auth::check()
        ]);
        
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Use the immediately captured session ID for migration
        $guestSessionId = $originalSessionId;
        
        \Log::info('AuthController: Login method started', [
            'current_session_id' => session()->getId(),
            'original_session_id' => $request->get('original_session_id'),
            'guest_session_id_used' => $guestSessionId,
            'authenticated' => \Auth::check()
        ]);
        
        \Log::info('User login - Guest data migration', [
            'guest_session_id' => $guestSessionId,
            'has_guest_cart' => \App\Models\CartItem::where('session_id', $guestSessionId)->exists(),
            'has_guest_wishlist' => \App\Models\WishlistItem::where('session_id', $guestSessionId)->exists()
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
                'session_id_after_auth' => session()->getId()
            ]);
            
            // Migrate guest data AFTER authentication to prevent session conflicts
            $cartController = new CartController();
            $cartController->migrateCartToUser($user->id, $guestSessionId);
            
            $sessionWishlistService = new \App\Services\SessionWishlistService();
            $sessionWishlistService->migrateGuestToUser($user->id, $guestSessionId);
            
            \Log::info('User login - Session handled by Laravel', [
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'auth_check' => Auth::check()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'redirect' => route('home')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
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
                'has_remember_token' => $user && $user->remember_token ? true : false
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
                        'user_id' => $user ? $user->id : null
                    ]
                ]);
            }
            
            // Redirect to home for regular requests
            return redirect()->route('home')->with('success', 'Logged out successfully');
            
        } catch (\Exception $e) {
            \Log::error('LOGOUT: Error during logout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Even if there's an error, try to clear session
            try {
                Auth::logout();
                session()->flush();
                // Don't regenerate session during emergency cleanup
                \Log::info('LOGOUT: Emergency cleanup completed');
            } catch (\Exception $cleanupError) {
                \Log::error('LOGOUT: Emergency cleanup failed', [
                    'error' => $cleanupError->getMessage()
                ]);
            }
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully',
                    'debug' => [
                        'error_occurred' => true,
                        'error_message' => $e->getMessage()
                    ]
                ]);
            }
            
            return redirect()->route('home');
        }
    }

    // Google OAuth Methods
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return redirect()->route('home')->withErrors(['error' => 'Google OAuth configuration error: ' . $e->getMessage()]);
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        // Capture session ID IMMEDIATELY at the start of the method
        $originalSessionId = session()->getId();
        
        \Log::info('AuthController: Google OAuth callback started - IMMEDIATE session capture', [
            'immediate_session_id' => $originalSessionId,
            'authenticated' => \Auth::check()
        ]);
        
        try {
            
            // Configure the Socialite driver to handle SSL issues in development
            $googleDriver = Socialite::driver('google');
            
            // For local development, disable SSL certificate verification
            if (config('app.env') === 'local' || config('app.debug')) {
                $googleDriver->setHttpClient(new Client([
                    'verify' => false,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ]
                ]));
            }
            
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
                    'has_guest_wishlist' => \App\Models\WishlistItem::where('session_id', $guestSessionId)->exists()
                ]);
                
                // Update existing user with Google ID if not already set
                if (!$existingUser->google_id) {
                    $updateData = [
                        'google_id' => $googleUser->id,
                        'provider' => 'google',
                        'avatar' => $googleUser->avatar,
                    ];
                    
                    // Generate username if user doesn't have one
                    if (empty($existingUser->username)) {
                        $username = $this->generateUsername($googleUser->name);
                        $updateData['username'] = $username;
                    }
                    
                    $existingUser->update($updateData);
                }
                
                // Migrate guest data BEFORE authentication to prevent session loss
                $cartController = new CartController();
                $cartController->migrateCartToUser($existingUser->id, $guestSessionId);
                
                $sessionWishlistService = new \App\Services\SessionWishlistService();
                \Log::info('AuthController: Calling migrateGuestToUser (login existing user)', [
                'user_id' => $existingUser->id,
                'guest_session_id_passed' => $guestSessionId
            ]);
            $sessionWishlistService->migrateGuestToUser($existingUser->id, $guestSessionId);
                
                Auth::login($existingUser);
                
                
            } else {
                
                // Use the immediately captured session ID for migration
                $guestSessionId = $originalSessionId;
                
                \Log::info('Google OAuth - New user creation', [
                    'guest_session_id' => $guestSessionId,
                    'has_guest_cart' => \App\Models\CartItem::where('session_id', $guestSessionId)->exists(),
                    'has_guest_wishlist' => \App\Models\WishlistItem::where('session_id', $guestSessionId)->exists()
                ]);
                
                // Generate username for Google user
                $username = $this->generateUsername($googleUser->name);
                
                // Split Google name into first and last name
                $nameParts = explode(' ', trim($googleUser->name));
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
                    'avatar' => $googleUser->avatar,
                    'provider' => 'google',
                ]);
                
                // Migrate guest data BEFORE authentication to prevent session loss
                $cartController = new CartController();
                $cartController->migrateCartToUser($newUser->id, $guestSessionId);
                
                $sessionWishlistService = new \App\Services\SessionWishlistService();
                $sessionWishlistService->migrateGuestToUser($newUser->id, $guestSessionId);
                
                Auth::login($newUser);
                
                
            }

            return redirect()->route('home')->with('google_signin_success', 'Welcome! Please remember to add a password in your account settings for added security.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('home')->withErrors(['error' => 'Google authentication failed: ' . $e->getMessage()]);
        }
    }

}
