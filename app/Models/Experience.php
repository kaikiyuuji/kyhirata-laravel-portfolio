<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'role',
        'description',
        'started_at',
        'ended_at',
        'is_visible',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'ended_at' => 'date',
            'is_visible' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc');
    }
}
