<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\Product;
    use App\Models\Category;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Validation\Rule;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Str;

    class ProductController extends Controller
    {
        /** Batas jumlah gambar per produk */
        private int $MIN_IMAGES = 0;
        private int $MAX_IMAGES = 8;

        // LIST + FILTER
        public function index(Request $request)
        {
            $q          = trim((string) $request->get('q', ''));
            $categoryId = $request->get('category_id');

            $products = Product::with('category')
                ->when($q, fn ($qr) => $qr->where('name', 'like', "%{$q}%"))
                ->when($categoryId, fn ($qr) => $qr->where('category_id', $categoryId))
                ->latest()
                ->paginate(10)
                ->withQueryString();

            $categories = Category::orderBy('name')->get();

            return view('admin.products.index', compact('products', 'categories'));
        }

        // CREATE
            public function create()
            {
                $categories = Category::orderBy('name')->get();
                $product = null;
                return view('admin.products.create', compact('categories', 'product'));
            }

        // STORE — tanpa minimal, maksimal 8
        public function store(Request $request)
{
    // Validasi input
    $data = $request->validate([
        'name'           => ['required', 'string', 'max:255'],
        'category_id'    => ['required', Rule::exists('categories', 'id')],
        'description'    => ['nullable', 'string'],
        'shopee_link'    => ['nullable', 'url'],
        'tokopedia_link' => ['nullable', 'url'],
        'whatsapp_link'  => ['nullable', 'url'],
        'stock'          => ['required', 'integer', 'min:0'],
        'price'          => ['required', 'numeric', 'min:0'],

        'images'   => ['nullable', 'array', 'max:' . $this->MAX_IMAGES],
        'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'], // 10MB/file
    ]);

    foreach (['shopee_link','tokopedia_link','whatsapp_link'] as $k) {
        if (!filled($data[$k] ?? null)) $data[$k] = null;
    }

    // Ambil file upload
    $files = [];
    if ($request->hasFile('images')) {
        $raw   = $request->file('images');
        $files = $raw instanceof UploadedFile ? [$raw] : (is_array($raw) ? $raw : []);
    }

    // 🔎 Debugging log
    Log::info('PRODUCT STORE UPLOAD DEBUG', [
        'has_images' => $request->hasFile('images'),
        'count'      => count($files),
        'names'      => array_map(fn($f) => $f->getClientOriginalName(), $files),
        'sizes'      => array_map(fn($f) => $f->getSize(), $files),
        'all_files_keys' => array_keys($request->allFiles()),
    ]);

    if (count($files) > $this->MAX_IMAGES) {
        return back()->withErrors(['images' => 'Maksimal ' . $this->MAX_IMAGES . ' gambar.'])->withInput();
    }

    // Buat produk dulu
    $product = Product::create([
        'name'           => $data['name'],
        'category_id'    => $data['category_id'],
        'description'    => $data['description'] ?? null,
        'shopee_link'    => $data['shopee_link'],
        'tokopedia_link' => $data['tokopedia_link'],
        'whatsapp_link'  => $data['whatsapp_link'],
        'stock'          => $data['stock'],
        'price'          => $data['price'],
    ]);


            // Simpan file (rename aman)
            $paths = [];
            foreach ($files as $file) {
                $paths[] = $this->safeStore($file); // <-- pakai slug & timestamp
            }
            $paths = array_values(array_unique(array_filter($paths)));

            $product->image  = $paths[0] ?? null;
            $product->images = array_values(array_filter($paths, fn ($p) => $p !== ($product->image ?? '')));
            $product->save();

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil disimpan.');
        }

        // SHOW (opsional)
        public function show(Product $product)
        {
            $product->load('category');
            return view('admin.products.show', compact('product'));
        }

        // EDIT
        public function edit(Product $product)
        {
            $categories = Category::orderBy('name')->get();
            return view('admin.products.edit', compact('product', 'categories'));
        }

        // UPDATE — hanya maksimal 8 (total akhir)
        public function update(Request $request, Product $product)
        {
            $data = $request->validate([
                'name'           => ['required', 'string', 'max:255'],
                'category_id'    => ['required', Rule::exists('categories', 'id')],
                'description'    => ['nullable', 'string'],
                'shopee_link'    => ['nullable', 'url'],
                'tokopedia_link' => ['nullable', 'url'],
                'whatsapp_link'  => ['nullable', 'url'],
                'stock'          => ['required', 'integer', 'min:0'],
                'price'          => ['required', 'numeric', 'min:0'],

                'images'         => ['nullable', 'array', 'max:' . $this->MAX_IMAGES],
                'images.*'       => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],

                'remove_images'   => ['nullable', 'array'],
                'remove_images.*' => ['string'],
            ]);

            foreach (['shopee_link', 'tokopedia_link', 'whatsapp_link'] as $k) {
                if (!filled($data[$k] ?? null)) $data[$k] = null;
            }

            // Pool saat ini (cover + json)
            $current = [];
            if (!empty($product->image))    $current[] = $product->image;
            if (is_array($product->images)) $current   = array_merge($current, $product->images);
            $current = array_values(array_unique($current));

            // Normalisasi daftar hapus agar tidak pakai prefix /storage
            $toRemoveRequested = array_map(function ($v) {
                $v = str_replace('\\','/',$v);
                $v = ltrim($v, '/');
                return preg_replace('#^(public/)?(storage/)?#', '', $v) ?? $v;
            }, (array) $request->input('remove_images', []));

            $willRemove = array_values(array_intersect($current, $toRemoveRequested));

            // Ambil file baru
            $newFiles = [];
            if ($request->hasFile('images')) {
                $raw = $request->file('images');
                $newFiles = $raw instanceof UploadedFile ? [$raw] : (is_array($raw) ? $raw : []);
            }

            Log::info('PRODUCT UPDATE UPLOAD DEBUG', [
                'has_images' => $request->hasFile('images'),
                'incoming'   => count($newFiles),
                'remove'     => $willRemove,
                'curr_count' => count($current),
            ]);

            // Hitung final
            $finalCount = count($current) - count($willRemove) + count($newFiles);
            if ($finalCount > $this->MAX_IMAGES) {
                return back()
                    ->withErrors(['images' => 'Maksimal ' . $this->MAX_IMAGES . ' gambar per produk. Saat ini total: ' . $finalCount])
                    ->withInput();
            }

            // 1) Buang dari pool (belum hapus fisik)
            $pool = array_values(array_diff($current, $willRemove));

            // 2) Simpan file baru (rename aman) → tambah ke pool
            $newPaths = [];
            foreach ($newFiles as $file) {
                $newPaths[] = $this->safeStore($file); // <-- pakai slug & timestamp
            }
            $pool = array_values(array_unique(array_merge($pool, $newPaths)));

            // 3) Set cover & json
            $cover = $pool[0] ?? null;
            $json  = array_values(array_filter($pool, fn ($p) => $p !== $cover));

            $product->fill([
                'name'           => $data['name'],
                'category_id'    => $data['category_id'],
                'description'    => $data['description'] ?? null,
                'shopee_link'    => $data['shopee_link'],
                'tokopedia_link' => $data['tokopedia_link'],
                'whatsapp_link'  => $data['whatsapp_link'],
                'stock'          => $data['stock'],
                'price'          => $data['price'],
            ]);
            $product->image  = $cover;   // boleh null
            $product->images = $json;
            $product->save();

            // 4) Hapus fisik file yang dibuang
            foreach ($willRemove as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
        }

        // DESTROY
        public function destroy(Product $product)
        {
            $product->delete();
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
        }

        /* =================== Helpers =================== */

        /** Simpan file dengan nama aman (slug + timestamp), menghindari spasi/karakter aneh */
        private function safeStore(UploadedFile $file): string
        {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ext  = strtolower($file->getClientOriginalExtension());

            $safe = Str::slug($name, '-');
            $safe = trim($safe, '-');
            if ($safe === '') $safe = 'img';

            $final = now()->format('Ymd_His') . '-' . $safe . '.' . $ext; // contoh: 20250818_054313-show-1.jpg

            return $file->storeAs('products', $final, 'public'); // → products/....
        }
    }
