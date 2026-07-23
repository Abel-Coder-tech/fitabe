<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Soutien extends Model
{
    protected $fillable = [
        'nom',
        'photo',
        'citation',
        'titre',
        'role_parrain',
    ];

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo ? asset('storage/' . $this->photo) : '';
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('id');
    }
}
