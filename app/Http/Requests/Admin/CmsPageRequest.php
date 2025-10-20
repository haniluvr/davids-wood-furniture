<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CmsPageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $pageId = $this->route('cms_page') ? $this->route('cms_page')->id : null;

        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_pages,slug,'.$pageId,
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'show_in_menu' => 'boolean',
            'show_in_footer' => 'boolean',
            'template' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Page title is required.',
            'title.max' => 'Page title cannot exceed 255 characters.',
            'slug.required' => 'Page slug is required.',
            'slug.max' => 'Page slug cannot exceed 255 characters.',
            'slug.unique' => 'This slug is already taken.',
            'content.required' => 'Page content is required.',
            'excerpt.max' => 'Page excerpt cannot exceed 500 characters.',
            'meta_title.max' => 'Meta title cannot exceed 60 characters.',
            'meta_description.max' => 'Meta description cannot exceed 160 characters.',
            'meta_keywords.max' => 'Meta keywords cannot exceed 255 characters.',
            'template.max' => 'Template name cannot exceed 100 characters.',
            'sort_order.integer' => 'Sort order must be a whole number.',
            'sort_order.min' => 'Sort order cannot be negative.',
            'published_at.date' => 'Please enter a valid publication date.',
            'featured_image.image' => 'Featured image must be an image file.',
            'featured_image.mimes' => 'Featured image must be in JPEG, PNG, JPG, GIF, or WebP format.',
            'featured_image.max' => 'Featured image cannot exceed 5MB.',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'page title',
            'slug' => 'page slug',
            'content' => 'page content',
            'excerpt' => 'page excerpt',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
            'meta_keywords' => 'meta keywords',
            'is_active' => 'active status',
            'is_featured' => 'featured status',
            'show_in_menu' => 'show in menu',
            'show_in_footer' => 'show in footer',
            'template' => 'page template',
            'sort_order' => 'sort order',
            'published_at' => 'publication date',
            'featured_image' => 'featured image',
        ];
    }
}
