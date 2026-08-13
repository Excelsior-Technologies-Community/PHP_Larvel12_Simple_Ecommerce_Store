<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\Http\Request;

class CmsPageController extends Controller
{
    public function show($slug)
    {
        $page = CmsPage::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('cms.show', compact('page'));
    }

    public function index()
    {
        $pages = CmsPage::latest()->paginate(10);
        return view('admin.cms-pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.cms-pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|unique:cms_pages,slug',
            'title' => 'required|string',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        CmsPage::create($request->all());

        return redirect()->route('admin.cms-pages.index')->with('success', 'Page created successfully');
    }

    public function edit(CmsPage $cmsPage)
    {
        return view('admin.cms-pages.edit', compact('cmsPage'));
    }

    public function update(Request $request, CmsPage $cmsPage)
    {
        $request->validate([
            'slug' => 'required|string|unique:cms_pages,slug,' . $cmsPage->id,
            'title' => 'required|string',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $cmsPage->update($request->all());

        return redirect()->route('admin.cms-pages.index')->with('success', 'Page updated successfully');
    }

    public function destroy(CmsPage $cmsPage)
    {
        $cmsPage->delete();
        return back()->with('success', 'Page deleted successfully');
    }
}
