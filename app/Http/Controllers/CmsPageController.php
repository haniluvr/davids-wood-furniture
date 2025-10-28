<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;

class CmsPageController extends Controller
{
    /**
     * Display the specified CMS page.
     */
    public function show(string $slug)
    {
        $page = CmsPage::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (! $page) {
            abort(404);
        }

        return view('cms.show', compact('page'));
    }
}
