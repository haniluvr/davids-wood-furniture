<?php

namespace App\Providers;

use App\Events\LowStockAlert;
use App\Events\NewCustomerMessage;
use App\Events\NewCustomerRegistered;
use App\Events\NewRefundRequest;
use App\Events\NewReview;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Events\RefundRequestApproved;
use App\Events\RefundRequestRejected;
use App\Listeners\CreateLowStockNotificationListener;
use App\Listeners\CreateMessageNotificationListener;
use App\Listeners\CreateNewCustomerNotificationListener;
use App\Listeners\CreateOrderNotificationListener;
use App\Listeners\CreateOrderStatusNotificationListener;
use App\Listeners\CreateRefundNotificationListener;
use App\Listeners\CreateReviewNotificationListener;
use App\Listeners\SendRefundApprovalNotificationListener;
use App\Listeners\SendRefundRejectionNotificationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        OrderCreated::class => [
            CreateOrderNotificationListener::class,
        ],
        OrderStatusChanged::class => [
            CreateOrderStatusNotificationListener::class,
        ],
        NewCustomerMessage::class => [
            CreateMessageNotificationListener::class,
        ],
        NewCustomerRegistered::class => [
            CreateNewCustomerNotificationListener::class,
        ],
        NewReview::class => [
            CreateReviewNotificationListener::class,
        ],
        LowStockAlert::class => [
            CreateLowStockNotificationListener::class,
        ],
        NewRefundRequest::class => [
            CreateRefundNotificationListener::class,
        ],
        RefundRequestApproved::class => [
            SendRefundApprovalNotificationListener::class,
        ],
        RefundRequestRejected::class => [
            SendRefundRejectionNotificationListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
