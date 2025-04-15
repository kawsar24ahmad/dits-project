<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('category')->latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'offer_price' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'boolean',
        ]);
        if ($request->hasFile('thumbnail')) {
            // Store new photo
            $filename =  uniqid() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $request->file('thumbnail')->move(public_path('thumbnails'), $filename);
            $validated['thumbnail'] = 'thumbnails/' . $filename;
        }

        $validated['slug'] = Str::slug($validated['title']);

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function show(string $id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.show', compact('service'));
    }

    public function edit(string $id)
    {
        $categories = Category::all();
        $service = Service::with('category')->findOrFail($id);
        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'offer_price' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            // delete old
            if ($service->thumbnail && file_exists($service->thumbnail)) {
                unlink(public_path($service->thumbnail));
            }
           // Store new photo
           $filename =  uniqid() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
           $request->file('thumbnail')->move(public_path('thumbnails'), $filename);
           $validated['thumbnail'] = 'thumbnails/' . $filename;
        }

        $validated['slug'] = Str::slug($validated['title']);

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        // delete old
        if ($service->thumbnail && file_exists($service->thumbnail)) {
            unlink(public_path($service->thumbnail));
        }
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
