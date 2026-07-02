<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'nom',
        'email',
        'objet',
        'message',
        'lu',
    ];

    protected function casts(): array
    {
        return [
            'lu' => 'boolean',
        ];
    }

    public function getSujetAttribute(): ?string
    {
        return $this->objet;
    }

    public function scopeNonLu(Builder $query): Builder
    {
        return $query->where('lu', false);
    }
}
