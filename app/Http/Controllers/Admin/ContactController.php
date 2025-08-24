<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // LIST (server-side pagination)
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $contacts = Contact::query()
            ->when($q, function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('message', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(20)        // <- atur page size
            ->withQueryString();  // <- biar query (q) tetep kebawa

        return view('admin.contacts.index', compact('contacts', 'q'));
    }

    // SHOW (opsional)
    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    // DESTROY
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Pesan dihapus.');
    }
}
