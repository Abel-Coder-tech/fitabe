<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'email', 'objet', 'message', 'adresse_ip', 'lu',
    ];

    protected function casts(): array
    {
        return [
            'lu' => 'boolean',
        ];
    }

    public function markAsRead(): void
    {
        $this->update(['lu' => true]);
    }

    public function scopeUnread(Builder $query)
    {
        return $query->where('lu', false);
    }

    // Alias français : nonLu = non lu
    public function scopeNonLu(Builder $query)
    {
        return $query->where('lu', false);
    }
}
