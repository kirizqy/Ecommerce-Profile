<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * List berita + search.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $news = News::when($q, function ($qr) use ($q) {
                    $qr->where(function ($w) use ($q) {
                        $w->where('title', 'like', "%{$q}%")
                          ->orWhere('content', 'like', "%{$q}%"); // <-- pakai content
                    });
                })
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->paginate(15)
                ->withQueryString();

        return view('admin.news.index', compact('news', 'q'));
    }

    /**
     * Form create.
     */
    public function create()
    {
        $news = new News([
            'status'       => News::STATUS_DRAFT,
            'published_at' => now(), // default agar input datetime terisi
        ]);

        return view('admin.news.create', compact('news'));
    }

    /**
     * Simpan berita.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);      // sudah memetakan body -> content

        $data['status'] = strtolower($data['status']);
        $data['slug']   = $this->makeSlug($data['slug'] ?? null, $data['title']);

        if ($data['status'] === News::STATUS_PUBLISHED && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        News::create($data);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dibuat.');
    }

    /**
     * Form edit.
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update berita.
     */
    public function update(Request $request, News $news)
    {
        $data = $this->validated($request);      // sudah memetakan body -> content

        $data['status'] = strtolower($data['status']);
        $data['slug']   = $this->makeSlug($data['slug'] ?? null, $data['title'], $news->id);

        if ($data['status'] === News::STATUS_PUBLISHED && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $news->update($data);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Hapus berita.
     */
    public function destroy(News $news)
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }
        $news->delete();

        return back()->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Validasi request + map body -> content.
     */
    private function validated(Request $request): array
    {
        $v = $request->validate([
            'title'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255',
            'body'         => 'required|string',                // dari form editor
            'status'       => 'required|in:draft,published,Draft,Published',
            'published_at' => 'nullable|date',
            'image'        => 'nullable|image|max:2048',
        ]);

        // Map ke kolom DB
        $v['content'] = $v['body'];
        unset($v['body']);

        return $v;
    }

    /**
     * Buat slug unik dari judul jika slug kosong.
     */
    private function makeSlug(?string $slug, string $title, ?int $ignoreId = null): string
    {
        $base  = Str::slug($slug ?: $title) ?: 'post';
        $final = $base;
        $i     = 2;

        while (
            News::where('slug', $final)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $final = $base.'-'.$i;
            $i++;
        }

        return $final;
    }
}
