<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    protected $fillable = [
        'title','type','start_at','end_at','location','discount_percent','image','description'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function scopeUpcoming($q){ return $q->where('start_at','>=',now()); }
    public function scopePast($q){ return $q->where('start_at','<',now()); }

    public function getBadgeClassAttribute(): string
    {
        return $this->type === 'discount' ? 'bg-danger' : 'bg-primary';
    }
}
