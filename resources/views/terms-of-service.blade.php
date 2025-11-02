@extends('layouts.app')

@section('title', 'Terms of Service - David\'s Wood Furniture')

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
            <h1 class="text-5xl font-bold text-gray-900 mb-5">Terms of Service</h1>
            <p class="text-2xl text-gray-700 mb-5 font-normal">Terms and conditions for using David's Wood</p>
            <p class="text-gray-600 text-base">Updated October 22, 2025</p>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row">
        <!-- Left Sidebar Navigation -->
        <aside class="w-full lg:w-80 lg:sticky lg:top-20 lg:self-start border-l border-gray-300 my-20">
            <nav class="space-y-1">
                <a href="#introduction" class="sidebar-link block py-2.5 px-8">Introduction</a>
                <a href="#user-accounts" class="sidebar-link block py-2.5 px-8">User Accounts</a>
                <a href="#prohibited-use" class="sidebar-link block py-2.5 px-8">Prohibited Use</a>
                <a href="#intellectual-property" class="sidebar-link block py-2.5 px-8">Intellectual Property</a>
                <a href="#payment-pricing" class="sidebar-link block py-2.5 px-8">Payment and Pricing</a>
                <a href="#refund-cancellation" class="sidebar-link block py-2.5 px-8">Refund and Cancellation</a>
                <a href="#shipping-delivery" class="sidebar-link block py-2.5 px-8">Shipping and Delivery</a>
                <a href="#limitation-liability" class="sidebar-link block py-2.5 px-8">Limitation of Liability</a>
                <a href="#warranty-disclaimer" class="sidebar-link block py-2.5 px-8">Warranty Disclaimer</a>
                <a href="#dispute-resolution" class="sidebar-link block py-2.5 px-8">Dispute Resolution</a>
                <a href="#governing-law" class="sidebar-link block py-2.5 px-8">Governing Law</a>
                <a href="#changes-terms" class="sidebar-link block py-2.5 px-8">Changes to Terms</a>
                <a href="#contact" class="sidebar-link block py-2.5 px-8">Contact Information</a>
                <a href="#privacy-policy" class="sidebar-link block py-2.5 px-8">Privacy Policy Link</a>
            </nav>
        </aside>

        <!-- Right Content Area -->
        <main id="content-area" class="flex-1 ps-20">
            <!-- Introduction -->
            <section id="introduction" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Introduction</h2>
                <div class="space-y-6 text-gray-700 leading-relaxed text-base">
                    <p>
                        <strong>Welcome to David's Wood.</strong> These <strong>Terms of Service</strong> constitute a <strong>legally binding agreement</strong> governing your access to and use of David's Wood's e-commerce platform, website, and services.
                    </p>
                    <p>
                        By accessing or using our website, creating an account, or making a purchase, you agree to be bound by these Terms. If you do not agree with any part of these Terms, you must not use our services.
                    </p>
                    
                    <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">About David's Wood</h3>
                    <p class="text-gray-700 mb-6 leading-relaxed text-base">
                        David's Wood is a Filipino e-commerce platform specializing in handcrafted wooden furniture. We connect customers with locally crafted, high-quality furniture pieces while supporting Filipino artisans and promoting traditional woodcraft.
                    </p>
                    <div class="p-6 rounded-lg mb-6" style="background-color:rgba(255, 255, 255, 0.4);">
                        <p class="text-gray-700 mb-3 text-base"><strong>Business Information:</strong></p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4 text-base">
                            <li><strong>Company Name:</strong> David's Wood</li>
                            <li><strong>Business Type:</strong> E-commerce Platform for Handcrafted Furniture</li>
                            <li><strong>Location:</strong> 123 Santa Rosa - Tagaytay Rd, Silang, 4118 Cavite, Philippines</li>
                            <li><strong>Contact:</strong> hello@davidswood.shop, +63 (917) 123-4567</li>
                        </ul>
                    </div>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-5">Product Catalog</h3>
                    <p class="text-gray-700 mb-6 leading-relaxed text-base">
                        Our platform offers a curated selection of handcrafted Filipino wooden furniture, including but not limited to living room furniture (sofas, tables, cabinets), bedroom furniture (beds, wardrobes, nightstands), dining furniture (dining tables, chairs, buffets), and home decor and accessories.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-5">Product Descriptions</h3>
                    <p class="text-gray-700 mb-6 leading-relaxed text-base">
                        We strive to provide accurate product descriptions, images, specifications, and pricing. However, colors may vary slightly due to screen settings and natural wood variations. Dimensions are approximate and may vary slightly due to handcrafted nature. Product availability is subject to change without notice. We reserve the right to correct errors in descriptions or pricing.
                    </p>

                    <h3 class="text-2xl font-semibold text-gray-800 mb-5">Entire Agreement</h3>
                    <p class="text-gray-700 mb-10 leading-relaxed text-base">
                        These Terms, together with our Privacy Policy and any other policies referenced herein, constitute the entire agreement between you and David's Wood regarding your use of our services and supersede all prior agreements and understandings.
                    </p>
                </div>
            </section>

            <!-- User Accounts -->
            <section id="user-accounts" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">User Accounts</h2>
                
                <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">Eligibility</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    To use our services, you must:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Be at least 18 years of age</li>
                    <li>Have the legal capacity to enter into binding contracts</li>
                    <li>Provide accurate and complete registration information</li>
                    <li>Comply with all applicable laws and regulations</li>
                </ul>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    If you are using our services on behalf of an organization, you represent that you have the authority to bind that organization to these Terms.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Account Creation</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    To make purchases and access certain features, you must create an account by providing full name, valid email address, contact phone number, secure password, and shipping address.
                </p>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    <strong>Email Verification:</strong> For security purposes, all new accounts require email verification. You will receive a verification link via email after registration. Until your email is verified, access to certain features (such as placing orders) may be restricted.
                </p>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    You may also choose to sign in using <strong>Google OAuth</strong>, which is subject to Google's terms and privacy policy.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Account Security</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    You are responsible for maintaining the confidentiality of your account credentials, all activities that occur under your account, and notifying us immediately of any unauthorized access or security breach.
                </p>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    We reserve the right to suspend or terminate accounts that violate these Terms or engage in fraudulent activity.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Account Accuracy</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    You must provide accurate, current, and complete information. You agree to update your information promptly if it changes. We are not liable for any losses or damages resulting from inaccurate information.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Account Termination</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    You may close your account at any time by contacting customer service. We reserve the right to suspend or terminate your account immediately, without prior notice, if you violate these Terms or engage in fraudulent, illegal, or harmful activities.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Assignment</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    You may not assign or transfer your rights or obligations under these Terms without our prior written consent. We may assign or transfer our rights and obligations at any time without restriction.
                </p>
            </section>

            <!-- Prohibited Use -->
            <section id="prohibited-use" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Prohibited Use</h2>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    By using our platform, you agree not to:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Violate any applicable laws or regulations</li>
                    <li>Infringe on intellectual property rights of David's Wood or third parties</li>
                    <li>Provide false, misleading, or fraudulent information</li>
                    <li>Engage in fraudulent transactions or payment disputes</li>
                    <li>Use automated systems (bots, scrapers) to access the website</li>
                    <li>Attempt to gain unauthorized access to our systems or accounts</li>
                    <li>Interfere with or disrupt the operation of the website</li>
                    <li>Upload malware, viruses, or harmful code</li>
                    <li>Harass, abuse, or harm other users or our staff</li>
                    <li>Use the platform for any illegal or unauthorized purpose</li>
                    <li>Resell products for commercial purposes without authorization</li>
                </ul>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    We reserve the right to suspend or terminate your account if you violate these Terms, engage in fraudulent activity, or act in a manner harmful to our business or other users.
                </p>
            </section>

            <!-- Intellectual Property -->
            <section id="intellectual-property" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Intellectual Property</h2>
                
                <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">Our Content</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    All content on the David's Wood platform, including but not limited to text, graphics, logos, images, photographs, product descriptions and specifications, website design, layout, structure, software, code, functionality, trademarks, service marks, and brand names, is the exclusive property of David's Wood or our content providers and is protected by Philippine and international intellectual property laws.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Limited License</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    We grant you a limited, non-exclusive, non-transferable license to access and use our platform for personal, non-commercial purposes. You may not reproduce, distribute, or publicly display our content; modify, adapt, or create derivative works; use our content for commercial purposes without written permission; or remove copyright or proprietary notices.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">User Content</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    If you submit reviews, comments, photos, or other content to our platform (including product reviews from verified purchasers), you grant us a worldwide, royalty-free license to use, reproduce, modify, and display such content for business purposes.
                </p>
            </section>

            <!-- Payment and Pricing -->
            <section id="payment-pricing" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Payment and Pricing</h2>
                
                <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">Pricing</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    All prices are listed in <strong>Philippine Peso (PHP)</strong> unless otherwise stated. Prices are subject to change without prior notice. The price at the time of order placement governs your purchase. Promotional prices are valid only during the specified period. Shipping fees are calculated separately and displayed at checkout.
                </p>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    We reserve the right to correct pricing errors and may cancel orders placed at incorrect prices, even after order confirmation. We will notify you and offer a refund if this occurs.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Product Availability</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Product availability is not guaranteed until your order is confirmed. If a product becomes unavailable after you place an order, we will notify you and offer a suitable alternative product, a full refund of the purchase price, or store credit for future purchases.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Placing Orders</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    By placing an order, you make an offer to purchase products subject to these Terms, agree to pay the total amount including product price, shipping fees, and applicable taxes, and confirm that all information provided is accurate.
                </p>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    After placing an order, you will receive an email confirmation. Order confirmation does not constitute acceptance; we reserve the right to refuse or cancel orders. We may cancel orders due to pricing errors, product unavailability, fraud suspicion, or violation of these Terms.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Payment Methods</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    We accept the following payment methods:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-3 ml-6 leading-relaxed text-base">
                    <li><strong>Credit/Debit Cards:</strong> Processed securely through Xendit payment gateway</li>
                    <li><strong>GCash:</strong> Philippine mobile wallet service via Xendit</li>
                    <li><strong>Maya (formerly PayMaya):</strong> Digital payment platform via Xendit</li>
                    <li><strong>Other E-Wallets:</strong> GrabPay and other supported e-wallets via Xendit</li>
                    <li><strong>Cash on Delivery (COD):</strong> Payment upon receipt of goods (subject to location availability)</li>
                    <li>Other payment methods as may be added from time to time</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Payment Processing</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    Payment is processed securely through third-party payment processors (Xendit). We do not store your complete credit card or payment information. You authorize us to charge the payment method for the total order amount. Payment must be successfully processed before order fulfillment.
                </p>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Taxes and fees, if applicable, will be calculated and displayed at checkout. You are responsible for all charges associated with your order.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Third-Party Payment Services</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Our platform integrates with third-party payment processors (Xendit, GCash, Maya). We are not responsible for the availability, accuracy, or content of these third-party services; the privacy practices or terms of third-party services; or any damages or losses caused by third-party services. Your use of third-party payment services is subject to their respective terms and policies.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Promotions and Discounts</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Promotional offers are subject to specific terms and conditions. Discounts cannot be combined unless explicitly stated. We reserve the right to modify or cancel promotions at any time.
                </p>
            </section>

            <!-- Refund and Cancellation -->
            <section id="refund-cancellation" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Refund and Cancellation</h2>
                
                <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">Return Policy</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    We want you to be completely satisfied with your purchase. If you're not happy with your order, you may request a return within <strong>7 days</strong> of delivery, subject to the following conditions:
                </p>
                <p class="text-gray-700 mb-3 leading-relaxed text-base"><strong>Eligible for Return:</strong></p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Product is defective, damaged, or not as described</li>
                    <li>Wrong item was delivered</li>
                    <li>Product has manufacturing defects</li>
                </ul>
                <p class="text-gray-700 mb-3 leading-relaxed text-base"><strong>Not Eligible for Return:</strong></p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Change of mind or buyer's remorse</li>
                    <li>Products used, assembled, or altered</li>
                    <li>Custom-made or personalized items</li>
                    <li>Sale or clearance items (unless defective)</li>
                    <li>Products without original packaging and tags</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Return Process (RMA)</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    To initiate a return, contact our customer service within 7 days of delivery, provide your order number and reason for return, provide photos of the product and packaging (for damage claims), receive return authorization (RMA number) and instructions, pack the item securely in original packaging, and ship the item to our designated return address (if approved).
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Return Shipping</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Return shipping costs are borne by the customer unless the product is defective or incorrect. We recommend using trackable shipping methods. We are not responsible for items lost or damaged during return transit.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Refunds</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    Refunds are processed after we receive and inspect the returned product. Approved refunds are issued to the original payment method within 7-14 business days. Shipping fees are non-refundable unless we made an error. Refund amount excludes shipping fees paid for the original delivery.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Exchanges</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    If you receive a defective or incorrect product, we will arrange an exchange at no additional cost. Contact us within 7 days to arrange an exchange.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Damaged or Defective Products</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    If you receive a damaged or defective product, contact us within 24-48 hours of delivery, provide photos of the damage or defect, and we will arrange for replacement, repair, or refund at our discretion.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Order Cancellation</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    You may cancel your order before it is shipped by contacting customer service. Once an order has been shipped, standard return policies apply. We reserve the right to cancel orders due to pricing errors, product unavailability, fraud suspicion, or violation of these Terms.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Gift Cards and Store Credit</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Gift cards and store credit are non-refundable and cannot be exchanged for cash. Validity periods and terms apply as specified at the time of issuance.
                </p>
            </section>

            <!-- Shipping and Delivery -->
            <section id="shipping-delivery" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Shipping and Delivery</h2>
                
                <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">Shipping Coverage</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    We currently ship to addresses within the Philippines. International shipping is not available at this time.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Shipping Fees</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Shipping fees are calculated based on delivery location, package size, and weight. Fees are displayed during checkout before payment. We may offer free shipping promotions from time to time.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Delivery Timeframes</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    Estimated delivery times are provided at checkout:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Standard delivery: 7-14 business days (Metro Manila and major cities)</li>
                    <li>Provincial delivery: 10-21 business days</li>
                    <li>Custom or made-to-order items may require additional time</li>
                </ul>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Delivery times are estimates and not guaranteed. We are not liable for delays caused by logistics partners, customs, weather, or other circumstances beyond our control.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Delivery Address</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Ensure your delivery address is accurate and complete. We are not responsible for failed deliveries due to incorrect addresses. Address changes after order placement may not be possible.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Order Tracking</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    You can track your order status through your account's Order History page, email notifications with tracking information, or logistics partner tracking systems.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Inspection Upon Delivery</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    You must inspect products upon delivery. If there is visible damage to the package or product, note the damage with the delivery personnel, take photographs of the damage, and contact us within 24 hours to report the issue.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Third-Party Logistics</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    We work with third-party logistics and shipping partners to deliver your orders. We are not responsible for the availability, accuracy, or services of third-party logistics providers; or any damages or losses caused by third-party logistics services.
                </p>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Your use of our shipping services is subject to the terms and policies of our logistics partners. Delays or issues with delivery are subject to the policies and limitations of our logistics partners.
                </p>
            </section>

            <!-- Limitation of Liability -->
            <section id="limitation-liability" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Limitation of Liability</h2>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    TO THE MAXIMUM EXTENT PERMITTED BY LAW, DAVID'S WOOD, ITS OFFICERS, DIRECTORS, EMPLOYEES, AND AGENTS SHALL NOT BE LIABLE FOR any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits or revenue, loss of data or information, loss of business opportunities, or personal injury or property damage.
                </p>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    Our total liability for any claims arising from your use of our services shall not exceed the amount you paid for the product or service giving rise to the claim, or PHP 5,000, whichever is less.
                </p>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Some jurisdictions do not allow the exclusion or limitation of certain warranties or liabilities. In such jurisdictions, our liability is limited to the fullest extent permitted by law. Nothing in these Terms excludes or limits our liability for death or personal injury caused by negligence, fraud, or any other liability that cannot be excluded by law.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Indemnification</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    You agree to indemnify, defend, and hold harmless David's Wood, its affiliates, officers, directors, employees, agents, and partners from and against any claims, liabilities, damages, losses, costs, or expenses (including reasonable attorneys' fees) arising from:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Your violation of these Terms</li>
                    <li>Your use or misuse of our services</li>
                    <li>Your violation of any rights of third parties</li>
                    <li>Your violation of applicable laws or regulations</li>
                    <li>Any fraudulent or illegal activities associated with your account</li>
                </ul>
            </section>

            <!-- Warranty Disclaimer -->
            <section id="warranty-disclaimer" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Warranty Disclaimer</h2>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    TO THE FULLEST EXTENT PERMITTED BY LAW, DAVID'S WOOD PROVIDES THE PLATFORM AND SERVICES "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED.
                </p>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    We disclaim all warranties, including but not limited to implied warranties of merchantability and fitness for a particular purpose; warranties that the platform will be uninterrupted, error-free, or secure; warranties regarding the accuracy or reliability of content; and warranties that defects will be corrected.
                </p>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Your use of the platform is at your sole risk. While we strive to provide accurate information and quality products, we do not guarantee that products will meet your specific requirements, website functionality will be uninterrupted or error-free, or product descriptions or images are completely accurate due to natural variations in handcrafted items.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Product Warranties</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    David's Wood provides a limited warranty against manufacturing defects for a period of <strong>6 months</strong> from the date of delivery. This warranty covers structural defects in materials and workmanship, manufacturing flaws that affect product functionality, and premature wear under normal use conditions.
                </p>
                <p class="text-gray-700 mb-3 leading-relaxed text-base"><strong>Warranty Exclusions:</strong></p>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Normal wear and tear</li>
                    <li>Damage from misuse, abuse, or neglect</li>
                    <li>Damage from accidents, fire, or natural disasters</li>
                    <li>Modifications or alterations made by the customer</li>
                    <li>Damage from improper assembly or installation</li>
                    <li>Natural variations in wood grain, color, or texture</li>
                </ul>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    To make a warranty claim, contact us with your order number and description of the issue, provide photographs of the defect, and we will assess the claim and determine the appropriate remedy (repair, replacement, or refund).
                </p>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    To maintain warranty coverage, products must be cared for according to the care instructions provided, including regular cleaning with appropriate methods, protection from excessive moisture, heat, or sunlight, and proper assembly and installation.
                </p>
            </section>

            <!-- Dispute Resolution -->
            <section id="dispute-resolution" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Dispute Resolution</h2>
                
                <h3 class="text-2xl font-semibold text-gray-800 mb-5 mt-10">Informal Resolution</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    If you have any concerns or disputes, please contact us first. We will make good faith efforts to resolve the issue informally through negotiation.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Jurisdiction</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Any disputes arising from these Terms or your use of our services shall be resolved exclusively in the courts of Cavite, Philippines. You consent to the personal jurisdiction of such courts.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Arbitration (Optional)</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    As an alternative to litigation, disputes may be submitted to binding arbitration in accordance with the rules of the Philippine Dispute Resolution Center, Inc. (PDRCI) or similar arbitration body, if both parties agree.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Severability</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    If any provision of these Terms is found to be invalid, illegal, or unenforceable by a court of competent jurisdiction, the remaining provisions shall continue in full force and effect. The invalid provision shall be modified to the minimum extent necessary to make it valid and enforceable.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Waiver</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Our failure to enforce any right or provision of these Terms shall not constitute a waiver of such right or provision. Any waiver must be in writing and signed by an authorized representative of David's Wood.
                </p>
            </section>

            <!-- Governing Law -->
            <section id="governing-law" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Governing Law</h2>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    These Terms are governed by and construed in accordance with the laws of the Republic of the Philippines, without regard to conflict of law principles.
                </p>
            </section>

            <!-- Changes to Terms -->
            <section id="changes-terms" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Changes to Terms</h2>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    We reserve the right to modify or update these Terms at any time. Changes will be effective immediately upon posting to our website with a new "Last Updated" date.
                </p>
                <p class="text-gray-700 mb-3 leading-relaxed text-base"><strong>Notification of Changes:</strong></p>
                <ul class="list-disc list-inside text-gray-700 mb-10 space-y-3 ml-6 leading-relaxed text-base">
                    <li>Material changes will be notified via email to registered users</li>
                    <li>A notice will be displayed on our website homepage</li>
                    <li>Continued use of our services after changes constitutes acceptance</li>
                </ul>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    We encourage you to review these Terms periodically to stay informed of any updates.
                </p>
            </section>

            <!-- Contact Information -->
            <section id="contact" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Contact Information</h2>
                <p class="text-gray-700 mb-8 leading-relaxed text-base">
                    If you have any questions, concerns, or feedback regarding these Terms of Service, please contact us:
                </p>
                <div class="p-8 rounded-lg mb-8" style="background-color:rgba(255, 255, 255, 0.4);">
                    <p class="text-gray-700 mb-4 text-base"><strong>David's Wood Customer Service</strong></p>
                    <p class="text-gray-700 mb-4 text-base">
                        <strong>Email:</strong> <a href="mailto:hello@davidswood.shop" class="content-link">hello@davidswood.shop</a>
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
                        Monday - Saturday, 9:00 AM - 6:00 PM (GMT +8)
                    </p>
                </div>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    We aim to respond to all inquiries within 1-2 business days.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Newsletter and Marketing</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    By providing your email address, you may receive promotional emails from David's Wood. You can unsubscribe at any time by clicking the unsubscribe link in our emails.
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Feedback and Suggestions</h3>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    We welcome your feedback and suggestions. Any feedback you provide may be used by David's Wood without obligation to you.
                </p>
            </section>

            <!-- Privacy Policy Link -->
            <section id="privacy-policy" class="pt-20 pb-10">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">Privacy Policy Link</h2>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    Your privacy is important to us. Our collection, use, and protection of your personal information is governed by our <a href="{{ route('privacy-policy') }}" class="content-link">Privacy Policy</a>, which is incorporated into these Terms by reference. By using our services, you consent to our collection and use of personal information as described in the Privacy Policy. We comply with the Philippine Data Privacy Act of 2012 (Republic Act No. 10173).
                </p>

                <h3 class="text-2xl font-semibold text-gray-800 mb-5">Third-Party Services</h3>
                <p class="text-gray-700 mb-6 leading-relaxed text-base">
                    Our platform may integrate with or link to third-party services, including payment processors (Xendit, GCash, Maya), logistics and shipping partners, analytics and advertising services, and social media platforms (Google OAuth).
                </p>
                <p class="text-gray-700 mb-10 leading-relaxed text-base">
                    We are not responsible for the availability, accuracy, or content of third-party services; the privacy practices or terms of third-party services; or any damages or losses caused by third-party services. Your use of third-party services is subject to their respective terms and policies.
                </p>
            </section>

            <!-- Acceptance -->
            <section class="border-t pt-10 pb-20">
                <p class="text-gray-700 text-xl leading-relaxed">
                    <strong>By using David's Wood's e-commerce platform, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.</strong>
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
