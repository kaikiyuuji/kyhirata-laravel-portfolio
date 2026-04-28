<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail_path',
        'github_url',
        'demo_url',
        'show_project_button',
        'is_visible',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'show_project_button' => 'boolean',
            'is_visible' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class, 'project_technology')
            ->withPivot('is_primary');
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
