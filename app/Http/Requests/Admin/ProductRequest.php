<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,'.$productId,
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|max:100|unique:products,sku,'.$productId,
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0|gt:price',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_digital' => 'boolean',
            'requires_shipping' => 'boolean',
            'track_quantity' => 'boolean',
            'allow_backorder' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp,avif|max:5120',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name cannot exceed 255 characters.',
            'slug.required' => 'Product slug is required.',
            'slug.unique' => 'This slug is already taken.',
            'description.required' => 'Product description is required.',
            'short_description.max' => 'Short description cannot exceed 500 characters.',
            'sku.required' => 'SKU is required.',
            'sku.unique' => 'This SKU is already taken.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'compare_price.gt' => 'Compare price must be greater than regular price.',
            'cost_price.numeric' => 'Cost price must be a valid number.',
            'cost_price.min' => 'Cost price cannot be negative.',
            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.integer' => 'Stock quantity must be a whole number.',
            'stock_quantity.min' => 'Stock quantity cannot be negative.',
            'low_stock_threshold.integer' => 'Low stock threshold must be a whole number.',
            'low_stock_threshold.min' => 'Low stock threshold cannot be negative.',
            'weight.numeric' => 'Weight must be a valid number.',
            'weight.min' => 'Weight cannot be negative.',
            'length.numeric' => 'Length must be a valid number.',
            'length.min' => 'Length cannot be negative.',
            'width.numeric' => 'Width must be a valid number.',
            'width.min' => 'Width cannot be negative.',
            'height.numeric' => 'Height must be a valid number.',
            'height.min' => 'Height cannot be negative.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category does not exist.',
            'meta_title.max' => 'Meta title cannot exceed 60 characters.',
            'meta_description.max' => 'Meta description cannot exceed 160 characters.',
            'meta_keywords.max' => 'Meta keywords cannot exceed 255 characters.',
            'images.array' => 'Images must be an array.',
            'images.max' => 'Maximum 10 images allowed.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Images must be in JPEG, PNG, JPG, GIF, WebP, or AVIF format.',
            'images.*.max' => 'Each image cannot exceed 5MB.',
            'tags.array' => 'Tags must be an array.',
            'tags.*.string' => 'Each tag must be a string.',
            'tags.*.max' => 'Each tag cannot exceed 50 characters.',
            'sort_order.integer' => 'Sort order must be a whole number.',
            'sort_order.min' => 'Sort order cannot be negative.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'product name',
            'slug' => 'product slug',
            'description' => 'product description',
            'short_description' => 'short description',
            'sku' => 'SKU',
            'price' => 'price',
            'compare_price' => 'compare price',
            'cost_price' => 'cost price',
            'stock_quantity' => 'stock quantity',
            'low_stock_threshold' => 'low stock threshold',
            'weight' => 'weight',
            'length' => 'length',
            'width' => 'width',
            'height' => 'height',
            'category_id' => 'category',
            'is_active' => 'active status',
            'is_featured' => 'featured status',
            'is_digital' => 'digital product',
            'requires_shipping' => 'requires shipping',
            'track_quantity' => 'track quantity',
            'allow_backorder' => 'allow backorder',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
            'meta_keywords' => 'meta keywords',
            'images' => 'product images',
            'tags' => 'product tags',
            'sort_order' => 'sort order',
        ];
    }
}
