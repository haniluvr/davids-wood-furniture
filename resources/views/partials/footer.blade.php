<!-- footer -->
<footer class="footer" id="contact">
    <div class="w-full px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-14">
            <!-- Column 1: Brand Info -->
            <div class="w-full">
                <div class="relative flex items-center gap-3">
                    <div class="relative">
                      <img 
                        src="{{ asset('frontend/assets/favicon.png') }}" 
                        alt="David's Wood" 
                        class="w-10 h-10 drop-shadow-md"
                        draggable="false"
                      >
                      <!-- Glossy highlight -->
                      <div class="absolute inset-0 bg-gradient-to-t from-transparent via-white/20 to-transparent pointer-events-none"></div>
                    </div>
                    <h2 class="logo font-semibold leading-none text-lg text-gray-700">DAVID'S WOOD <br>FURNITURES</h2>
                  </div>
                <p class="brand-desc mt-2">
                    Handcrafteded furniture with timeless design. Natural materials, thoughtful details, built to last.
                </p>
                <div class="contact-info">
                    <div class="info-item flex items-center">
                        <i data-lucide="phone-call" class="icon"></i>
                        <span>+1 (234) 555-1234</span>
                    </div>
                    <div class="info-item flex items-center">
                        <i data-lucide="contact" class="icon"></i>
                        <span>hello@davidswood.com</span>
                    </div>
                    <div class="info-item flex flex-col">
                        <div class="flex items-center">
                            <i data-lucide="map-pin" class="icon"></i>
                            <span>
                                <strong>Showroom & Workshop</strong><br>
                            </span>
                        </div>
                        <span class="text-span">
                            245 Cedar Lane, Suite B<br>
                            Portland, OR 97205
                        </span>
                    </div>
                    <div class="info-item flex flex-col">
                        <div class="flex items-center">
                            <i data-lucide="clock" class="icon"></i>
                            <span>
                                <strong>Hours</strong><br>
                            </span>
                        </div>
                        <span class="text-span">
                            Mon-Fri: 9am - 6pm<br>
                            Sat: 10am - 4pm
                        </span>
                    </div>
                </div>
                <div class="social-links">
                    <a href="#" aria-label="LinkedIn"><i data-lucide="linkedin" class="icon"></i></a>
                    <a href="#" aria-label="Instagram"><i data-lucide="instagram" class="icon"></i></a>
                    <a href="#" aria-label="Facebook"><i data-lucide="facebook" class="icon"></i></a>
                    <a href="#" aria-label="Twitter"><i data-lucide="twitter" class="icon"></i></a>
                </div>
            </div>
            <!-- Column 2: Contact Form -->
            <div class="w-full">
                <h3 class="text-lg font-semibold mb-2">Contact us</h3>
                <p class="form-desc">We'll respond within 1–2 business days.</p>
                <form id="contact-form" action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="w-full flex flex-col">
                            <label for="contact-name">Name</label>
                            <input type="text" id="contact-name" name="name" placeholder="Jane Doe" required value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}">
                        </div>
                        <div class="w-full flex flex-col">
                            <label for="contact-email">Email</label>
                            <input type="email" id="contact-email" name="email" placeholder="jane@example.com" required value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}">
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="contact-message">Message</label>
                        <textarea id="contact-message" name="message" placeholder="Tell us about your project..." rows="4" required>{{ old('message') }}</textarea>
                    </div>
                    <div id="contact-form-message" class="mt-2 text-sm" style="display: none;"></div>
                    <div class="form-footer">
                        <span class="disclaimer">By sending, you agree to our policies below.</span>
                        <button type="submit" class="btn-send flex items-center">
                          <i data-lucide="send" class="icon mr-2"></i> 
                          <span class="text-sm">Send message</span>
                        </button>
                      </div>
                </form>
            </div>
            <!-- Column 3: Newsletter & Customer Care -->
            <div class="w-full">
                <h3 class="text-lg font-semibold mb-2">Newsletter</h3>
                <p class="form-desc">Be first to see new pieces, wood stories, and events.</p>
                <div class="newsletter-form">
                    <input type="email" placeholder="you@example.com" required>
                    <button type="submit" class="text-sm btn-subscribe">Subscribe</button>
                </div>
                <hr class="divider">
                <div class="customer-care text-sm">
                    <h3 class="text-lg font-semibold mb-2">Customer care</h3>
                    <ul>
                        <li><a href="#">Shipping information</a></li>
                        <li><a href="#">Returns & exchanges</a></li>
                        <li><a href="#">Care & maintenance</a></li>
                        <li><a href="#">Custom orders</a></li>
                    </ul>
                </div>
            </div>
            <!-- Column 4: Company & Policies -->
            <div class="w-full col-links text-sm">
                <h3 class="text-lg font-semibold mb-2">Company</h3>
                <ul>
                    <li><a href="#">About David's Wood</a></li>
                    <li><a href="#">Journal</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <hr class="divider">
                <h3 class="text-lg font-semibold mb-2">Policies</h3>
                <ul>
                    <li><a href="#">Privacy policy</a></li>
                    <li><a href="#">Terms of service</a></li>
                    <li><a href="#">Warranty</a></li>
                </ul>
            </div>
        </div>
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p class="copyright">© 2025 David's Wood. All rights reserved.</p>
            <div class="bottom-links">
            <span class="sustainability">
                <i data-lucide="circle" class="icon green"></i>
                Crafted with sustainable materials
            </span>
            <a href="#" class="call-us">
                <i data-lucide="phone-call" class="icon"></i> Call us
            </a>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const messageDiv = document.getElementById('contact-form-message');
            const originalBtnText = submitBtn.innerHTML;
            
            // Disable submit button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i data-lucide="loader" class="icon mr-2 animate-spin"></i> <span class="text-sm">Sending...</span>';
            
            // Reinitialize icons for spinner
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
            // Send AJAX request
            fetch('{{ route('contact.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'mt-2 text-sm text-green-600';
                    messageDiv.style.display = 'block';
                    
                    // Reset form
                    contactForm.reset();
                    
                    // Hide message after 5 seconds
                    setTimeout(() => {
                        messageDiv.style.display = 'none';
                    }, 5000);
                } else {
                    // Show error message
                    messageDiv.textContent = data.message || 'An error occurred. Please try again.';
                    messageDiv.className = 'mt-2 text-sm text-red-600';
                    messageDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.textContent = 'An error occurred. Please try again later.';
                messageDiv.className = 'mt-2 text-sm text-red-600';
                messageDiv.style.display = 'block';
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                // Reinitialize icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        });
    }
});
</script>
