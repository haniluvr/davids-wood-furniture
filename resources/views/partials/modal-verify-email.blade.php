<!-- Email Verification Modal -->
<div id="modal-verify-email" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Close button -->
            <div class="flex justify-end">
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeVerifyEmailModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal content -->
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-3">Check Your Email</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">We've sent you a verification link to complete your registration</p>

                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-md text-left">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <h4 class="text-sm font-medium text-green-800">Almost there!</h4>
                        </div>
                        <ul class="mt-2 text-sm text-green-700 list-disc pl-5 space-y-1">
                            <li>Check your email inbox (and spam folder)</li>
                            <li>Click the verification link in the email</li>
                            <li>You'll be automatically logged in</li>
                            <li>Start exploring our beautiful furniture collection</li>
                        </ul>
                    </div>

                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md text-left">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <h4 class="text-sm font-medium text-blue-800">Didn't receive the email?</h4>
                        </div>
                        <p class="mt-2 text-sm text-blue-700">
                            Check your spam folder or try registering again. The verification link expires in 1 hour.
                        </p>
                    </div>

                    <div class="mt-6">
                        <form id="resend-verification-form-modal" class="space-y-4">
                            @csrf
                            <div>
                                <label for="email-modal" class="block text-sm font-medium text-gray-700">
                                    Didn't receive the email? Enter your email to resend
                                </label>
                                <div class="mt-1">
                                    <input id="email-modal" name="email" type="email" required
                                           class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-orange-500 focus:border-orange-500 focus:z-10 sm:text-sm"
                                           placeholder="Enter your email address">
                                </div>
                            </div>
                            <div>
                                <button type="submit"
                                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Resend Verification Email
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="text-center mt-4">
                        <p class="text-sm text-gray-500">
                            Already verified?
                            <a href="{{ route('login') }}" class="font-medium text-orange-600 hover:text-orange-500" onclick="closeVerifyEmailModal()">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function closeVerifyEmailModal() {
    document.getElementById('modal-verify-email').classList.add('hidden');
}

// Handle resend verification form
document.addEventListener('DOMContentLoaded', function() {
    const resendForm = document.getElementById('resend-verification-form-modal');
    if (resendForm) {
        resendForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const email = formData.get('email');
            
            try {
                const response = await fetch('{{ route("auth.resend-verification") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Verification email sent! Please check your inbox.');
                } else {
                    alert('Error: ' + (result.message || 'Failed to send verification email'));
                }
            } catch (error) {
                console.error('Error sending verification email:', error);
                alert('Error sending verification email. Please try again.');
            }
        });
    }
});
</script>
