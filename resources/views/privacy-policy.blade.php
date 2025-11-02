@extends('layouts.app')

@section('title', 'Privacy Policy - David\'s Wood Furniture')

@section('styles')
<style>
    .sidebar-link {
        position: relative;
        padding-left: 1.5rem;
        transition: all 0.2s ease;
        color: #4a5568;
    }
    a.sidebar-link.active {
        color: #1a202c !important;
        font-weight: 900 !important;
        border-left: 3px solid #655e4e !important;
        padding-left: calc(1.5rem - 3px) !important;
    }
    .sidebar-link:hover {
        color: #655e4e;
    }
    #content-area section {
        scroll-margin-top: 120px;
    }
    .content-link {
        color: #059669;
        text-decoration: underline;
    }
    .content-link:hover {
        color: #047857;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen">
    <!-- Top Banner with Light Green Background -->
    <div class="py-40 sm:px-6" style="background-color: #b3aa99;">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-5xl font-bold text-gray-900 mb-5">Privacy Policy</h1>
            <p class="text-2xl text-gray-700 mb-5 font-normal">How David's Wood handles your data</p>
            <p class="text-gray-600 text-base">Updated October 22, 2025</p>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row">
        <!-- Left Sidebar Navigation -->
        <aside class="w-full lg:w-80 lg:sticky lg:top-20 lg:self-start border-l border-gray-300 my-20">
            <nav class="space-y-1">
                <a href="#introduction" class="sidebar-link block py-2.5 px-8">Introduction</a>
                <a href="#information-we-collect" class="sidebar-link block py-2.5 px-8">Information We Collect</a>
                <a href="#how-we-use" class="sidebar-link block py-2.5 px-8">How We Use Your Information</a>
                <a href="#how-we-share" class="sidebar-link block py-2.5 px-8">How We Share Your Information</a>
                <a href="#data-security" class="sidebar-link block py-2.5 px-8">Data Security</a>
                <a href="#data-retention" class="sidebar-link block py-2.5 px-8">Data Retention</a>
                <a href="#cookies" class="sidebar-link block py-2.5 px-8">Cookies and Tracking</a>
                <a href="#privacy-rights" class="sidebar-link block py-2.5 px-8">Your Privacy Rights</a>
                <a href="#children-privacy" class="sidebar-link block py-2.5 px-8">Children's Privacy</a>
                <a href="#third-party-links" class="sidebar-link block py-2.5 px-8">Third-Party Links</a>
                <a href="#data-transfers" class="sidebar-link block py-2.5 px-8">International Data Transfers</a>
                <a href="#contact" class="sidebar-link block py-2.5 px-8">Contact Us</a>
                <a href="#policy-changes" class="sidebar-link block py-2.5 px-8">Changes to This Policy</a>
                <a href="#dpa-compliance" class="sidebar-link block py-2.5 px-8">Philippine Data Privacy Act</a>
            </nav>
        </aside>

        <!-- Right Content Area -->
        <main id="content-area" class="flex-1 ps-20">
            <!-- Introduction -->
            <section id="introduction" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Introduction</h2>
                <div class="space-y-6 text-gray-700 leading-relaxed text-base">
                    <p>
                        In our mission to make commerce better for everyone at David's Wood, we collect and use information about you, our:
                    </p>
                    <ul class="list-disc list-inside space-y-3 ml-6">
                        <li><a href="#information-we-collect" class="content-link">merchants using David's Wood</a> to power your business</li>
                        <li><a href="#information-we-collect" class="content-link">customers</a> who shop at a David's Wood-powered business</li>
                        <li><a href="#information-we-collect" class="content-link">partners</a> who develop apps for merchants to use, build stores on behalf of merchants, refer potential entrepreneurs to David's Wood, or otherwise help merchants operate or improve their David's Wood-powered business</li>
                        <li><a href="#information-we-collect" class="content-link">users of David's Wood apps and services</a> like our customer support chatbot</li>
                        <li><a href="#information-we-collect" class="content-link">visitors to David's Wood's websites</a>, or anyone contacting David's Wood support</li>
                    </ul>
                    <p>
                        This Privacy Policy will help you better understand how we collect, use, and share your personal information. If we change our privacy practices, we may update this policy. If we make any material changes, we will notify you as required by law, including by posting the updated policy on our website.
                    </p>
                </div>
            </section>

            <!-- Information We Collect -->
            <section id="information-we-collect" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Information We Collect</h2>
                
                <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">Personal Information You Provide</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    When you use our platform, we may collect the following personal information:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li><strong>Account Information:</strong> Name, email address, phone number, username, and password when you register for an account</li>
                    <li><strong>Shipping Information:</strong> Delivery address, contact details, and recipient information</li>
                    <li><strong>Payment Information:</strong> Payment method details (processed securely through our payment partners Xendit, GCash, Maya, or Cash on Delivery)</li>
                    <li><strong>Order Information:</strong> Purchase history, product preferences, and transaction details</li>
                    <li><strong>Communication Information:</strong> Messages, inquiries, and feedback you send to us through contact forms or customer support</li>
                    <li><strong>Authentication Information:</strong> Email verification status, authentication tokens (for magic link authentication and password reset), Google OAuth information (if you choose to sign in with Google), and login activity</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Information Collected Automatically</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    When you visit our website, we automatically collect certain information:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li><strong>Device Information:</strong> IP address, browser type, operating system, and device identifiers</li>
                    <li><strong>Usage Data:</strong> Pages visited, time spent on pages, click patterns, and navigation paths</li>
                    <li><strong>Cookies and Tracking Technologies:</strong> We use cookies and similar technologies to enhance your experience (see <a href="#cookies" class="content-link">Cookies and Tracking section</a>)</li>
                    <li><strong>Location Data:</strong> General geographic location based on IP address to optimize delivery services</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Information from Third Parties</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    We may receive information from:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li><strong>Payment Processors:</strong> Transaction confirmation and payment status from Xendit, GCash, Maya, and other payment partners</li>
                    <li><strong>Logistics Partners:</strong> Delivery status and tracking information</li>
                    <li><strong>Authentication Providers:</strong> Basic profile information from Google OAuth (if you sign in with Google)</li>
                    <li><strong>Analytics Providers:</strong> Aggregated website usage statistics</li>
                </ul>
            </section>

            <!-- How We Use Your Information -->
            <section id="how-we-use" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">How We Use Your Information</h2>
                <p class="text-gray-700 mb-8 leading-relaxed text-base">
                    We use the collected information for the following purposes:
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">Order Processing and Fulfillment</h3>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Process and complete your purchases</li>
                    <li>Manage your shopping cart and checkout experience</li>
                    <li>Arrange product delivery and shipping</li>
                    <li>Send order confirmations and status updates</li>
                    <li>Handle returns, exchanges, and refunds (including RMA management)</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Account Management</h3>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Create and maintain your user account</li>
                    <li>Verify your identity and authenticate access</li>
                    <li>Enable email verification and manage authentication tokens</li>
                    <li>Enable order history tracking</li>
                    <li>Manage your preferences and settings</li>
                    <li>Save items in your wishlist</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Customer Service</h3>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Respond to your inquiries and support requests</li>
                    <li>Provide product information and assistance</li>
                    <li>Resolve disputes and troubleshoot problems</li>
                    <li>Improve our customer service quality</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Business Operations</h3>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Maintain and improve our website functionality</li>
                    <li>Analyze user behavior and preferences</li>
                    <li>Generate sales reports and business analytics</li>
                    <li>Manage inventory and product catalog</li>
                    <li>Detect and prevent fraud or unauthorized activities</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Marketing and Communications</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    With your consent, we may:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Send promotional materials about our products</li>
                    <li>Notify you about new products, special offers, and updates</li>
                    <li>Conduct surveys and gather feedback</li>
                    <li>Personalize your shopping experience</li>
                </ul>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    You may opt out of marketing communications at any time by clicking the "unsubscribe" link in our emails or contacting us directly.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Legal Compliance</h3>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Comply with applicable laws and regulations</li>
                    <li>Enforce our Terms of Service</li>
                    <li>Protect our rights and property</li>
                    <li>Respond to legal requests and prevent harm</li>
                </ul>
            </section>

            <!-- How We Share Your Information -->
            <section id="how-we-share" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">How We Share Your Information</h2>
                <p class="text-gray-700 mb-8 leading-relaxed text-base">
                    We respect your privacy and do not sell your personal information. We may share your information only in the following circumstances:
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">Service Providers</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    We share information with trusted third-party service providers who assist us in:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Payment processing (Xendit, GCash, Maya, payment gateways)</li>
                    <li>Shipping and logistics services</li>
                    <li>Web hosting and cloud storage</li>
                    <li>Analytics and website optimization</li>
                    <li>Customer support tools</li>
                    <li>Authentication services (Google OAuth)</li>
                </ul>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    These providers are contractually obligated to protect your information and use it only for the purposes we specify.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Business Transfers</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    In the event of a merger, acquisition, sale of assets, or bankruptcy, your information may be transferred to the acquiring entity.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Legal Requirements</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    We may disclose your information when required by law or in response to:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Valid legal processes (subpoenas, court orders)</li>
                    <li>Government or regulatory requests</li>
                    <li>Protection of our rights, property, or safety</li>
                    <li>Prevention of fraud or illegal activities</li>
                    <li>Enforcement of our policies</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">With Your Consent</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    We may share your information for other purposes with your explicit consent.
                </p>
            </section>

            <!-- Data Security -->
            <section id="data-security" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Data Security</h2>
                <p class="text-gray-700 mb-8 leading-relaxed text-base">
                    We implement appropriate technical and organizational measures to protect your personal information:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li><strong>Encryption:</strong> Sensitive data is encrypted during transmission using SSL/TLS protocols</li>
                    <li><strong>Secure Storage:</strong> Personal information is stored on secure servers with restricted access</li>
                    <li><strong>Access Controls:</strong> Only authorized personnel have access to personal data</li>
                    <li><strong>Regular Security Audits:</strong> We conduct periodic security assessments and updates</li>
                    <li><strong>Payment Security:</strong> Payment information is processed through PCI-DSS compliant payment partners</li>
                    <li><strong>Secure Authentication:</strong> We use secure authentication tokens with expiration periods and industry-standard password hashing</li>
                </ul>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    While we strive to protect your information, no method of transmission over the internet is 100% secure. We cannot guarantee absolute security but continuously work to maintain the highest standards.
                </p>
            </section>

            <!-- Data Retention -->
            <section id="data-retention" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Data Retention</h2>
                <p class="text-gray-700 mb-8 leading-relaxed text-base">
                    We retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required by law.
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li><strong>Account Information:</strong> Retained while your account is active and for a reasonable period after account closure</li>
                    <li><strong>Transaction Records:</strong> Retained for at least 5 years for accounting, tax, and legal compliance purposes</li>
                    <li><strong>Marketing Data:</strong> Retained until you opt out of communications</li>
                    <li><strong>Website Analytics:</strong> Aggregated and anonymized data may be retained indefinitely</li>
                    <li><strong>Authentication Tokens:</strong> Retained only for their validity period (typically 1 hour) and then automatically deleted</li>
                </ul>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    You may request deletion of your personal information at any time, subject to legal and contractual obligations.
                </p>
            </section>

            <!-- Cookies and Tracking -->
            <section id="cookies" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Cookies and Tracking Technologies</h2>
                
                <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">What Are Cookies?</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Cookies are small text files stored on your device that help us provide and improve our services.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Types of Cookies We Use</h3>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li><strong>Essential Cookies:</strong> Necessary for website functionality (shopping cart, login sessions)</li>
                    <li><strong>Performance Cookies:</strong> Help us understand how visitors use our website (analytics)</li>
                    <li><strong>Functionality Cookies:</strong> Remember your preferences and settings</li>
                    <li><strong>Marketing Cookies:</strong> Track your browsing to provide relevant advertisements (with consent)</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Managing Cookies</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    You can control cookies through your browser settings. However, disabling certain cookies may affect website functionality, such as maintaining your shopping cart or staying logged in.
                </p>
            </section>

            <!-- Your Privacy Rights -->
            <section id="privacy-rights" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Your Privacy Rights</h2>
                <p class="text-gray-700 mb-8 leading-relaxed text-base">
                    Depending on your location, you may have the following rights regarding your personal information:
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">Access and Portability</h3>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Request a copy of the personal information we hold about you</li>
                    <li>Receive your data in a structured, commonly used format</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Correction and Update</h3>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Correct inaccurate or incomplete information</li>
                    <li>Update your account details at any time</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Deletion</h3>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Request deletion of your personal information (subject to legal obligations)</li>
                    <li>Close your account permanently</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Objection and Restriction</h3>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Object to processing of your information for marketing purposes</li>
                    <li>Request restriction of processing under certain circumstances</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Withdraw Consent</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Withdraw consent for data processing where consent was the legal basis.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Lodge a Complaint</h3>
                <p class="text-gray-700 mb-8 leading-relaxed text-base">
                    File a complaint with the relevant data protection authority.
                </p>

                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    To exercise any of these rights, please contact us using the information in the <a href="#contact" class="content-link">Contact Us</a> section.
                </p>
            </section>

            <!-- Children's Privacy -->
            <section id="children-privacy" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Children's Privacy</h2>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    Our services are not intended for individuals under the age of 18. We do not knowingly collect personal information from children. If we become aware that we have collected information from a child without parental consent, we will take steps to delete such information promptly.
                </p>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    If you believe we have collected information from a child, please contact us immediately.
                </p>
            </section>

            <!-- Third-Party Links -->
            <section id="third-party-links" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Third-Party Links</h2>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Our website may contain links to third-party websites, services, or applications that are not operated by us. We are not responsible for the privacy practices of these third parties. We encourage you to review their privacy policies before providing any personal information.
                </p>
            </section>

            <!-- International Data Transfers -->
            <section id="data-transfers" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">International Data Transfers</h2>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    David's Wood operates primarily within the Philippines. If you access our website from outside the Philippines, your information may be transferred to, stored, and processed in the Philippines. By using our services, you consent to such transfers.
                </p>
            </section>

            <!-- Contact Us -->
            <section id="contact" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Contact Us</h2>
                <p class="text-gray-700 mb-8 leading-relaxed text-base">
                    If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:
                </p>
                <div class="p-8 rounded-lg mb-8" style="background-color:rgba(255, 255, 255, 0.4);">
                    <p class="text-gray-700 mb-4 text-base"><strong>David's Wood</strong></p>
                    <p class="text-gray-700 mb-4 text-base">
                        <strong>Email:</strong> <a href="mailto:privacy@davidswood.com" class="content-link">privacy@davidswood.com</a> or <a href="mailto:hello@davidswood.shop" class="content-link">hello@davidswood.shop</a>
                    </p>
                    <p class="text-gray-700 mb-4 text-base">
                        <strong>Phone:</strong> +63 (917) 123-4567
                    </p>
                    <p class="text-gray-700 mb-4 text-base">
                        <strong>Address:</strong><br>
                        123 Santa Rosa - Tagaytay Rd,<br>
                        Silang, 4118 Cavite<br>
                        Philippines
                    </p>
                    <p class="text-gray-700 text-base">
                        <strong>Business Hours:</strong><br>
                        Monday - Saturday, 9:00 AM - 6:00 PM (Philippine Time)
                    </p>
                </div>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    We will respond to your inquiry within 30 days.
                </p>
            </section>

            <!-- Updates to Privacy Policy -->
            <section id="policy-changes" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Changes to This Privacy Policy</h2>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    We may update this Privacy Policy from time to time to reflect changes in our practices, technology, legal requirements, or business operations. We will notify you of any material changes by:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Posting the updated policy on our website with a new "Last Updated" date</li>
                    <li>Sending an email notification to registered users</li>
                    <li>Displaying a prominent notice on our homepage</li>
                </ul>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Your continued use of our services after changes are posted constitutes acceptance of the updated Privacy Policy. We encourage you to review this policy periodically.
                </p>
            </section>

            <!-- Philippine Data Privacy Act Compliance -->
            <section id="dpa-compliance" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Philippine Data Privacy Act Compliance</h2>
                <p class="text-gray-700 mb-8 leading-relaxed text-base">
                    David's Wood complies with Republic Act No. 10173, also known as the Data Privacy Act of 2012, and its implementing rules and regulations. We are committed to:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-8 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Processing personal information in a transparent and lawful manner</li>
                    <li>Collecting information only for specified and legitimate purposes</li>
                    <li>Ensuring information is adequate, relevant, and not excessive</li>
                    <li>Maintaining accuracy of personal information</li>
                    <li>Retaining information only as long as necessary</li>
                    <li>Implementing appropriate security measures</li>
                </ul>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    For concerns related to data privacy compliance, you may also contact the National Privacy Commission at:
                </p>
                <div class="p-6 rounded-lg" style="background-color:rgba(255, 255, 255, 0.4);>
                    <p class="text-gray-700 mb-4 text-base"><strong>National Privacy Commission</strong></p>
                    <p class="text-gray-700 mb-4 text-base">Website: <a href="https://www.privacy.gov.ph" class="content-link" target="_blank">www.privacy.gov.ph</a></p>
                    <p class="text-gray-700 text-base">Email: <a href="mailto:info@privacy.gov.ph" class="content-link">info@privacy.gov.ph</a></p>
                </div>
            </section>

            <!-- Closing Statement -->
            <section class="border-t pt-10 pb-20">
                <p class="text-gray-700 text-xl leading-relaxed">
                    <strong>By using David's Wood's e-commerce platform, you acknowledge that you have read, understood, and agree to be bound by these Privacy Policy.</strong>
                </p>
            </section>
        </main>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    function initSidebarActive() {
        try {
            var contentArea = document.getElementById('content-area');
            if (!contentArea) {
                setTimeout(initSidebarActive, 100);
                return;
            }
            
            var sections = contentArea.querySelectorAll('section[id]');
            var links = document.querySelectorAll('.sidebar-link');
            
            if (sections.length === 0 || links.length === 0) {
                setTimeout(initSidebarActive, 100);
                return;
            }
            
            function setActiveLink() {
                var current = '';
                var scrollY = window.scrollY || window.pageYOffset || 0;
                var scrollPos = scrollY + 200;
                
                // Find current section
                for (var i = 0; i < sections.length; i++) {
                    var section = sections[i];
                    var sectionTop = section.offsetTop;
                    var sectionHeight = section.offsetHeight || section.clientHeight;
                    
                    if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                        current = section.getAttribute('id');
                        break;
                    }
                }
                
                // Fallback: find last passed section
                if (!current) {
                    if (scrollY < 100) {
                        current = sections[0].getAttribute('id');
                    } else {
                        for (var j = sections.length - 1; j >= 0; j--) {
                            if (scrollY >= sections[j].offsetTop - 200) {
                                current = sections[j].getAttribute('id');
                                break;
                            }
                        }
                    }
                }
                
                // Update links
                if (current) {
                    for (var k = 0; k < links.length; k++) {
                        var link = links[k];
                        var href = link.getAttribute('href') || '';
                        var linkId = href.replace('#', '').trim();
                        
                        if (linkId === current) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    }
                }
            }
            
            // Click handlers
            for (var i = 0; i < links.length; i++) {
                (function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        var href = this.getAttribute('href') || '';
                        var targetId = href.replace('#', '').trim();
                        var target = document.getElementById(targetId);
                        
                        if (target) {
                            // Remove all active
                            for (var j = 0; j < links.length; j++) {
                                links[j].classList.remove('active');
                            }
                            // Add to clicked
                            this.classList.add('active');
                            
                            // Scroll
                            var offset = 150;
                            var rect = target.getBoundingClientRect();
                            var targetPos = (rect.top + (window.pageYOffset || document.documentElement.scrollTop)) - offset;
                            
                            window.scrollTo({
                                top: Math.max(0, targetPos),
                                behavior: 'smooth'
                            });
                            
                            setTimeout(setActiveLink, 600);
                        }
                    });
                })(links[i]);
            }
            
            // Scroll handler
            var scrollTimeout = null;
            window.addEventListener('scroll', function() {
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }
                scrollTimeout = setTimeout(setActiveLink, 10);
            }, { passive: true });
            
            // Initial set
            setTimeout(setActiveLink, 50);
        } catch (e) {
            console.error('Sidebar active error:', e);
        }
    }
    
    // Run immediately if DOM is ready, otherwise wait
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebarActive);
    } else {
        initSidebarActive();
    }
})();
</script>
@endsection
