<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'user_id' => 'nullable|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:2000',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
            'admin_response' => 'nullable|string|max:1000',
            'responded_by' => 'nullable|exists:admins,id',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'Selected product does not exist.',
            'user_id.exists' => 'Selected user does not exist.',
            'rating.required' => 'Rating is required.',
            'rating.integer' => 'Rating must be a whole number.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'review.required' => 'Review text is required.',
            'review.max' => 'Review cannot exceed 2000 characters.',
            'admin_response.max' => 'Admin response cannot exceed 1000 characters.',
            'responded_by.exists' => 'Selected admin does not exist.',
        ];
    }

    public function attributes()
    {
        return [
            'product_id' => 'product',
            'user_id' => 'customer',
            'rating' => 'rating',
            'review' => 'review text',
            'is_approved' => 'approval status',
            'is_featured' => 'featured status',
            'admin_response' => 'admin response',
            'responded_by' => 'responded by',
        ];
    }
}
