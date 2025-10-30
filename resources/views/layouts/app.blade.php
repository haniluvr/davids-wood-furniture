<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'David\'s Wood Furniture - Handcrafteded furniture with timeless design.')</title>
        
        <!-- css -->
        <link rel="icon" type="image/x-icon" href="{{ asset('frontend/assets/favicon.png') }}">
        <link rel="stylesheet" href="{{ asset('frontend/style.css') }}">
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        
        <!-- scripts -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js" defer></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" defer></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
        <script src='https://www.noupe.com/embed/019a1fbba6967b649fb5061d5d5f459ea1ea.js'></script>
        <!-- Preline UI -->
        <script src="https://cdn.jsdelivr.net/npm/preline@2.0.3/dist/preline.min.js" defer></script>
        
        <!-- Lazy Loading Styles -->
        <style>
            .lazy-load {
                opacity: 0;
                transition: opacity 0.3s;
            }
            .lazy-load.loaded {
                opacity: 1;
            }
        </style>
        
        @stack('styles')
        <meta name="storage-base-url" content="{{
            config('filesystems.disks.s3.url')
                ?: ('https://' . config('filesystems.disks.s3.bucket') . '.s3.' . config('filesystems.disks.s3.region') . '.amazonaws.com')
        }}">
    </head>
    <body>
        <!-- navbar -->
        @include('partials.navbar')
        
        <!-- search modal -->
        @include('partials.modal-search')
        @include('partials.modal-login')
        @include('partials.modal-signup')
        @include('partials.modal-alert')
        @include('partials.modal-verify-email')
        
        <!-- wishlist offcanvas -->
        @include('partials.offcanvas-wishlist')
        <!-- cart offcanvas-->
        @include('partials.offcanvas-cart')
        <!-- notification offcanvas -->
        @include('partials.offcanvas-notification')
        <!-- quick view modal-->
        @include('partials.modal-quick-view')
        
        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
        
        <!-- footer -->
        @include('partials.footer')
        
        <!-- scripts -->
        <script src="{{ asset('frontend/js/config.js') }}"></script>
        <script src="{{ asset('frontend/js/api.js') }}"></script>
        <script src="{{ asset('frontend/js/auth.js') }}"></script>
        <script src="{{ asset('frontend/js/app.js') }}?v={{ time() }}"></script>
        <script>
        </script>
        <script>
            // Main JavaScript functionality
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize icons
                setTimeout(function() {
                    if (typeof lucide !== 'undefined' && lucide.createIcons) {
                        lucide.createIcons();
                    }
                    
                    
                    if (typeof AOS !== 'undefined') {
                        AOS.init({
                            duration: 450,
                            once: true,
                            offset: 20,
                            delay: 0,
                            easing: 'ease-in-out',
                            anchorPlacement: 'top-center',
                            startEvent: 'DOMContentLoaded',
                            disableMutationObserver: false,
                            mirror: false
                        });
                    }
                    
                    if (typeof HSCore !== 'undefined' && HSCore.components) {
                        HSCore.components();
                    }
                }, 200);

                // Delay modal initialization to ensure all components are loaded
                setTimeout(() => {
                    // Initialize modes AND offcanvas
                    initializeModalAndOffcanvas();
                    
                    // Initialize navbar functionality
                    initializeNavbarFunctionality();
                    
                    // Initialize scrolling
                    initializeAnchorScrolling();

                    // Initialize password strength validation
                    initializePasswordStrengthValidation();
                    
                    // Initialize hero slider
                    initializeHeroSlider();
                    
                    // Check if modal buttons exist
                    const searchBtn = document.getElementById('openSearchModal');
                    const loginBtn = document.getElementById('open-login-modal');
                    const signupBtn = document.getElementById('open-signup-modal');
                }, 200);
            });

            // Initialize Hero Slider
            function initializeHeroSlider() {
                let currentIndex = 0;
                const $slides = $('.slide');
                const $indicators = $('.indicator');
                let autoSlideInterval;

                // Check if jQuery and elements exist
                if (!$slides.length || !$indicators.length) {
                    return;
                }

                function showSlide(index) {
                    $slides.removeClass('active');
                    $indicators.removeClass('active');

                    $slides.eq(index).addClass('active');
                    $indicators.eq(index).addClass('active');

                    // Update any text overlays if they exist
                    const currentSlide = $slides.eq(index);
                    $('#slide-title').text(currentSlide.data('name'));
                    $('#slide-desc').text(currentSlide.data('price'));
                }

                function nextSlide() {
                    currentIndex = (currentIndex + 1) % $slides.length;
                    showSlide(currentIndex);
                }

                function startAutoSlide() {
                    autoSlideInterval = setInterval(nextSlide, 2000); // 2 second interval like original
                }

                function stopAutoSlide() {
                    clearInterval(autoSlideInterval);
                }

                // Only start auto-slide if we have more than one slide
                if ($slides.length > 1) {
                    startAutoSlide();
                }

                // Pause on hover
                $('.hero-slider').hover(
                    function() { stopAutoSlide(); },
                    function() { startAutoSlide(); }
                );

                // Dot click handlers
                $indicators.click(function() {
                    currentIndex = $(this).index();
                    showSlide(currentIndex);
                });

                // Initialize
                showSlide(currentIndex);
            }

            // Fix smooth scrolling for anchor links
            function initializeAnchorScrolling() {
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        const targetId = this.getAttribute('href');
                        if (targetId && targetId !== '#') {
                            e.preventDefault();
                            const targetElement = document.querySelector(targetId);
                            if (targetElement) {
                                targetElement.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        }
                    });
                });
            }

            // Initialize all modals and offcanvas components
            function initializeModalAndOffcanvas() {
                // Initialize all modals
                initModal('modal-search');
                initModal('modal-login');
                initModal('modal-signup');
                initModal('modal-quick-view');
                
                // Initialize all offcanvas
                initOffcanvas('offcanvas-wishlist');
                initOffcanvas('offcanvas-cart');
                initOffcanvas('offcanvas-notification');
            }

            // Modal and Offcanvas initialization functions
            // Signup Modal Events Function
            function initSignupModalEvents(modalElement) {
                
                // Get password field and confirm password group
                const passwordField = modalElement.querySelector('#signup-password');
                const confirmPasswordField = modalElement.querySelector('#signup-confirm-password');
                const confirmPasswordGroup = modalElement.querySelector('#confirm-password-group');
                const passwordMatchError = modalElement.querySelector('#password-match-error');
                
                if (passwordField && confirmPasswordGroup) {
                    // Show confirm password group ONLY when user starts typing (not on focus)
                    passwordField.addEventListener('input', function() {
                        if (passwordField.value.length > 0) {
                            confirmPasswordGroup.classList.add('show');
                        }
                    });
                    
                    // Dynamic Password Feedback System
                    const passwordStrengthIndicator = modalElement.querySelector('#passwordStrengthIndicator');
                    const passwordStrengthHints = modalElement.querySelector('#passwordStrengthHints');
                    const strengthBar = modalElement.querySelector('#password-strength-bar');
                    const strengthText = modalElement.querySelector('#password-strength-text');
                    
                    // Track focus state to ensure UI is show/hide correctly
                    let passwordFieldActive = false;
                    
                    // FUNCTION-1: Show password feedback (progress & hints popover) **only when focused/active**
                    passwordField.addEventListener('focus', function() {
                        passwordFieldActive = true;
                        
                        // Reveal the password strength UI
                        if (passwordStrengthIndicator) {
                            passwordStrengthIndicator.classList.remove('hidden');
                            passwordStrengthIndicator.style.opacity = '1';
                            passwordStrengthIndicator.style.display = 'block';
                        }
                        
                        if (passwordStrengthHints) {
                            passwordStrengthHints.classList.remove('hidden');
                            passwordStrengthHints.style.opacity = '1';
                            passwordStrengthHints.style.display = 'block';
                        }
                        
                        // Initialize Preline components for new visible elements
                        setTimeout(() => {
                            if (window.HSCore && window.HSCore.components) {
                                window.HSCore.components();
                            }
                        }, 50);
                    });
                    
                    // FUNCTION-2: Hide password feedback elements on blur (leave when inactive)
                    passwordField.addEventListener('blur', function() {
                        passwordFieldActive = false;
                        
                        // Hide both progress bar and hints popover
                        if (passwordStrengthIndicator) {
                            passwordStrengthIndicator.classList.add('hidden');
                            passwordStrengthIndicator.style.opacity = '0';
                            passwordStrengthIndicator.style.display = 'none';
                        }
                        
                        if (passwordStrengthHints) {
                            passwordStrengthHints.classList.add('hidden');
                            passwordStrengthHints.style.opacity = '0';
                            passwordStrengthHints.style.display = 'none';
                        }
                    });
                    
                    // FUNCTION-3: Real-time password validation feedback updates
                    passwordField.addEventListener('input', function() {
                        if (!passwordFieldActive) return;  // Only scan while focused
                        
                        // Trigger Preline re-evaluation as user types
                        if (window.HSCore && window.HSCore.components) {
                            window.HSCore.components();
                        }
                    });
                    
                    // FUNCTION-4: Apply ARIA accessibility attributes for screen readers
                    if (passwordStrengthIndicator) {
                        passwordStrengthIndicator.setAttribute('role', 'progressbar');
                        passwordStrengthIndicator.setAttribute('aria-valuenow', '0');
                        passwordStrengthIndicator.setAttribute('aria-valuemin', '0');
                        passwordStrengthIndicator.setAttribute('aria-valuemax', '100');
                        passwordStrengthIndicator.setAttribute('aria-label', 'Password strength level');
                    }
                }
                
                // Password confirmation validation + SUBMIT BUTTON ENABLING
                if (passwordField && confirmPasswordField && passwordMatchError) {
                    const signupSubmit = modalElement.querySelector('#signup-submit');
                    
                    function validatePasswordMatch() {
                        const password = passwordField.value.trim();
                        const confirmPassword = confirmPasswordField.value.trim();
                        
                        // Also get other required fields
                        const firstName = modalElement.querySelector('#signup-firstname');
                        const lastName = modalElement.querySelector('#signup-lastname');
                        const email = modalElement.querySelector('#signup-email');
                        const username = modalElement.querySelector('#signup-username');
                        
                        // Validate password match state
                        if (confirmPassword.length > 0) {
                            if (password !== confirmPassword) {
                                passwordMatchError.textContent = 'Passwords do not match';
                                passwordMatchError.style.opacity = '1';
                                // Disable button on mismatch
                                if (signupSubmit) {
                                    signupSubmit.disabled = true;
                                    signupSubmit.classList.add('opacity-50');
                                }
                                return false;
                            } else {
                                passwordMatchError.textContent = '';
                                passwordMatchError.style.opacity = '0';
                                // Check for full validation: passwords match + all fields filled + email valid + minimum password length
                                if (firstName.value.trim() && lastName.value.trim() && email.value.includes('@') && email.value.includes('.') && username.value.trim() && password.length >= 8) {
                                    if (signupSubmit) {
                                        signupSubmit.disabled = false;
                                        signupSubmit.classList.remove('opacity-50');
                                        signupSubmit.style.opacity = '1';
                                    }
                                } else {
                                    if (signupSubmit) {
                                        signupSubmit.disabled = true;
                                        signupSubmit.classList.add('opacity-50');
                                    }
                                }
                                return true;
                            }
                        } else {
                            passwordMatchError.textContent = '';
                            passwordMatchError.style.opacity = '0';
                            if (signupSubmit) {
                                signupSubmit.disabled = true;
                                signupSubmit.classList.add('opacity-50');
                            }
                            return null; // No validation needed yet
                        }
                    }
                    
                    // Add event listeners for validation
                    passwordField.addEventListener('input', validatePasswordMatch);
                    confirmPasswordField.addEventListener('input', validatePasswordMatch);
                    
                    // Listen for changes to all form fields to enable temporary inspection
                    const firstname = modalElement.querySelector('#signup-firstname');
                    const lastname = modalElement.querySelector('#signup-lastname');
                    const username = modalElement.querySelector('#signup-username');
                    const email = modalElement.querySelector('#signup-email');
                    
                    const allFields = [firstname, lastname, email, username];
                    allFields.forEach(field => {
                        if (field) {
                            field.addEventListener('input', function(e) {
                                // Re-validate on other field changes MAY cause more immediate enable, however SIMMERING LONGER interval check overall:
                                setTimeout(() => validatePasswordMatch(), 10);
                            });
                        }
                    });
                }
                
                // Registration form submission is now handled in auth.js
                // No duplicate listener needed here
                
                // Re-initialize Lucide icons after modal opens
                setTimeout(() => {
                    if (typeof lucide !== 'undefined' && lucide.createIcons) {
                        lucide.createIcons();
                    }
                }, 200);
            }

            function initModal(id) {
                const el = document.getElementById(id);
                if (!el) {
                    return;
                }

                const simpleName = id.replace('modal-', '').replace(/-/g, '');
                const idBasedName = id.replace(/-/g, '');
                
                // Look for different close button formats
                let closeBtn = document.getElementById('close-' + simpleName + '-modal');
                if (!closeBtn && id === 'modal-quick-view') {
                    closeBtn = document.getElementById('close-quickview-modal');
                }

                const show = function() { 
                    
                    // For quick-view modal with different structure
                    if (id === 'modal-quick-view') {
                        el.classList.remove('hidden');
                        el.style.display = 'flex'; // It uses flex layout
                        el.style.opacity = '0';
                        setTimeout(() => {
                            el.style.transition = 'opacity 0.3s ease';
                            el.style.opacity = '1';
                        }, 10);
                    } else {
                        // STEP 1: Set current state
                        
                        // STEP 2: Force the modal container open with fade-in animation
                        el.setAttribute('aria-hidden', 'false');
                        el.classList.remove('hidden');
                        el.style.setProperty('display', 'flex', 'important');
                        el.style.setProperty('opacity', '0', 'important');
                        el.style.setProperty('visibility', 'visible', 'important');
                        el.style.setProperty('z-index', '1050', 'important');
                        el.style.setProperty('transition', 'opacity 0.3s ease, visibility 0.3s ease', 'important');
                        
                        // Trigger fade-in animation
                        setTimeout(() => {
                            el.classList.add('show');
                            el.style.setProperty('opacity', '1', 'important');
                        }, 10);
                        
                        // STEP 3: Let modal-dialog use your custom CSS classes
                        const modalDialog = el.querySelector('.modal-dialog');
                        if (modalDialog) {
                            // Remove any JS overrides, let CSS take control
                            modalDialog.style.cssText = '';
                            modalDialog.classList.add('modal-dialog-centered');
                        }
                        
                        // STEP 4: Let modal-content use your custom CSS classes  
                        const modalContent = el.querySelector('.modal-content');
                        if (modalContent) {
                            // Remove any JS overrides, let CSS take control
                            modalContent.style.cssText = '';
                        }
                        
                        // STEP 5: Verify final state
                        
                        // STEP 6: Initialize Preline components
                        setTimeout(() => {
                            // Initialize all Preline components and especially leave strong password for Preline's API
                            if (typeof HSCore !== 'undefined' && HSCore.components) {
                                // This is the magic - it needs to run in modal context to catch new/moved DOM nodes within the open modal
                                HSCore.components();
                                
                                // For signup modal a slight delay helps Preline "see" the modal content
                                if (id === 'modal-signup') {
                                    setTimeout(() => {
                                        // Simply call HSCore.components again now with the modal DOM subtree available:
                                        HSCore.components();
                                        
                                        // Additional direct registration of password field
                                        const strongPasswordInput = el.querySelector('#signup-password');
                                        if (strongPasswordInput && strongPasswordInput.dataset && strongPasswordInput.dataset.hsStrongPassword) {
                                            // Provide elements visibility for Preline side detection 
                                            const indicator = el.querySelector('#passwordStrengthIndicator');
                                            const hints = el.querySelector('#passwordStrengthHints');
                                            
                                            // Preload ensure visibility
 
                                            // Important: ONLY after HSCore has registered
                                            // but leaving these elements visible UNTIL user types triggers a new component binding    
                                            // So there is no element inertia when HSCore looks 
                                            if (indicator) {
                                                indicator.classList.remove('hidden'); 
                                                indicator.style.opacity = '1';
                                            }
                                            if (hints) {
                                                hints.classList.remove('hidden');
                                                hints.style.opacity = '1';
                                            }
                                            
                                            // Forcibly bring the input's preline attributes into attention
                                            strongPasswordInput.focus();
                                            strongPasswordInput.blur();
                                            
                                            // Fire direct preline strong password event  
                                            const event = new Event('input');
                                            strongPasswordInput.dispatchEvent(event);
                                        }
                                    }, 200);
                                }
                            }
                            
                            // Add Event Listeners for Signup Modal functionality
                            if (id === 'modal-signup') {
                                initSignupModalEvents(el);
                                
                                // CRITICAL: Force Preline strong password initialization after modal is visible
                                setTimeout(() => {
                                    const passwordInput = el.querySelector('#signup-password');
                                    if (passwordInput) {
                                        // Play event so Preline can start monitoring the input field
                                        const event = new Event('input', { bubbles: true });
                                        passwordInput.dispatchEvent(event);
                                    }
                                }, 300);
                            }
                            
                            el.focus();
                        }, 200);
                    }
                };

                const hide = function() { 
                    // Fade out with proper CSS classes
                    el.classList.remove('show');
                    el.style.transition = 'opacity 0.3s ease, visibility 0.3s ease';
                    el.style.opacity = '0';
                    el.style.visibility = 'hidden';
                    
                    setTimeout(() => {
                        el.style.display = 'none';
                        el.classList.add('hidden');
                    }, 300);
                };

                // Create global function references
                window['show' + simpleName] = show;
                window['hide' + simpleName] = hide;
                window['show' + idBasedName] = show;
                window['hide' + idBasedName] = hide;

                if (closeBtn) {
                    closeBtn.addEventListener('click', hide);
                }

                // Backdrop click to close - better detection
                el.addEventListener('click', function(e) {
                    // Only close if clicking the modal backdrop (not the content)
                    if (e.target === el || (e.target.classList.contains('modal') && e.target === el)) {
                        hide();
                    }
                });
                
                // Also prevent clicks on modal-dialog from closing
                const modalDialog = el.querySelector('.modal-dialog');
                if (modalDialog) {
                    modalDialog.addEventListener('click', function(e) {
                        e.stopPropagation(); // Prevent dialog clicks from bubbling to modal backdrop
                    });
                }
                
                // ESC key support
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && el.style.display !== 'none' && !el.classList.contains('hidden')) {
                        hide();
                    }
                });

            }

            function initOffcanvas(id) {
                const el = document.getElementById(id);
                const panel = document.getElementById(id + '-panel');
                if (!el || !panel) return;

                const simpleName = id.replace('offcanvas-', '').replace(/-/g, '');
                const idBasedName = id.replace(/-/g, '');
                const closeBtn = document.getElementById('close-' + simpleName + '-offcanvas');

                const show = function() {
                    el.style.display = 'block';
                    el.classList.remove('hidden');
                    
                    // Ensure panel starts positioned correctly
                    panel.classList.remove('translate-x-0');
                    panel.classList.add('translate-x-full');
                    
                    // Trigger slide-in animation after small delay
                    setTimeout(() => {
                        panel.classList.remove('translate-x-full');
                        panel.classList.add('translate-x-0');
                    }, 10);
                    
                    // Prevent body scrolling when offcanvas is open
                    // Store the current scrollbar width to prevent layout shift
                    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
                    document.body.style.overflow = 'hidden';
                    document.body.style.paddingRight = scrollbarWidth + 'px';
                };

                const hide = function() {
                    // Clear any opacity/transition styles that might interfere
                    el.style.opacity = '';
                    el.style.transition = '';
                    
                    // Ensure panel starts in visible position
                    panel.classList.remove('translate-x-full');
                    panel.classList.add('translate-x-0');
                    
                    // Force a repaint to ensure current position is set
                    panel.offsetHeight;
                    
                    // Now trigger the slide-out animation
                    setTimeout(() => {
                        // Use direct CSS transforms for guaranteed animation
                        panel.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                        panel.style.transform = 'translateX(0)';
                        
                        // Force a tiny delay to ensure the base position is set
                        requestAnimationFrame(() => {
                            panel.style.transform = 'translateX(100%)';
                        });
                    }, 10);
                    
                    // Hide the element entirely after slide animation completes
                    setTimeout(() => {
                        // Reset CSS now that animation is complete
                        panel.style.transition = '';
                        panel.style.transform = '';
                        el.style.display = 'none';
                        el.classList.add('hidden');
                        // Reset for next opening
                        panel.classList.remove('translate-x-full');
                        // Restore body scrolling when offcanvas is closed
                        document.body.style.overflow = 'auto';
                        document.body.style.paddingRight = '';
                    }, 300);
                };

                window['show' + simpleName] = show;
                window['hide' + simpleName] = hide;
                window['show' + idBasedName] = show;
                window['hide' + idBasedName] = hide;

                if (closeBtn) {
                    closeBtn.addEventListener('click', hide);
                }

                el.addEventListener('click', function (event) {
                    if (event.target === el) {
                        hide();
                    }
                });

            }

            // Navbar functionality
            function initializeNavbarFunctionality() {
                // Search Modal Button
                const openSearchBtn = document.getElementById('openSearchModal');
                
                if (openSearchBtn) {
                    openSearchBtn.addEventListener('click', function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        
                        // Try different function variations
                        if (typeof window.showmodalsearch === 'function') {
                            window.showmodalsearch();
                        } else if (typeof window.showsearch === 'function') {
                            window.showsearch();
                        } else {
                            // Fallback: manually show element
                            const modal = document.getElementById('modal-search');
                            if (modal) {
                                modal.classList.remove('hidden');
                                modal.style.display = 'block';
                                modal.style.opacity = '1';
                            }
                        }
                    });
                }

                // Wishlist Offcanvas Button
                const openWishlistBtn = document.getElementById('openOffcanvas');
                if (openWishlistBtn) {
                    openWishlistBtn.addEventListener('click', function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        if (typeof window.showoffcanvaswishlist === 'function') {
                            window.showoffcanvaswishlist();
                        }
                    });
                }

                // Cart Offcanvas Button
                const openCartBtn = document.getElementById('openCartOffcanvas');
                if (openCartBtn) {
                    openCartBtn.addEventListener('click', function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        if (typeof window.showoffcanvascart === 'function') {
                            window.showoffcanvascart();
                        }
                    });
                }

                // Account dropdown functionality - MOVED TO app.js
                // This functionality has been moved to the main app.js file
                // to avoid conflicts and ensure proper dropdown behavior.

                // Login and Signup Modal Buttons
                const openLoginBtn = document.getElementById('open-login-modal');
                const openSignupBtn = document.getElementById('open-signup-modal');
                const accountMenu = document.getElementById('account-menu');
                
                if (openLoginBtn) {
                    openLoginBtn.addEventListener('click', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        
                        // Try multiple function name variations
                        if (typeof window.showmodallogin === 'function') {
                            window.showmodallogin();
                        } else if (typeof window.showlogin === 'function') {
                            window.showlogin();
                        } else {
                            // Fallback: manually show login modal
                            const modal = document.getElementById('modal-login');
                            if (modal) {
                                modal.classList.remove('hidden');
                                modal.style.display = 'block';
                                modal.style.opacity = '1';
                                modal.style.position = 'fixed';
                                modal.style.top = '0';
                                modal.style.left = '0';
                                modal.style.width = '100%';
                                modal.style.height = '100%';
                                modal.style.zIndex = '1050';
                                modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
                            }
                        }
                        
                        if (accountMenu) {
                            accountMenu.style.display = 'none';
                            accountMenu.classList.add('hidden');
                        }
                    });
                }
                
                if (openSignupBtn) {
                    openSignupBtn.addEventListener('click', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        
                        // Try multiple function name variations
                        if (typeof window.showmodalsignup === 'function') {
                            window.showmodalsignup();
                        } else if (typeof window.showsignup === 'function') {
                            window.showsignup();
                        } else {
                            // Fallback: manually show signup modal
                            const modal = document.getElementById('modal-signup');
                            if (modal) {
                                modal.classList.remove('hidden');
                                modal.style.display = 'block';
                                modal.style.opacity = '1';
                                modal.style.position = 'fixed';
                                modal.style.top = '0';
                                modal.style.left = '0';
                                modal.style.width = '100%';
                                modal.style.height = '100%';
                                modal.style.zIndex = '1050';
                                modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
                            }
                        }
                        
                        if (accountMenu) {
                            accountMenu.style.display = 'none';
                            accountMenu.classList.add('hidden');
                        }
                    });
                }
                
                // Logout button is now handled in auth.js
                // No duplicate listener needed here

                // Login form submission is now handled in auth.js
                // No duplicate listener needed here

                // Mobile menu toggle
                const mobileMenuBtn = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');
                if (mobileMenuBtn && mobileMenu) {
                    mobileMenuBtn.addEventListener('click', function() {
                        mobileMenu.classList.toggle('hidden');
                    });
                }

            }

            // Initialize Password Strength Validation
            function initializePasswordStrengthValidation() {
                const passwordInput = document.getElementById('signup-password');
                const passwordStrength = document.getElementById('passwordStrength');
                const strengthIndicator = document.getElementById('passwordStrengthIndicator');
                const strengthBar = document.getElementById('password-strength-bar');
                const strengthText = document.getElementById('password-strength-text');
                const strengthHints = document.getElementById('passwordStrengthHints');
                
                if (!passwordInput || !passwordStrength || !strengthIndicator || !strengthBar || !strengthText || !strengthHints) {
                    return;
                }

                // Password validation rules
                const passwordRules = {
                    length: { test: (pwd) => pwd.length >= 8, text: "At least 8 characters" },
                    lowercase: { test: (pwd) => /[a-z]/.test(pwd), text: "At least one lowercase letter" },
                    uppercase: { test: (pwd) => /[A-Z]/.test(pwd), text: "At least one uppercase letter" },
                    number: { test: (pwd) => /[0-9]/.test(pwd), text: "At least one number" },
                    special: { test: (pwd) => /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(pwd), text: "At least one special character" }
                };

                // Track validation state
                let isFocused = false;
                let validationTimeout = null;

                function showPasswordValidation() {
                    // Show entire password strength container with fold animation
                    passwordStrength.classList.remove('hidden');
                    passwordStrength.setAttribute('aria-hidden', 'false');
                    
                    $(passwordStrength).hide().show('fold', { 
                        duration: 400,
                        easing: 'swing'
                    });
                }

                function hidePasswordValidation() {
                    // Hide entire password strength container with fold animation
                    $(passwordStrength).hide('fold', { 
                        duration: 300,
                        easing: 'swing',
                        complete: function() {
                            passwordStrength.classList.add('hidden');
                            passwordStrength.setAttribute('aria-hidden', 'true');
                        }
                    });
                }

                function updatePasswordStrength(password) {
                    const rules = Object.keys(passwordRules);
                    const metRules = rules.filter(rule => passwordRules[rule].test(password));
                    const score = Math.round((metRules.length / rules.length) * 100);
                    
                    // Update progress bar with smooth animation
                    strengthBar.style.width = `${score}%`;
                    strengthBar.setAttribute('aria-valuenow', score);
                    
                    // Update colors and text based on strength
                    let bgColor = 'bg-red-500';
                    let textColor = 'text-red-500';
                    let strengthTextValue = 'Very weak';
                    
                    if (score === 0) {
                        bgColor = 'bg-red-500';
                        textColor = 'text-red-500';
                        strengthTextValue = 'Very weak';
                    } else if (score < 40) {
                        bgColor = 'bg-orange-500';
                        textColor = 'text-orange-500';
                        strengthTextValue = 'Weak';
                    } else if (score < 60) {
                        bgColor = 'bg-yellow-500';
                        textColor = 'text-yellow-500';
                        strengthTextValue = 'Fair';
                    } else if (score < 80) {
                        bgColor = 'bg-blue-500';
                        textColor = 'text-blue-500';
                        strengthTextValue = 'Good';
                    } else {
                        bgColor = 'bg-green-500';
                        textColor = 'text-green-500';
                        strengthTextValue = 'Strong';
                    }

                    // Apply the color updates
                    strengthBar.className = `${bgColor} h-2 rounded-full transition-all duration-300`;
                    strengthText.textContent = strengthTextValue;
                    strengthText.className = `text-sm font-medium ${textColor}`;

                    // Update all rule indicators based on input state
                    if (!strengthHints) {
                        return;
                    }
                    
                    // Call the icon updating logic with safeguarding for timing
                    updatePasswordIcons(password, rules);
                }
                
                function updatePasswordIcons(password, rules) {
                    rules.forEach(rule => {
                        const ruleElement = strengthHints.querySelector(`[data-rule="${rule}"]`);
                        
                            if (ruleElement) {
                                // Look for icon element
                                let icon = ruleElement.querySelector('i[data-lucide]');
                                if (!icon) {
                                    // Fallback: get first child if it has data-lucide
                                    icon = ruleElement.children[0];
                                    if (!icon || !icon.hasAttribute('data-lucide')) {
                                        return;
                                    }
                                }
                                
                                const isMet = passwordRules[rule].test(password);
                                
                                // Determine new icon and color state
                                let newIcon = 'x';
                                let newColor = 'text-gray-400';
                                
                                if (password.length === 0) {
                                    // Grey state - not typing
                                    newIcon = 'x';
                                    newColor = 'text-gray-400';
                                } else if (isMet) {
                                    // Green state - met
                                    newIcon = 'check';
                                    newColor = 'text-green-500';
                                } else {
                                    // Red state - not met 
                                    newIcon = 'x';
                                    newColor = 'text-red-500';
                                }
                                
                                // Update the icon
                                icon.setAttribute('data-lucide', newIcon);
                                icon.className = `w-4 h-4 ${newColor} transition-colors duration-200`;
                                
                                // Update the span text color to match the icon
                                const span = ruleElement.querySelector('span');
                                if (span) {
                                    // Apply the same color to the text as the icon
                                    span.className = `transition-colors duration-200 ${newColor}`;
                                }
                                
                                // Trigger Lucide icon refresh
                                if (typeof lucide !== 'undefined' && lucide.createIcons) {
                                    lucide.createIcons();
                                }
                            }
                    });
                }

                // Event listeners
                passwordInput.addEventListener('focus', function() {
                    isFocused = true;
                    showPasswordValidation();
                    // Update strength display (will show grey icons for empty password)
                    updatePasswordStrength(this.value || '');
                });

                passwordInput.addEventListener('blur', function() {
                    isFocused = false;
                    clearTimeout(validationTimeout);
                    validationTimeout = setTimeout(() => {
                        hidePasswordValidation();
                    }, 150);
                });

                passwordInput.addEventListener('input', function() {
                    if (isFocused) {
                        updatePasswordStrength(this.value);
                    }
                });

                // Escape key handler
                passwordInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        hidePasswordValidation();
                        this.blur();
                    }
                });
            }

            // Handle Google OAuth sign-in alert
            @if(session('google_signin_success'))
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        // Migrate guest wishlist to user account after Google OAuth sign-in
                        if (typeof migrateGuestWishlist === 'function') {
                            migrateGuestWishlist();
                        }
                        
                        // Show custom alert modal
                        const modal = document.getElementById('custom-alert-modal');
                        const message = document.getElementById('alert-modal-message');
                        const okBtn = document.getElementById('alert-modal-ok-btn');
                        
                        if (modal && message) {
                            message.textContent = '{{ session('google_signin_success') }}';
                            modal.classList.remove('hidden');
                            
                        // Start with invisible/offset state, then animate in (maintain centering using inline style)
                        modal.style.left = '50%';
                        modal.style.transform = 'translateX(-50%) translateY(-10px)';
                        modal.style.opacity = '0';
                        
                        // Add smooth slide-in animation
                        setTimeout(function() {
                            modal.style.transform = 'translateX(-50%) translateY(0)';
                            modal.style.opacity = '1';
                        }, 50);
                        }
                        
                        // Handle close button click
                        if (okBtn) {
                            okBtn.addEventListener('click', function() {
                                modal.style.opacity = '0';
                                modal.style.transform = 'translateX(-50%) translateY(-10px)';
                                setTimeout(function() {
                                    modal.classList.add('hidden');
                                }, 300);
                            });
                        }
                        
                        // Auto-hide after 8 seconds if user doesn't close
                        setTimeout(function() {
                            if (!modal.classList.contains('hidden')) {
                                modal.style.opacity = '0';
                                modal.style.transform = 'translateX(-50%) translateY(-10px)';
                                setTimeout(function() {
                                    modal.classList.add('hidden');
                                }, 300);
                            }
                        }, 8000);
                        
                    }, 500);
                });
            @endif
            
            // Backup initialization
            window.addEventListener('load', function() {
                if (typeof lucide !== 'undefined' && lucide.createIcons) {
                    lucide.createIcons();
                }
                if (typeof AOS !== 'undefined') {
                    AOS.init();
                }
            });
            
            // Lazy Loading Implementation
            function initLazyLoading() {
                const lazyImages = document.querySelectorAll('img.lazy-load');
                
                if ('IntersectionObserver' in window) {
                    const imageObserver = new IntersectionObserver((entries, observer) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const img = entry.target;
                                img.src = img.dataset.src;
                                img.classList.add('loaded');
                                observer.unobserve(img);
                            }
                        });
                    });
                    
                    lazyImages.forEach(img => imageObserver.observe(img));
                } else {
                    // Fallback for browsers without IntersectionObserver
                    lazyImages.forEach(img => {
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                    });
                }
            }
            
            // Initialize lazy loading when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initLazyLoading);
            } else {
                initLazyLoading();
            }
        </script>
        
        @stack('scripts')
        
        <!-- Initialize Lucide Icons -->
        <script>
            // Initialize Lucide icons on client-side pages
            function initClientIcons() {
                if (typeof lucide !== 'undefined' && lucide.createIcons) {
                    lucide.createIcons();
                } else {
                    setTimeout(initClientIcons, 100);
                }
            }
            
            // Initialize when DOM is ready
            document.addEventListener('DOMContentLoaded', initClientIcons);
            
            // Also try on window load
            window.addEventListener('load', initClientIcons);
        </script>
    </body>
</html>
