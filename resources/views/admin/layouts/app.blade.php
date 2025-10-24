<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - David's Wood Admin</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/assets/favicon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Preline UI -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/preline/dist/preline.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#3C50E0',
                        secondary: '#80CAEE',
                        success: '#219653',
                        danger: '#D34053',
                        warning: '#FFA70B',
                        info: '#0FADCF',
                        
                        // Brand Colors - Light Mode
                        'brand-dark': '#0D1E1E',      // Primary dark navy
                        'brand-green': '#52734F',     // Sage green accent
                        'brand-beige': '#D3D0CF',     // Light beige background
                        'brand-brown': '#6C464E',     // Muted brown
                        'brand-rose': '#96616B',      // Rose accent
                        
                        // Dark Mode Variants
                        'brand-dark-dm': '#1A2F2F',   // Lighter navy for dark mode
                        'brand-green-dm': '#6B9266',  // Lighter green for dark mode
                        'brand-beige-dm': '#2A2826',  // Dark beige for dark mode
                        'brand-brown-dm': '#8B5D68',  // Lighter brown for dark mode
                        'brand-rose-dm': '#B38791',   // Lighter rose for dark mode
                        dark: '#1C2434',
                        'body': '#64748B',
                        'bodydark': '#AEB7C0',
                        'bodydark1': '#DEE4EE',
                        'bodydark2': '#8A99AF',
                        'stroke': '#E2E8F0',
                        'gray': '#EFF4FB',
                        'graydark': '#333A48',
                        'whiten': '#F1F5F9',
                        'whiter': '#F5F7FD',
                        'boxdark': '#24303F',
                        'boxdark-2': '#1A222C',
                        'strokedark': '#2E3A47',
                        'form-strokedark': '#3d4d60',
                        'form-input': '#1d2a39',
                        'meta-1': '#DC3545',
                        'meta-2': '#EFF2F7',
                        'meta-3': '#10B981',
                        'meta-4': '#313D4A',
                        'meta-5': '#259AE6',
                        'meta-6': '#FFBA00',
                        'meta-7': '#FF6766',
                        'meta-8': '#F0950C',
                        'meta-9': '#E5E7EB',
                        'meta-10': '#0FADCF',
                    },
                    fontSize: {
                        'title-xxl': ['44px', '55px'],
                        'title-xl': ['36px', '45px'],
                        'title-xl2': ['33px', '45px'],
                        'title-lg': ['28px', '35px'],
                        'title-md': ['24px', '30px'],
                        'title-md2': ['26px', '30px'],
                        'title-sm': ['20px', '26px'],
                        'title-sm2': ['22px', '28px'],
                        'title-xsm': ['18px', '24px'],
                    },
                    spacing: {
                        '4.5': '1.125rem',
                        '5.5': '1.375rem',
                        '6.5': '1.625rem',
                        '7.5': '1.875rem',
                        '8.5': '2.125rem',
                        '9.5': '2.375rem',
                        '10.5': '2.625rem',
                        '11.5': '2.875rem',
                        '12.5': '3.125rem',
                        '13': '3.25rem',
                        '13.5': '3.375rem',
                        '14.5': '3.625rem',
                        '15': '3.75rem',
                        '15.5': '3.875rem',
                        '16.5': '4.125rem',
                        '17': '4.25rem',
                        '17.5': '4.375rem',
                        '18': '4.5rem',
                        '18.5': '4.625rem',
                        '19': '4.75rem',
                        '19.5': '4.875rem',
                        '21': '5.25rem',
                        '21.5': '5.375rem',
                        '22': '5.5rem',
                        '22.5': '5.625rem',
                        '24.5': '6.125rem',
                        '25': '6.25rem',
                        '25.5': '6.375rem',
                        '26': '6.5rem',
                        '27': '6.75rem',
                        '27.5': '6.875rem',
                        '29': '7.25rem',
                        '29.5': '7.375rem',
                        '30': '7.5rem',
                        '32.5': '8.125rem',
                        '34': '8.5rem',
                        '35': '8.75rem',
                        '36.5': '9.125rem',
                        '37.5': '9.375rem',
                        '39': '9.75rem',
                        '39.5': '9.875rem',
                        '40': '10rem',
                        '42.5': '10.625rem',
                        '44': '11rem',
                        '45': '11.25rem',
                        '46': '11.5rem',
                        '47.5': '11.875rem',
                        '49': '12.25rem',
                        '50': '12.5rem',
                        '52.5': '13.125rem',
                        '54': '13.5rem',
                        '54.5': '13.625rem',
                        '55': '13.75rem',
                        '55.5': '13.875rem',
                        '59': '14.75rem',
                        '60': '15rem',
                        '62.5': '15.625rem',
                        '65': '16.25rem',
                        '67': '16.75rem',
                        '67.5': '16.875rem',
                        '70': '17.5rem',
                        '72.5': '18.125rem',
                        '73': '18.25rem',
                        '75': '18.75rem',
                        '90': '22.5rem',
                        '94': '23.5rem',
                        '95': '23.75rem',
                        '100': '25rem',
                        '115': '28.75rem',
                        '125': '31.25rem',
                        '132.5': '33.125rem',
                        '150': '37.5rem',
                        '171.5': '42.875rem',
                        '180': '45rem',
                        '187.5': '46.875rem',
                        '203': '50.75rem',
                        '230': '57.5rem',
                        '242.5': '60.625rem',
                    },
                    maxWidth: {
                        '2.5xl': '45rem',
                        '3xl': '48rem',
                        '4xl': '56rem',
                        '5xl': '64rem',
                        '6xl': '72rem',
                        '7xl': '80rem',
                    },
                    zIndex: {
                        '999': '999',
                        '9999': '9999',
                        '99999': '99999',
                        '999999': '999999',
                        '9999999': '9999999',
                    },
                }
            }
        }
    </script>
    
    <!-- Lucide Icons - Local File -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        });
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/m44aoheimxfmxu4q07iimwoq1y6xoiga1gr0bjz6hkl1n7r0/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    <!-- Pusher -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    <!-- Laravel Echo -->
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Hide scrollbar but keep functionality */
        .no-scrollbar {
            -ms-overflow-style: none;  /* Internet Explorer 10+ */
            scrollbar-width: none;  /* Firefox */
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;  /* Safari and Chrome */
        }
        
        /* TinyMCE Custom Styles */
        .tox-tinymce {
            border-radius: 0.5rem !important;
            border: 1px solid #e2e8f0 !important;
        }
        
        .tox-tinymce:hover {
            border-color: #cbd5e1 !important;
        }
        
        .tox-tinymce:focus-within {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
        
        .dark .tox-tinymce {
            border-color: #374151 !important;
            background-color: #1f2937 !important;
        }
        
        .dark .tox-tinymce:hover {
            border-color: #4b5563 !important;
        }
        
        .dark .tox-tinymce:focus-within {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100/50 dark:from-boxdark-2 dark:to-boxdark" x-data="{ 
    sidebarOpen: false, 
    darkMode: localStorage.getItem('darkMode') === 'true' || false,
    sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' || false,
    notifications: [],
    unreadCount: 0,
    echo: null
}" 
x-init="
    $watch('darkMode', val => localStorage.setItem('darkMode', val));
    $watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val));
    // Auto-collapse on mobile
    if (window.innerWidth < 1024) {
        sidebarCollapsed = false;
    }
    
    // Initialize real-time notifications
    initializeRealtimeNotifications();
" 
:class="{ 'dark': darkMode }">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')
        
        <!-- Content Area -->
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            @include('admin.partials.header')
            
            <!-- Main Content -->
            <main>
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    @include('admin.partials.alerts')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         class="fixed inset-0 z-9999 bg-black bg-opacity-50 lg:hidden"
         x-cloak></div>
    
    @stack('scripts')
    
    <!-- Initialize Lucide Icons -->
    <script>
        // Simple icon initialization
        function initIcons() {
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        }
        
        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', initIcons);
        
        // Re-initialize after Alpine loads
        document.addEventListener('alpine:initialized', () => {
            setTimeout(initIcons, 100);
        });
    </script>
    
    <!-- Preline UI Script -->
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.min.js"></script>
    
    <!-- TinyMCE Initialization -->
    <script>
        // Initialize TinyMCE
        function initTinyMCE() {
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: 'textarea.tinymce',
                    height: 400,
                    menubar: false,
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
                    ],
                    toolbar: 'undo redo | blocks | ' +
                        'bold italic forecolor | alignleft aligncenter ' +
                        'alignright alignjustify | bullist numlist outdent indent | ' +
                        'removeformat | help | image | link | table | emoticons | code',
                    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
                    skin: 'oxide',
                    content_css: 'default',
                    branding: false,
                    promotion: false,
                    resize: true,
                    statusbar: true,
                    elementpath: true,
                    paste_data_images: true,
                    images_upload_handler: function (blobInfo, success, failure) {
                        // Handle image uploads
                        const formData = new FormData();
                        formData.append('image', blobInfo.blob(), blobInfo.filename());
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                        
                        fetch('/admin/images/upload', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                success(result.images[0].url);
                            } else {
                                failure('Upload failed: ' + result.message);
                            }
                        })
                        .catch(error => {
                            failure('Upload failed: ' + error.message);
                        });
                    },
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                        });
                    }
                });
            }
        }
        
        // Initialize TinyMCE when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initTinyMCE();
        });
        
        // Re-initialize TinyMCE after Alpine updates
        document.addEventListener('alpine:initialized', () => {
            setTimeout(() => {
                initTinyMCE();
            }, 100);
        });
        
        // Initialize TinyMCE for dynamically loaded content
        function reinitTinyMCE() {
            if (typeof tinymce !== 'undefined') {
                tinymce.remove('textarea.tinymce');
                initTinyMCE();
            }
        }
        
        // Make reinitTinyMCE globally available
        window.reinitTinyMCE = reinitTinyMCE;
        
        // Real-time Notifications
        function initializeRealtimeNotifications() {
            // Check if Pusher and Echo are available
            if (typeof Pusher === 'undefined' || typeof Echo === 'undefined') {
                return;
            }
            
            // Initialize Echo with Pusher
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env('PUSHER_APP_KEY', 'your-pusher-key') }}',
                cluster: '{{ env('PUSHER_APP_CLUSTER', 'mt1') }}',
                forceTLS: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }
            });
            
            // Listen for admin notifications
            window.Echo.private('admin.notifications')
                .listen('.system.notification', (e) => {
                    addNotification(e);
                })
                .listen('.order.created', (e) => {
                    addNotification(e);
                })
                .listen('.order.status.changed', (e) => {
                    addNotification(e);
                })
                .listen('.inventory.low.stock', (e) => {
                    addNotification(e);
                })
                .listen('.review.created', (e) => {
                    addNotification(e);
                });
        }
        
        function addNotification(data) {
            const notification = {
                id: Date.now(),
                title: data.title || getNotificationTitle(data.type),
                message: data.message,
                type: data.type || 'info',
                priority: data.priority || 'medium',
                timestamp: new Date(),
                read: false
            };
            
            // Add to notifications array
            this.notifications.unshift(notification);
            
            // Update unread count
            this.unreadCount++;
            
            // Show browser notification if permission granted
            if (Notification.permission === 'granted') {
                new Notification(notification.title, {
                    body: notification.message,
                    icon: '/favicon.ico',
                    tag: notification.id
                });
            }
            
            // Auto-remove after 10 seconds
            setTimeout(() => {
                const index = this.notifications.findIndex(n => n.id === notification.id);
                if (index > -1) {
                    this.notifications.splice(index, 1);
                }
            }, 10000);
        }
        
        function getNotificationTitle(type) {
            const titles = {
                'order': 'New Order',
                'order_status': 'Order Status Update',
                'inventory': 'Low Stock Alert',
                'review': 'New Review',
                'info': 'System Notification'
            };
            return titles[type] || 'Notification';
        }
        
        function markNotificationAsRead(notificationId) {
            const notification = this.notifications.find(n => n.id === notificationId);
            if (notification && !notification.read) {
                notification.read = true;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            }
        }
        
        function markAllAsRead() {
            this.notifications.forEach(notification => {
                notification.read = true;
            });
            this.unreadCount = 0;
        }
        
        function formatTime(timestamp) {
            const now = new Date();
            const time = new Date(timestamp);
            const diffInSeconds = Math.floor((now - time) / 1000);
            
            if (diffInSeconds < 60) {
                return 'Just now';
            } else if (diffInSeconds < 3600) {
                const minutes = Math.floor(diffInSeconds / 60);
                return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
            } else if (diffInSeconds < 86400) {
                const hours = Math.floor(diffInSeconds / 3600);
                return `${hours} hour${hours > 1 ? 's' : ''} ago`;
            } else {
                const days = Math.floor(diffInSeconds / 86400);
                return `${days} day${days > 1 ? 's' : ''} ago`;
            }
        }
        
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
        
        // Make functions globally available
        window.addNotification = addNotification;
        window.markNotificationAsRead = markNotificationAsRead;
        window.markAllAsRead = markAllAsRead;
        window.formatTime = formatTime;
    </script>
</body>
</html>