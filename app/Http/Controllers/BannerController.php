<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'link' => 'nullable|url',
            'link_text' => 'nullable|string',
            'position' => 'required|string',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/banners'), $data['image']);
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'link' => 'nullable|url',
            'link_text' => 'nullable|string',
            'position' => 'required|string',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/banners'), $data['image']);
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return back()->with('success', 'Banner deleted successfully');
    }
}
