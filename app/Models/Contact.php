<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    // HANYA kolom yang ada di tabel
    protected $fillable = ['name', 'email', 'message'];
}
