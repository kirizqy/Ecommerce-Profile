<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'nullable|string',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'status'      => 'nullable|boolean',
            'order'       => 'nullable|integer',
        ]);

        // simpan gambar ke storage/app/public/sliders/...
        $data['image']   = $request->file('image')->store('sliders', 'public');
        $data['status']  = (bool) ($data['status'] ?? true);
        $data['order']   = $data['order'] ?? 0;

        Slider::create($data);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'title'       => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'status'      => 'nullable|boolean',
            'order'       => 'nullable|integer',
        ]);

        $data['status'] = (bool) ($data['status'] ?? $slider->status);
        $data['order']  = $data['order'] ?? $slider->order;

        if ($request->hasFile('image')) {
            // hapus gambar lama jika ada
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($data);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        if ($slider->image && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }
        $slider->delete();

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider berhasil dihapus.');
    }
}
