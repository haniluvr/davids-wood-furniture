<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterMail;
use App\Mail\WelcomeMail;
use App\Mail\AbandonedCartMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\Request;

class EmailPreviewController extends Controller
{
    /**
     * Display email previews
     */
    public function index()
    {
        $emailTypes = [
            'order-created' => 'Order Confirmation',
            'order-status-changed' => 'Order Status Update',
            'low-stock' => 'Low Stock Alert',
            'new-review' => 'New Review Notification',
            'newsletter' => 'Newsletter',
            'welcome' => 'Welcome Email',
            'abandoned-cart' => 'Abandoned Cart',
        ];

        return view('admin.emails.preview', compact('emailTypes'));
    }

    /**
     * Preview specific email type
     */
    public function preview($type)
    {
        try {
            switch ($type) {
                case 'order-created':
                    return $this->previewOrderCreated();
                
                case 'order-status-changed':
                    return $this->previewOrderStatusChanged();
                
                case 'low-stock':
                    return $this->previewLowStock();
                
                case 'new-review':
                    return $this->previewNewReview();
                
                case 'newsletter':
                    return $this->previewNewsletter();
                
                case 'welcome':
                    return $this->previewWelcome();
                
                case 'abandoned-cart':
                    return $this->previewAbandonedCart();
                
                default:
                    abort(404, 'Email type not found');
            }
        } catch (\Exception $e) {
            abort(500, 'Error generating email preview: ' . $e->getMessage());
        }
    }

    /**
     * Preview order created email
     */
    private function previewOrderCreated()
    {
        $order = $this->getSampleOrder();
        $user = $this->getSampleUser();
        
        return view('emails.orders.created', compact('order', 'user'));
    }

    /**
     * Preview order status changed email
     */
    private function previewOrderStatusChanged()
    {
        $order = $this->getSampleOrder();
        $user = $this->getSampleUser();
        $newStatus = 'shipped';
        $oldStatus = 'processing';
        
        return view('emails.orders.status-changed', compact('order', 'user', 'newStatus', 'oldStatus'));
    }

    /**
     * Preview low stock email
     */
    private function previewLowStock()
    {
        $product = $this->getSampleProduct();
        
        return view('emails.inventory.low-stock', compact('product'));
    }

    /**
     * Preview new review email
     */
    private function previewNewReview()
    {
        $review = $this->getSampleReview();
        
        return view('emails.reviews.new-review', compact('review'));
    }

    /**
     * Preview newsletter email
     */
    private function previewNewsletter()
    {
        $subscriber = $this->getSampleUser();
        $featuredProducts = $this->getSampleProducts(3);
        $promotions = collect([]);
        
        return view('emails.marketing.newsletter', compact('subscriber', 'featuredProducts', 'promotions'));
    }

    /**
     * Preview welcome email
     */
    private function previewWelcome()
    {
        $user = $this->getSampleUser();
        
        return view('emails.marketing.welcome', compact('user'));
    }

    /**
     * Preview abandoned cart email
     */
    private function previewAbandonedCart()
    {
        $user = $this->getSampleUser();
        $cartItems = $this->getSampleCartItems();
        $cartTotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        return view('emails.marketing.abandoned-cart', compact('user', 'cartItems', 'cartTotal'));
    }

    /**
     * Get sample order data
     */
    private function getSampleOrder()
    {
        return (object) [
            'id' => 1,
            'order_number' => 'DW-2024-001',
            'status' => 'processing',
            'total_amount' => 1250.00,
            'subtotal' => 1100.00,
            'shipping_cost' => 50.00,
            'tax_amount' => 100.00,
            'discount_amount' => 0.00,
            'payment_method' => 'Credit Card',
            'shipping_address' => [
                'name' => 'John Doe',
                'address_line_1' => '123 Main Street',
                'address_line_2' => 'Apt 4B',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'United States',
                'phone' => '(555) 123-4567'
            ],
            'created_at' => now(),
            'updated_at' => now(),
            'user' => $this->getSampleUser(),
            'items' => $this->getSampleOrderItems()
        ];
    }

    /**
     * Get sample order items
     */
    private function getSampleOrderItems()
    {
        return collect([
            (object) [
                'id' => 1,
                'quantity' => 1,
                'price' => 800.00,
                'total_price' => 800.00,
                'product' => (object) [
                    'id' => 1,
                    'name' => 'Oak Dining Table',
                    'sku' => 'OAK-DT-001'
                ]
            ],
            (object) [
                'id' => 2,
                'quantity' => 4,
                'price' => 75.00,
                'total_price' => 300.00,
                'product' => (object) [
                    'id' => 2,
                    'name' => 'Oak Dining Chairs',
                    'sku' => 'OAK-DC-001'
                ]
            ]
        ]);
    }

    /**
     * Get sample user data
     */
    private function getSampleUser()
    {
        return (object) [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '(555) 123-4567'
        ];
    }

    /**
     * Get sample product data
     */
    private function getSampleProduct()
    {
        return (object) [
            'id' => 1,
            'name' => 'Oak Dining Table',
            'sku' => 'OAK-DT-001',
            'price' => 800.00,
            'stock_quantity' => 3,
            'low_stock_threshold' => 5,
            'is_active' => true,
            'category' => (object) [
                'name' => 'Dining Room'
            ],
            'updated_at' => now()
        ];
    }

    /**
     * Get sample products
     */
    private function getSampleProducts($count = 3)
    {
        $products = [];
        for ($i = 1; $i <= $count; $i++) {
            $products[] = (object) [
                'id' => $i,
                'name' => 'Sample Product ' . $i,
                'description' => 'This is a sample product description that showcases the quality and craftsmanship of our furniture.',
                'price' => 500.00 + ($i * 100),
                'sale_price' => $i === 2 ? 450.00 : null,
                'average_rating' => 4.5,
                'reviews_count' => 12,
                'images' => ['sample-image.jpg']
            ];
        }
        return collect($products);
    }

    /**
     * Get sample review data
     */
    private function getSampleReview()
    {
        return (object) [
            'id' => 1,
            'rating' => 5,
            'title' => 'Excellent Quality',
            'review' => 'This dining table is absolutely beautiful! The craftsmanship is outstanding and it looks perfect in our dining room.',
            'pros' => 'Beautiful design, sturdy construction, easy to assemble',
            'cons' => 'Slightly heavier than expected',
            'is_approved' => false,
            'created_at' => now(),
            'user' => $this->getSampleUser(),
            'product' => $this->getSampleProduct()
        ];
    }

    /**
     * Get sample cart items
     */
    private function getSampleCartItems()
    {
        return collect([
            (object) [
                'id' => 1,
                'quantity' => 1,
                'price' => 800.00,
                'product' => (object) [
                    'id' => 1,
                    'name' => 'Oak Dining Table',
                    'sku' => 'OAK-DT-001',
                    'images' => ['oak-dining-table.jpg']
                ]
            ],
            (object) [
                'id' => 2,
                'quantity' => 2,
                'price' => 150.00,
                'product' => (object) [
                    'id' => 2,
                    'name' => 'Oak Side Chairs',
                    'sku' => 'OAK-SC-001',
                    'images' => ['oak-side-chairs.jpg']
                ]
            ]
        ]);
    }
}


