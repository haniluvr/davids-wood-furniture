<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'username' => 'required|string|max:50|unique:users,username,' . $userId,
            'email' => 'required|email|max:255|unique:users,email,' . $userId,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'email_verified_at' => 'nullable|date',
            'password' => $userId ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'password_confirmation' => $userId ? 'nullable|string|min:8' : 'required|string|min:8',
            'newsletter_subscribed' => 'boolean',
            'marketing_emails' => 'boolean',
            'sms_notifications' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Username is required.',
            'username.max' => 'Username cannot exceed 50 characters.',
            'username.unique' => 'This username is already taken.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'email.unique' => 'This email address is already registered.',
            'first_name.required' => 'First name is required.',
            'first_name.max' => 'First name cannot exceed 255 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.max' => 'Last name cannot exceed 255 characters.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'date_of_birth.date' => 'Please enter a valid date of birth.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'gender.in' => 'Please select a valid gender.',
            'street.max' => 'Street address cannot exceed 255 characters.',
            'city.max' => 'City cannot exceed 255 characters.',
            'state.max' => 'State cannot exceed 255 characters.',
            'postal_code.max' => 'Postal code cannot exceed 20 characters.',
            'country.max' => 'Country cannot exceed 255 characters.',
            'email_verified_at.date' => 'Please enter a valid email verification date.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.required' => 'Password confirmation is required.',
            'password_confirmation.min' => 'Password confirmation must be at least 8 characters.'
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'username',
            'email' => 'email address',
            'first_name' => 'first name',
            'last_name' => 'last name',
            'phone' => 'phone number',
            'date_of_birth' => 'date of birth',
            'gender' => 'gender',
            'street' => 'street address',
            'city' => 'city',
            'state' => 'state',
            'postal_code' => 'postal code',
            'country' => 'country',
            'is_active' => 'active status',
            'email_verified_at' => 'email verification date',
            'password' => 'password',
            'password_confirmation' => 'password confirmation',
            'newsletter_subscribed' => 'newsletter subscription',
            'marketing_emails' => 'marketing emails',
            'sms_notifications' => 'SMS notifications'
        ];
    }
}
