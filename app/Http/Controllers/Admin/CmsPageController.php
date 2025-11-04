<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CmsPageController extends Controller
{
    public function index(Request $request)
    {
        $query = CmsPage::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $cmsPages = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.cms-pages.index', compact('cmsPages'));
    }

    public function create()
    {
        return view('admin.cms-pages.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_pages,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'type' => 'required|in:page,blog,faq,policy',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);

            // Ensure slug is unique
            $originalSlug = $data['slug'];
            $counter = 1;
            while (CmsPage::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug.'-'.$counter;
                $counter++;
            }
        }

        // Set published_at if not provided and page is active
        if (empty($data['published_at']) && $data['is_active']) {
            $data['published_at'] = now();
        }

        $cmsPage = CmsPage::create($data);

        // Log the action
        AuditLog::logCreate(Auth::guard('admin')->user(), $cmsPage);

        return redirect()->to(admin_route('cms-pages.index'))
            ->with('success', 'CMS page created successfully.');
    }

    public function show(CmsPage $cmsPage)
    {
        return view('admin.cms-pages.show', compact('cmsPage'));
    }

    public function edit(CmsPage $cmsPage)
    {
        return view('admin.cms-pages.edit', compact('cmsPage'));
    }

    public function update(Request $request, CmsPage $cmsPage)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:cms_pages,slug,'.$cmsPage->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'type' => 'required|in:page,blog,faq,policy',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldValues = $cmsPage->toArray();
        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);

            // Ensure slug is unique (excluding current page)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (CmsPage::where('slug', $data['slug'])->where('id', '!=', $cmsPage->id)->exists()) {
                $data['slug'] = $originalSlug.'-'.$counter;
                $counter++;
            }
        }

        // Set published_at if not provided and page is being activated
        if (empty($data['published_at']) && $data['is_active'] && ! $cmsPage->is_active) {
            $data['published_at'] = now();
        }

        $cmsPage->update($data);

        // Log the action
        AuditLog::logUpdate(Auth::guard('admin')->user(), $cmsPage, $oldValues);

        return redirect()->to(admin_route('cms-pages.index'))
            ->with('success', 'CMS page updated successfully.');
    }

    public function destroy(CmsPage $cmsPage)
    {
        $oldValues = $cmsPage->toArray();

        // Log the action
        AuditLog::logDelete(Auth::guard('admin')->user(), $cmsPage);

        $cmsPage->delete();

        return redirect()->to(admin_route('cms-pages.index'))
            ->with('success', 'CMS page deleted successfully.');
    }

    public function toggleStatus(CmsPage $cmsPage)
    {
        $oldStatus = $cmsPage->is_active;
        $newStatus = ! $cmsPage->is_active;

        $updateData = ['is_active' => $newStatus];

        // Set published_at if activating for the first time
        if ($newStatus && ! $cmsPage->published_at) {
            $updateData['published_at'] = now();
        }

        $cmsPage->update($updateData);

        // Log the action
        AuditLog::log('cms_page_status_toggled', Auth::guard('admin')->user(), $cmsPage, ['is_active' => $oldStatus], ['is_active' => $cmsPage->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'CMS page status updated successfully.',
            'is_active' => $cmsPage->is_active,
        ]);
    }

    public function duplicate(CmsPage $cmsPage)
    {
        $newCmsPage = $cmsPage->replicate();
        $newCmsPage->title = $cmsPage->title.' (Copy)';
        $newCmsPage->slug = $cmsPage->slug.'-copy-'.time();
        $newCmsPage->is_active = false;
        $newCmsPage->published_at = null;
        $newCmsPage->save();

        // Log the action
        AuditLog::logCreate(Auth::guard('admin')->user(), $newCmsPage);

        return redirect()->to(admin_route('cms-pages.edit', $newCmsPage))
            ->with('success', 'CMS page duplicated successfully.');
    }

    public function preview(CmsPage $cmsPage)
    {
        return view('admin.cms-pages.preview', compact('cmsPage'));
    }

    public function generateSlug(Request $request)
    {
        $title = $request->input('title');
        if (empty($title)) {
            return response()->json(['slug' => '']);
        }

        $slug = Str::slug($title);

        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (CmsPage::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return response()->json(['slug' => $slug]);
    }

    public function blogs(Request $request)
    {
        $query = CmsPage::where('type', 'blog')->with(['creator', 'updater']);

        // Calculate statistics
        $totalBlogs = CmsPage::where('type', 'blog')->count();
        $publishedCount = CmsPage::where('type', 'blog')->where('is_active', true)->count();
        $draftCount = CmsPage::where('type', 'blog')->where('is_active', false)->count();
        $featuredCount = CmsPage::where('type', 'blog')->where('is_featured', true)->count();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'published':
                    $query->where('is_active', true);

                    break;
                case 'draft':
                    $query->where('is_active', false);

                    break;
                case 'featured':
                    $query->where('is_featured', true);

                    break;
            }
        }

        // Filter by date range
        if ($request->filled('date_range')) {
            $now = now();
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());

                    break;
                case 'week':
                    $query->where('created_at', '>=', $now->subWeek());

                    break;
                case 'month':
                    $query->where('created_at', '>=', $now->subMonth());

                    break;
            }
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $blogs = $query->paginate(15)->withQueryString();

        return view('admin.blogs.index', compact('blogs', 'totalBlogs', 'publishedCount', 'draftCount', 'featuredCount'));
    }
}
