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
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
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

        Auth::login($user);

        // Migrate guest cart to user account
        $cartController = new CartController();
        $cartController->migrateCartToUser($user->id, session()->getId());
        
        // Migrate guest wishlist to user account
        $wishlistController = new WishlistController();
        $wishlistController->migrateWishlistToUser($user->id, session()->getId());

        // Generate a remember token for API authentication
        $user = Auth::user();
        $token = \Illuminate\Support\Str::random(60);
        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => $user,
            'data' => [
                'token' => $token
            ],
            'redirect' => route('home')
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
                'errors' => $validator->errors()
            ], 422);
        }

        // Get session ID BEFORE authentication (for migration)
        $guestSessionId = session()->getId();
        
        // Try to find user by username or email for backward compatibility
        $user = User::where('username', $request->username)
                   ->orWhere('email', $request->username)
                   ->first();

        if ($user && Auth::attempt(['id' => $user->id, 'password' => $request->password])) {
            
            // Migrate guest cart to user account
            $cartController = new CartController();
            $cartController->migrateCartToUser($user->id, $guestSessionId);
            
            // Migrate guest wishlist to user account
            $wishlistController = new WishlistController();
            $wishlistController->migrateWishlistToUser($user->id, $guestSessionId);

            // Generate a remember token for API authentication
            $user = Auth::user();
            $token = \Illuminate\Support\Str::random(60);
            $user->remember_token = $token;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'data' => [
                    'token' => $token
                ],
                'redirect' => route('home')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function logout(): JsonResponse
    {
        try {
            // Logout the user
            Auth::logout();
            
            // Clear session data
            session()->flush();
            
            // Regenerate session ID for security
            session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
                'redirect' => route('home')
            ]);
        } catch (\Exception $e) {
            // If logout fails, still try to clear session
            session()->flush();
            session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
                'redirect' => route('home')
            ]);
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

    public function handleGoogleCallback()
    {
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
                
                // Get session ID BEFORE authentication (for migration)
                $guestSessionId = session()->getId();
                
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
                Auth::login($existingUser);

                // Migrate guest cart to user account
                $cartController = new CartController();
                $cartController->migrateCartToUser($existingUser->id, $guestSessionId);
                
                // Migrate guest wishlist to user account
                $wishlistController = new WishlistController();
                $wishlistController->migrateWishlistToUser($existingUser->id, $guestSessionId);
            } else {
                
                // Get session ID BEFORE authentication (for migration)
                $guestSessionId = session()->getId();
                
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
                Auth::login($newUser);

                // Migrate guest cart to user account
                $cartController = new CartController();
                $cartController->migrateCartToUser($newUser->id, $guestSessionId);
                
                // Migrate guest wishlist to user account
                $wishlistController = new WishlistController();
                $wishlistController->migrateWishlistToUser($newUser->id, $guestSessionId);
            }

            return redirect()->route('home')->with('google_signin_success', 'Welcome! Please remember to add a password in your account settings for added security.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('home')->withErrors(['error' => 'Google authentication failed: ' . $e->getMessage()]);
        }
    }

}
