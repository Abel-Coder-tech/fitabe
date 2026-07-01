<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Partenaires extends Model
{
    protected $fillable = [
        'nom',
        'logo',
        'site_web',
        'description',
        'ordre',
    ];

    protected function casts(): array
    {
        return [
            'ordre' => 'integer',
        ];
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : '';
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('ordre');
    }
}
