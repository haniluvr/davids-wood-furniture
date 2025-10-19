<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsPage;

class CmsPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cmsPages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'created_by' => 1, // Admin user ID
                'content' => '<h1>About David\'s Wood Furniture</h1>
                <p>Welcome to David\'s Wood Furniture, where craftsmanship meets quality. For over 20 years, we have been creating beautiful, handcrafted furniture that brings warmth and character to your home.</p>
                
                <h2>Our Story</h2>
                <p>Founded in 2003 by master craftsman David Johnson, our company began as a small workshop in the heart of Oregon. What started as a passion for woodworking has grown into a trusted name in custom furniture.</p>
                
                <h2>Our Mission</h2>
                <p>We are committed to creating furniture that not only looks beautiful but also stands the test of time. Each piece is carefully crafted using traditional techniques and the finest materials.</p>
                
                <h2>Why Choose Us?</h2>
                <ul>
                    <li>Handcrafted with attention to detail</li>
                    <li>Sustainable and eco-friendly materials</li>
                    <li>Custom designs to fit your space</li>
                    <li>Lifetime warranty on all products</li>
                </ul>',
                'type' => 'page',
                'is_featured' => true,
                'published_at' => now(),
                'sort_order' => 1,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h1>Contact David\'s Wood Furniture</h1>
                <p>We\'d love to hear from you! Whether you have questions about our products, need help with a custom order, or want to visit our showroom, we\'re here to help.</p>
                
                <h2>Get in Touch</h2>
                <div class="contact-info">
                    <h3>Phone</h3>
                    <p>(555) 123-4567</p>
                    
                    <h3>Email</h3>
                    <p>info@davidswoodfurniture.com</p>
                    
                    <h3>Address</h3>
                    <p>123 Craftsmanship Lane<br>
                    Portland, OR 97201</p>
                </div>
                
                <h2>Showroom Hours</h2>
                <ul>
                    <li>Monday - Friday: 9:00 AM - 6:00 PM</li>
                    <li>Saturday: 10:00 AM - 4:00 PM</li>
                    <li>Sunday: Closed</li>
                </ul>
                
                <h2>Custom Orders</h2>
                <p>For custom furniture inquiries, please call us directly or visit our showroom. We offer free consultations for all custom projects.</p>',
                'type' => 'page',
                'is_featured' => false,
                'published_at' => now(),
                'sort_order' => 2,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1>
                <p><strong>Last updated:</strong> ' . now()->format('F d, Y') . '</p>
                
                <h2>Information We Collect</h2>
                <p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p>
                
                <h2>How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Process and fulfill your orders</li>
                    <li>Provide customer support</li>
                    <li>Send you important updates about your orders</li>
                    <li>Improve our products and services</li>
                </ul>
                
                <h2>Information Sharing</h2>
                <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>
                
                <h2>Data Security</h2>
                <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
                
                <h2>Contact Us</h2>
                <p>If you have any questions about this Privacy Policy, please contact us at privacy@davidswoodfurniture.com</p>',
                'type' => 'policy',
                'is_featured' => false,
                'published_at' => now(),
                'sort_order' => 3,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<h1>Terms of Service</h1>
                <p><strong>Last updated:</strong> ' . now()->format('F d, Y') . '</p>
                
                <h2>Acceptance of Terms</h2>
                <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>
                
                <h2>Use License</h2>
                <p>Permission is granted to temporarily download one copy of the materials on David\'s Wood Furniture\'s website for personal, non-commercial transitory viewing only.</p>
                
                <h2>Product Information</h2>
                <p>We strive to provide accurate product descriptions and images. However, we do not warrant that product descriptions or other content is accurate, complete, reliable, or error-free.</p>
                
                <h2>Pricing and Payment</h2>
                <p>All prices are subject to change without notice. Payment is due at the time of purchase unless other arrangements have been made.</p>
                
                <h2>Shipping and Delivery</h2>
                <p>Delivery times are estimates and may vary. We are not responsible for delays caused by shipping carriers or other factors beyond our control.</p>
                
                <h2>Returns and Refunds</h2>
                <p>Returns must be made within 30 days of delivery. Custom orders are not eligible for returns unless there is a defect in workmanship.</p>
                
                <h2>Contact Information</h2>
                <p>If you have any questions about these Terms of Service, please contact us at legal@davidswoodfurniture.com</p>',
                'type' => 'policy',
                'is_featured' => false,
                'published_at' => now(),
                'sort_order' => 4,
            ],
            [
                'title' => 'Frequently Asked Questions',
                'slug' => 'faq',
                'content' => '<h1>Frequently Asked Questions</h1>
                
                <h2>General Questions</h2>
                
                <h3>Do you offer custom furniture?</h3>
                <p>Yes! We specialize in custom furniture pieces. Contact us to discuss your specific needs and get a quote.</p>
                
                <h3>What materials do you use?</h3>
                <p>We use sustainably sourced hardwoods including oak, maple, cherry, and walnut. All materials are carefully selected for quality and durability.</p>
                
                <h3>How long does it take to make custom furniture?</h3>
                <p>Custom pieces typically take 4-8 weeks depending on complexity and current order volume.</p>
                
                <h2>Shipping & Delivery</h2>
                
                <h3>Do you ship nationwide?</h3>
                <p>Yes, we ship to all 50 states. Shipping costs vary based on size and weight of the item.</p>
                
                <h3>Do you offer white-glove delivery?</h3>
                <p>Yes, we offer white-glove delivery service for larger items. This includes delivery to the room of your choice and assembly if needed.</p>
                
                <h3>What if my furniture arrives damaged?</h3>
                <p>We carefully package all items, but if damage occurs during shipping, please contact us immediately. We\'ll arrange for repair or replacement.</p>
                
                <h2>Care & Maintenance</h2>
                
                <h3>How do I care for my wood furniture?</h3>
                <p>Dust regularly with a soft cloth and use a quality wood polish monthly. Avoid placing in direct sunlight or near heat sources.</p>
                
                <h3>Do you offer refinishing services?</h3>
                <p>Yes, we offer refinishing services for our furniture. Contact us for more information and pricing.</p>',
                'type' => 'faq',
                'is_featured' => false,
                'published_at' => now(),
                'sort_order' => 5,
            ],
            [
                'title' => 'The Art of Handcrafted Furniture',
                'slug' => 'art-of-handcrafted-furniture',
                'content' => '<h1>The Art of Handcrafted Furniture</h1>
                <p>In a world of mass production, there\'s something special about furniture that\'s been carefully crafted by hand. At David\'s Wood Furniture, we believe that every piece tells a story.</p>
                
                <h2>The Craftsmanship Process</h2>
                <p>Our master craftsmen follow time-honored techniques passed down through generations. Each piece begins with carefully selected wood, chosen for its grain pattern, color, and character.</p>
                
                <h2>Attention to Detail</h2>
                <p>From the initial design to the final finish, every step is executed with precision and care. We believe that the smallest details make the biggest difference.</p>
                
                <h2>Sustainable Practices</h2>
                <p>We\'re committed to sustainable practices, using only responsibly sourced wood and environmentally friendly finishes. Our furniture is built to last for generations.</p>
                
                <h2>Custom Design Process</h2>
                <p>When you choose custom furniture, you\'re not just buying a piece - you\'re participating in the creation process. We work closely with you to bring your vision to life.</p>',
                'type' => 'blog',
                'is_featured' => true,
                'published_at' => now()->subDays(5),
                'sort_order' => 6,
            ],
        ];

        foreach ($cmsPages as $page) {
            $page['created_by'] = 1; // Admin user ID
            CmsPage::create($page);
        }

        $this->command->info('CMS pages seeded successfully.');
    }
}
