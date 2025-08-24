<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::latest()->paginate(12);
        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request)
    {
        // Max 1GB; sesuaikan php.ini/nginx kalau perlu
        $data = $request->validate([
            'title'       => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'media'       => ['nullable','file','max:1024000',
                'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v'
            ],
            'poster'      => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        $g = new Gallery();
        $g->title       = $data['title'] ?? null;
        $g->description = $data['description'] ?? null;

        if ($request->hasFile('media')) {
            $path = $request->file('media')->store('gallery/media', 'public');
            $mime = $request->file('media')->getMimeType();
            $isVideo = str_starts_with($mime, 'video');

            $g->media = $path;
            $g->type  = $isVideo ? 'video' : 'image';

            // sinkron legacy image kalau file gambar
            if (!$isVideo) {
                $g->image = $g->media;
            }
        }

        if ($request->hasFile('poster')) {
            $g->poster = $request->file('poster')->store('gallery/posters', 'public');
        }

        $g->save();

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item has been created.');
    }

    public function destroy(Gallery $gallery)
    {
        foreach (['media','poster','image'] as $col) {
            if ($gallery->{$col}) Storage::disk('public')->delete($gallery->{$col});
        }
        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item has been deleted.');
    }
}
