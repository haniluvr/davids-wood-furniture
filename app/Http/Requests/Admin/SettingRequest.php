<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];

        // General settings
        if ($this->has('general')) {
            $rules = array_merge($rules, [
                'site_name' => 'required|string|max:255',
                'site_description' => 'nullable|string|max:500',
                'site_keywords' => 'nullable|string|max:255',
                'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,ico|max:1024',
                'default_currency' => 'required|string|max:3',
                'default_language' => 'required|string|max:5',
                'timezone' => 'required|string|max:50',
                'date_format' => 'required|string|max:20',
                'time_format' => 'required|string|max:10',
                'items_per_page' => 'required|integer|min:1|max:100',
                'maintenance_mode' => 'boolean',
                'maintenance_message' => 'nullable|string|max:1000'
            ]);
        }

        // Email settings
        if ($this->has('email')) {
            $rules = array_merge($rules, [
                'mail_driver' => 'required|string|max:20',
                'mail_host' => 'required|string|max:255',
                'mail_port' => 'required|integer|min:1|max:65535',
                'mail_username' => 'nullable|string|max:255',
                'mail_password' => 'nullable|string|max:255',
                'mail_encryption' => 'nullable|string|max:10',
                'mail_from_address' => 'required|email|max:255',
                'mail_from_name' => 'required|string|max:255',
                'mail_reply_to' => 'nullable|email|max:255',
                'mail_test_email' => 'nullable|email|max:255'
            ]);
        }

        // Payment settings
        if ($this->has('payment')) {
            $rules = array_merge($rules, [
                'default_payment_method' => 'required|string|max:50',
                'payment_gateway_enabled' => 'boolean',
                'payment_gateway_mode' => 'required|in:sandbox,live',
                'payment_gateway_public_key' => 'nullable|string|max:500',
                'payment_gateway_secret_key' => 'nullable|string|max:500',
                'payment_gateway_webhook_secret' => 'nullable|string|max:500',
                'auto_capture_payments' => 'boolean',
                'payment_timeout' => 'required|integer|min:1|max:1440'
            ]);
        }

        // Shipping settings
        if ($this->has('shipping')) {
            $rules = array_merge($rules, [
                'default_shipping_method' => 'required|string|max:50',
                'free_shipping_threshold' => 'nullable|numeric|min:0',
                'shipping_calculation' => 'required|in:flat,weight,distance',
                'default_shipping_rate' => 'required|numeric|min:0',
                'shipping_origin_country' => 'required|string|max:255',
                'shipping_origin_state' => 'required|string|max:255',
                'shipping_origin_city' => 'required|string|max:255',
                'shipping_origin_postal_code' => 'required|string|max:20',
                'shipping_processing_time' => 'required|integer|min:0|max:30',
                'shipping_delivery_time' => 'required|integer|min:0|max:30'
            ]);
        }

        // Notification settings
        if ($this->has('notifications')) {
            $rules = array_merge($rules, [
                'email_notifications_enabled' => 'boolean',
                'sms_notifications_enabled' => 'boolean',
                'push_notifications_enabled' => 'boolean',
                'admin_email' => 'required|email|max:255',
                'admin_phone' => 'nullable|string|max:20',
                'low_stock_threshold' => 'required|integer|min:0|max:1000',
                'low_stock_notification_enabled' => 'boolean',
                'new_order_notification_enabled' => 'boolean',
                'new_review_notification_enabled' => 'boolean',
                'new_user_notification_enabled' => 'boolean'
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            // General settings
            'site_name.required' => 'Site name is required.',
            'site_name.max' => 'Site name cannot exceed 255 characters.',
            'site_description.max' => 'Site description cannot exceed 500 characters.',
            'site_keywords.max' => 'Site keywords cannot exceed 255 characters.',
            'site_logo.image' => 'Site logo must be an image file.',
            'site_logo.mimes' => 'Site logo must be in JPEG, PNG, JPG, GIF, or WebP format.',
            'site_logo.max' => 'Site logo cannot exceed 2MB.',
            'site_favicon.image' => 'Site favicon must be an image file.',
            'site_favicon.mimes' => 'Site favicon must be in JPEG, PNG, JPG, GIF, WebP, or ICO format.',
            'site_favicon.max' => 'Site favicon cannot exceed 1MB.',
            'default_currency.required' => 'Default currency is required.',
            'default_currency.max' => 'Currency code cannot exceed 3 characters.',
            'default_language.required' => 'Default language is required.',
            'default_language.max' => 'Language code cannot exceed 5 characters.',
            'timezone.required' => 'Timezone is required.',
            'timezone.max' => 'Timezone cannot exceed 50 characters.',
            'date_format.required' => 'Date format is required.',
            'date_format.max' => 'Date format cannot exceed 20 characters.',
            'time_format.required' => 'Time format is required.',
            'time_format.max' => 'Time format cannot exceed 10 characters.',
            'items_per_page.required' => 'Items per page is required.',
            'items_per_page.integer' => 'Items per page must be a whole number.',
            'items_per_page.min' => 'Items per page must be at least 1.',
            'items_per_page.max' => 'Items per page cannot exceed 100.',
            'maintenance_message.max' => 'Maintenance message cannot exceed 1000 characters.',

            // Email settings
            'mail_driver.required' => 'Mail driver is required.',
            'mail_driver.max' => 'Mail driver cannot exceed 20 characters.',
            'mail_host.required' => 'Mail host is required.',
            'mail_host.max' => 'Mail host cannot exceed 255 characters.',
            'mail_port.required' => 'Mail port is required.',
            'mail_port.integer' => 'Mail port must be a whole number.',
            'mail_port.min' => 'Mail port must be at least 1.',
            'mail_port.max' => 'Mail port cannot exceed 65535.',
            'mail_username.max' => 'Mail username cannot exceed 255 characters.',
            'mail_password.max' => 'Mail password cannot exceed 255 characters.',
            'mail_encryption.max' => 'Mail encryption cannot exceed 10 characters.',
            'mail_from_address.required' => 'From email address is required.',
            'mail_from_address.email' => 'Please enter a valid email address.',
            'mail_from_address.max' => 'From email address cannot exceed 255 characters.',
            'mail_from_name.required' => 'From name is required.',
            'mail_from_name.max' => 'From name cannot exceed 255 characters.',
            'mail_reply_to.email' => 'Please enter a valid reply-to email address.',
            'mail_reply_to.max' => 'Reply-to email address cannot exceed 255 characters.',
            'mail_test_email.email' => 'Please enter a valid test email address.',
            'mail_test_email.max' => 'Test email address cannot exceed 255 characters.',

            // Payment settings
            'default_payment_method.required' => 'Default payment method is required.',
            'default_payment_method.max' => 'Payment method name cannot exceed 50 characters.',
            'payment_gateway_mode.required' => 'Payment gateway mode is required.',
            'payment_gateway_mode.in' => 'Payment gateway mode must be sandbox or live.',
            'payment_gateway_public_key.max' => 'Public key cannot exceed 500 characters.',
            'payment_gateway_secret_key.max' => 'Secret key cannot exceed 500 characters.',
            'payment_gateway_webhook_secret.max' => 'Webhook secret cannot exceed 500 characters.',
            'payment_timeout.required' => 'Payment timeout is required.',
            'payment_timeout.integer' => 'Payment timeout must be a whole number.',
            'payment_timeout.min' => 'Payment timeout must be at least 1 minute.',
            'payment_timeout.max' => 'Payment timeout cannot exceed 1440 minutes.',

            // Shipping settings
            'default_shipping_method.required' => 'Default shipping method is required.',
            'default_shipping_method.max' => 'Shipping method name cannot exceed 50 characters.',
            'free_shipping_threshold.numeric' => 'Free shipping threshold must be a valid number.',
            'free_shipping_threshold.min' => 'Free shipping threshold cannot be negative.',
            'shipping_calculation.required' => 'Shipping calculation method is required.',
            'shipping_calculation.in' => 'Shipping calculation must be flat, weight, or distance.',
            'default_shipping_rate.required' => 'Default shipping rate is required.',
            'default_shipping_rate.numeric' => 'Default shipping rate must be a valid number.',
            'default_shipping_rate.min' => 'Default shipping rate cannot be negative.',
            'shipping_origin_country.required' => 'Shipping origin country is required.',
            'shipping_origin_country.max' => 'Shipping origin country cannot exceed 255 characters.',
            'shipping_origin_state.required' => 'Shipping origin state is required.',
            'shipping_origin_state.max' => 'Shipping origin state cannot exceed 255 characters.',
            'shipping_origin_city.required' => 'Shipping origin city is required.',
            'shipping_origin_city.max' => 'Shipping origin city cannot exceed 255 characters.',
            'shipping_origin_postal_code.required' => 'Shipping origin postal code is required.',
            'shipping_origin_postal_code.max' => 'Shipping origin postal code cannot exceed 20 characters.',
            'shipping_processing_time.required' => 'Shipping processing time is required.',
            'shipping_processing_time.integer' => 'Shipping processing time must be a whole number.',
            'shipping_processing_time.min' => 'Shipping processing time cannot be negative.',
            'shipping_processing_time.max' => 'Shipping processing time cannot exceed 30 days.',
            'shipping_delivery_time.required' => 'Shipping delivery time is required.',
            'shipping_delivery_time.integer' => 'Shipping delivery time must be a whole number.',
            'shipping_delivery_time.min' => 'Shipping delivery time cannot be negative.',
            'shipping_delivery_time.max' => 'Shipping delivery time cannot exceed 30 days.',

            // Notification settings
            'admin_email.required' => 'Admin email is required.',
            'admin_email.email' => 'Please enter a valid admin email address.',
            'admin_email.max' => 'Admin email cannot exceed 255 characters.',
            'admin_phone.max' => 'Admin phone cannot exceed 20 characters.',
            'low_stock_threshold.required' => 'Low stock threshold is required.',
            'low_stock_threshold.integer' => 'Low stock threshold must be a whole number.',
            'low_stock_threshold.min' => 'Low stock threshold cannot be negative.',
            'low_stock_threshold.max' => 'Low stock threshold cannot exceed 1000.'
        ];
    }

    public function attributes()
    {
        return [
            'site_name' => 'site name',
            'site_description' => 'site description',
            'site_keywords' => 'site keywords',
            'site_logo' => 'site logo',
            'site_favicon' => 'site favicon',
            'default_currency' => 'default currency',
            'default_language' => 'default language',
            'timezone' => 'timezone',
            'date_format' => 'date format',
            'time_format' => 'time format',
            'items_per_page' => 'items per page',
            'maintenance_mode' => 'maintenance mode',
            'maintenance_message' => 'maintenance message',
            'mail_driver' => 'mail driver',
            'mail_host' => 'mail host',
            'mail_port' => 'mail port',
            'mail_username' => 'mail username',
            'mail_password' => 'mail password',
            'mail_encryption' => 'mail encryption',
            'mail_from_address' => 'from email address',
            'mail_from_name' => 'from name',
            'mail_reply_to' => 'reply-to email',
            'mail_test_email' => 'test email',
            'default_payment_method' => 'default payment method',
            'payment_gateway_enabled' => 'payment gateway enabled',
            'payment_gateway_mode' => 'payment gateway mode',
            'payment_gateway_public_key' => 'public key',
            'payment_gateway_secret_key' => 'secret key',
            'payment_gateway_webhook_secret' => 'webhook secret',
            'auto_capture_payments' => 'auto capture payments',
            'payment_timeout' => 'payment timeout',
            'default_shipping_method' => 'default shipping method',
            'free_shipping_threshold' => 'free shipping threshold',
            'shipping_calculation' => 'shipping calculation',
            'default_shipping_rate' => 'default shipping rate',
            'shipping_origin_country' => 'shipping origin country',
            'shipping_origin_state' => 'shipping origin state',
            'shipping_origin_city' => 'shipping origin city',
            'shipping_origin_postal_code' => 'shipping origin postal code',
            'shipping_processing_time' => 'shipping processing time',
            'shipping_delivery_time' => 'shipping delivery time',
            'email_notifications_enabled' => 'email notifications enabled',
            'sms_notifications_enabled' => 'SMS notifications enabled',
            'push_notifications_enabled' => 'push notifications enabled',
            'admin_email' => 'admin email',
            'admin_phone' => 'admin phone',
            'low_stock_threshold' => 'low stock threshold',
            'low_stock_notification_enabled' => 'low stock notification enabled',
            'new_order_notification_enabled' => 'new order notification enabled',
            'new_review_notification_enabled' => 'new review notification enabled',
            'new_user_notification_enabled' => 'new user notification enabled'
        ];
    }
}
