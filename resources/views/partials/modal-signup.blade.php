<!-- Signup Modal -->
<div class="modal fade hidden" id="modal-signup" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-stone-50">
            <div class="modal-header flex items-center justify-between">
                <h5 class="modal-title" id="signupModalLabel">Create Account</h5>
                <button type="button" class="btn-close border-none" id="close-signup-modal" aria-label="Close">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="signup-form" onsubmit="return false;" action="javascript:void(0);" method="post">
                    <!-- First & Last Name Row -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-5">
                        <div class="flex-1">
                            <label for="signup-firstname" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input 
                            type="text" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            id="signup-firstname" 
                            name="firstName" 
                            required
                        >
                        </div>
                        <div class="flex-1">
                            <label for="signup-lastname" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input 
                            type="text" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            id="signup-lastname" 
                            name="lastName" 
                            required
                        >
                        </div>
                    </div>
            
                    <!-- Email -->
                    <div class="mb-5">
                        <label for="signup-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input 
                            type="email" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            id="signup-email" 
                            name="email" 
                            required
                        >
                    </div>
            
                    <!-- Username -->
                    <div class="mb-5">
                        <label for="signup-username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input 
                            type="text" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                            id="signup-username" 
                            name="username" 
                            required
                            minlength="3"
                            maxlength="20"
                            pattern="[a-zA-Z0-9_]+"
                            title="Username can only contain letters, numbers, and underscores"
                        >
                        <div id="username-validation-hint" class="flex items-center text-sm mt-1" style="display: none;"></div>
                    </div>
            
                    <!-- Password -->
                    <div class="mb-5 relative">
                        <label for="signup-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input 
                                type="password" 
                                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                id="signup-password" 
                                name="password" 
                                required
                                aria-describedby="password-strength-tooltip"
                                >
                            <button 
                                class="absolute inset-y-0 right-0 flex items-center pr-3" 
                                type="button" 
                                id="toggle-signup-password">
                                <i data-lucide="eye" class="w-4 h-4 text-gray-500"></i>
                            </button>
                        </div>
                
                        <!-- Password Strength Container -->
                        <div id="passwordStrength" class="hidden">
                            <!-- Password Strength Indicator -->
                            <div id="passwordStrengthIndicator" class="mt-2">
                                <div class="flex items-center gap-x-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div 
                                        id="password-strength-bar" 
                                        class="bg-red-500 h-2 rounded-full transition-all duration-300" 
                                        style="width: 0%"
                                        role="progressbar"
                                        aria-valuenow="0"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                        aria-label="Password strength meter"
                                        ></div>
                                    </div>
                                    <span 
                                        id="password-strength-text" 
                                        class="text-sm font-medium text-red-500"
                                        aria-live="polite"
                                    >
                                        Very weak
                                    </span>
                                </div>
                            </div>

                            <!-- Password Strength Hints -->
                            <div id="passwordStrengthHints" class="mt-3">
                                <div class="bg-white border border-gray-200 rounded-lg shadow-xl p-4">
                                    <h4 class="text-sm font-semibold text-gray-800 mb-2">Your password must contain:</h4>
                                    <ul class="space-y-1 text-sm text-gray-600">
                                        <li class="flex items-center gap-x-2" data-rule="length">
                                            <i data-lucide="x" class="w-4 h-4 text-gray-400 transition-colors duration-200"></i>
                                            <span>At least 8 characters</span>
                                        </li>
                                        <li class="flex items-center gap-x-2" data-rule="lowercase">
                                            <i data-lucide="x" class="w-4 h-4 text-gray-400 transition-colors duration-200"></i>
                                            <span>At least one lowercase letter</span>
                                        </li>
                                        <li class="flex items-center gap-x-2" data-rule="uppercase">
                                            <i data-lucide="x" class="w-4 h-4 text-gray-400 transition-colors duration-200"></i>
                                            <span>At least one uppercase letter</span>
                                        </li>
                                        <li class="flex items-center gap-x-2" data-rule="number">
                                            <i data-lucide="x" class="w-4 h-4 text-gray-400 transition-colors duration-200"></i>
                                            <span>At least one number</span>
                                        </li>
                                        <li class="flex items-center gap-x-2" data-rule="special">
                                            <i data-lucide="x" class="w-4 h-4 text-gray-400 transition-colors duration-200"></i>
                                            <span>At least one special character</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- Confirm Password -->
                    <div class="mb-6" id="confirm-password-group">
                        <label for="signup-confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                id="signup-confirm-password" 
                                name="confirmPassword"
                            >
                            <button 
                                class="absolute inset-y-0 right-0 flex items-center pr-3" 
                                type="button" 
                                id="toggle-confirm-password"
                            >
                                <i data-lucide="eye" class="w-4 h-4 text-gray-500"></i>
                            </button>
                        </div>
                        <div class="text-red-500 text-sm mt-1" id="password-match-error"></div>
                    </div>
            
                    <!-- Submit Button -->
                    <div class="mb-4">
                        <button 
                            type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50" 
                            id="signup-submit" 
                            disabled>
                            Create Account
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-8 text-sm">
                    <p class="mb-0">Already have an account? 
                        <a href="#" class="underline" id="switch-to-login">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
