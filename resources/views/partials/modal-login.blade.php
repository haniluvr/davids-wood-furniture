<!-- Login Modal -->
<div class="modal fade hidden" id="modal-login" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header flex items-center justify-between">
                <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
                <button type="button" class="btn-close border-none" id="close-login-modal" aria-label="Close">
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="login-form">
                    <!-- Username -->
                    <div class="mb-5">
                      <label for="login-username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                      <input 
                        type="text" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        id="login-username" 
                        name="username" 
                        required
                      >
                    </div>
            
                    <!-- Password -->
                    <div class="mb-5">
                      <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                      <div class="relative">
                        <input 
                          type="password" 
                          class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                          id="login-password" 
                          name="password" 
                          required
                        >
                        <button 
                          class="absolute inset-y-0 right-0 flex items-center pr-3" 
                          type="button" 
                          id="toggle-login-password"
                        >
                          <i data-lucide="eye" class="w-4 h-4 text-gray-500"></i>
                        </button>
                      </div>
                    </div>
            
                    <!-- Keep me logged in -->
                    <div class="flex items-center mb-6">
                      <input 
                        type="checkbox" 
                        id="keep-logged-in" 
                        name="keepLoggedIn"
                        class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500"
                      >
                      <label for="keep-logged-in" class="ml-2 text-sm text-gray-600">
                        Keep me logged in
                      </label>
                    </div>
            
                    <!-- Submit Button -->
                    <div class="mb-4">
                      <button 
                        type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200"
                      >
                        Sign In
                      </button>
                    </div>
                  </form>
                
                <!-- Signup Switch Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account? 
                        <a href="#" id="switch-to-signup" class="text-blue-600 font-medium hover:text-blue-800 underline">
                            Create one here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
