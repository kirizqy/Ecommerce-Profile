<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // (opsional) halaman form
    public function create()
    {
        return view('frontend.contact');
    }

    public function store(Request $request)
    {
        // Validasi & ambil HANYA field yang akan disimpan
        $data = $request->validate([
            'name'    => ['required','string','max:100'],
            'email'   => ['required','email','max:150'],
            'message' => ['required','string','max:2000'],
        ]);

        Contact::create($data); // BUKAN $request->all()

        return back()->with('success', 'Pesan kamu sudah terkirim. Terima kasih!');
    }
}
