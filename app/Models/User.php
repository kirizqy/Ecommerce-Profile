<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh di-mass-assign.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',            // <-- tambah ini jika kadang create() dipakai
    ];

    /**
     * Kolom tersembunyi saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',      // good practice
    ];

    /**
     * Casting atribut.
     */
    protected $casts = [
        'password'            => 'hashed',
        'is_admin'            => 'boolean',   // <-- penting untuk middleware
        'email_verified_at'   => 'datetime',  // optional kalau pakai verifikasi
    ];
}
