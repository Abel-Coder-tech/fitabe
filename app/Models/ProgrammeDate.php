<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgrammeDate extends Model
{
    protected $fillable = [
        'programme_id',
        'titre',
        'date',
        'lieu',
        'ordre',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'ordre' => 'integer',
        ];
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programmes::class, 'programme_id');
    }
}