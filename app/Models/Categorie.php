<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Categorie extends Model
{
    protected $fillable = ['nom', 'slug', 'description', 'ordre'];

    public function scopeOrdered(Builder $query)
    {
        return $query->orderBy('ordre');
    }
}
