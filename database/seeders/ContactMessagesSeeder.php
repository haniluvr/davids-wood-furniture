<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messageCount = 50;

        // Get all user IDs
        $userIds = User::pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->error('No users found. Please run FilipinoUserSeeder first.');

            return;
        }

        // Ensure we have at least 50 users, otherwise we'll use some users multiple times
        if (count($userIds) < $messageCount) {
            $this->command->warn('Only '.count($userIds).' users found. Some users will have multiple messages.');
        }

        // Message templates for variety
        $messageTemplates = [
            [
                'subjects' => ['Product Inquiry', 'Custom Order Question', 'Size Availability'],
                'messages' => [
                    'Hello, I\'m interested in purchasing one of your wooden bed frames. Could you provide more information about the dimensions and available finishes?',
                    'I saw your oak dining table and would like to know if you offer custom sizing options. I have a specific space requirement.',
                    'Do you have any king-sized beds in stock? I\'m looking for something in a dark wood finish.',
                    'Can you tell me about the warranty on your furniture pieces? What does it cover?',
                    'I\'m interested in your cabinet collection. What materials do you use for the drawers?',
                ],
            ],
            [
                'subjects' => ['Delivery Question', 'Shipping Inquiry', 'Delivery Time'],
                'messages' => [
                    'How long does delivery typically take to Metro Manila? I need the furniture by next month.',
                    'Do you offer delivery to Quezon City? What are the shipping costs?',
                    'Can I schedule a specific delivery date and time? I have limited availability.',
                    'What carriers do you use for shipping? Are the items insured during transit?',
                    'I live in a condo on the 15th floor. Do you offer installation or assembly services?',
                ],
            ],
            [
                'subjects' => ['Order Status', 'Order Inquiry', 'Order Update'],
                'messages' => [
                    'I placed an order last week (Order #ORD-2025-XXXX) and haven\'t received a confirmation email. Can you check the status?',
                    'When will my order be shipped? I ordered a sofa bed two weeks ago.',
                    'I need to update my shipping address for my pending order. How can I do that?',
                    'Can I cancel my order? I changed my mind about the furniture I purchased.',
                    'I received my order but one item was damaged during shipping. What should I do?',
                ],
            ],
            [
                'subjects' => ['Payment Question', 'Payment Issue', 'Refund Request'],
                'messages' => [
                    'I tried to pay using GCash but the payment failed. Can you help me process it?',
                    'I was charged twice for my order. Can you please refund the duplicate charge?',
                    'Do you accept credit card payments? I don\'t see the option during checkout.',
                    'I paid via bank transfer but haven\'t received confirmation. The reference number is XXXX.',
                    'Can I pay via Cash on Delivery for orders over 20,000 PHP?',
                ],
            ],
            [
                'subjects' => ['Product Complaint', 'Quality Issue', 'Defect Report'],
                'messages' => [
                    'I received my table last month and noticed some scratches on the surface. This wasn\'t mentioned in the description.',
                    'The chair I ordered has a loose leg. It wobbles when I sit on it. Can this be fixed or replaced?',
                    'The color of the furniture I received doesn\'t match what was shown on the website. It\'s much darker than expected.',
                    'I\'ve had my bed frame for 3 months and one of the slats is starting to crack. Is this normal wear?',
                    'The cabinet doors don\'t close properly. They seem misaligned. Can you send a replacement?',
                ],
            ],
            [
                'subjects' => ['Compliment', 'Thank You', 'Feedback'],
                'messages' => [
                    'I just wanted to say thank you! The furniture I ordered exceeded my expectations. The quality is excellent!',
                    'Your customer service team was very helpful in answering all my questions. Great experience overall.',
                    'I\'ve received many compliments on the dining set I purchased from you. Thank you for the beautiful craftsmanship.',
                    'The delivery was prompt and the furniture was well-packaged. Excellent service from start to finish!',
                    'I love my new bookshelf! It\'s exactly what I was looking for. Will definitely shop here again.',
                ],
            ],
            [
                'subjects' => ['Return Request', 'Exchange Request', 'RMA Inquiry'],
                'messages' => [
                    'I would like to return a chair I purchased. It doesn\'t fit well in my space. What\'s your return policy?',
                    'Can I exchange my table for a different size? I realized I need something smaller.',
                    'How do I initiate a return? I\'ve had the item for less than a week.',
                    'What\'s the process for returning furniture? Are there any restocking fees?',
                    'I received the wrong item. I ordered a coffee table but got an end table instead.',
                ],
            ],
            [
                'subjects' => ['General Question', 'Store Information', 'Contact Request'],
                'messages' => [
                    'Do you have a physical store I can visit to see the furniture in person?',
                    'What are your business hours? I\'d like to speak with someone about a bulk order.',
                    'I\'m an interior designer and interested in wholesale pricing. Do you offer trade discounts?',
                    'Are you hiring? I\'d love to work for a company that makes such beautiful furniture.',
                    'I saw your furniture featured in a magazine. Can you tell me more about your craftsmanship process?',
                ],
            ],
        ];

        // Status distribution for realism
        $statusOptions = ['new', 'read', 'responded', 'archived'];
        $statusWeights = [
            'new' => 20,      // 20% new messages
            'read' => 30,     // 30% read but not responded
            'responded' => 40, // 40% responded
            'archived' => 10,  // 10% archived
        ];

        $createdMessages = 0;
        $usedUserIds = [];
        $threeMonthsAgo = strtotime('-3 months');
        $now = time();

        while ($createdMessages < $messageCount) {
            // Get a random user (ensure we use 50 different users if possible)
            if (count($usedUserIds) < count($userIds)) {
                // Use users we haven't used yet
                $availableUserIds = array_diff($userIds, $usedUserIds);
                $userId = $availableUserIds[array_rand($availableUserIds)];
                $usedUserIds[] = $userId;
            } else {
                // All users used, pick randomly
                $userId = $userIds[array_rand($userIds)];
            }

            $user = User::find($userId);
            if (! $user) {
                continue;
            }

            // Pick a random message template
            $template = $messageTemplates[array_rand($messageTemplates)];
            $subject = $template['subjects'][array_rand($template['subjects'])];
            $message = $template['messages'][array_rand($template['messages'])];

            // Generate random date within past 3 months
            $createdAt = date('Y-m-d H:i:s', rand($threeMonthsAgo, $now));
            $updatedAt = date('Y-m-d H:i:s', rand(strtotime($createdAt), $now));

            // Determine status with weights
            $statusRand = rand(1, 100);
            $cumulative = 0;
            $status = 'new';
            foreach ($statusWeights as $stat => $weight) {
                $cumulative += $weight;
                if ($statusRand <= $cumulative) {
                    $status = $stat;

                    break;
                }
            }

            // Set read_at if status is read, responded, or archived
            $readAt = null;
            if (in_array($status, ['read', 'responded', 'archived'])) {
                $readAt = date('Y-m-d H:i:s', rand(strtotime($createdAt), $now));
            }

            // Some messages might have admin_notes if they're responded or archived
            $adminNotes = null;
            if (in_array($status, ['responded', 'archived'])) {
                $possibleNotes = [
                    'Customer issue resolved via email.',
                    'Followed up with customer. Awaiting response.',
                    'Replacement item shipped.',
                    'Refund processed successfully.',
                    'Customer satisfied with resolution.',
                    'Issue escalated to management.',
                ];
                $adminNotes = $possibleNotes[array_rand($possibleNotes)];
            }

            ContactMessage::create([
                'user_id' => $userId,
                'name' => $user->first_name.' '.$user->last_name,
                'email' => $user->email,
                'message' => $message,
                'status' => $status,
                'admin_notes' => $adminNotes,
                'read_at' => $readAt ? date('Y-m-d H:i:s', strtotime($readAt)) : null,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            $createdMessages++;
        }

        $this->command->info("Created {$createdMessages} contact messages successfully!");
        $this->command->info('Messages distributed across:');
        foreach ($statusOptions as $status) {
            $count = ContactMessage::where('status', $status)->count();
            $this->command->line("  - {$status}: {$count}");
        }
    }
}
