<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'url',
        'icon',
        'is_visible',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function getDisplayIconAttribute(): string
    {
        // Se houver algo no banco que já pareça uma classe devicon ou fa, usa direto
        if ($this->icon && (str_contains($this->icon, 'devicon') || str_contains($this->icon, 'fa'))) {
            return $this->icon;
        }

        // Mapeamento de fallback baseado na plataforma (Devicon)
        $map = [
            'github'    => 'devicon-github-original',
            'linkedin'  => 'devicon-linkedin-plain',
            'twitter'   => 'devicon-twitter-original',
            'x'         => 'devicon-twitter-original', // Devicon ainda usa twitter para X ou x-plain em versões novas
            'facebook'  => 'devicon-facebook-plain',
            'instagram' => 'devicon-instagram-plain',
            'youtube'   => 'devicon-youtube-plain',
        ];

        $key = strtolower($this->platform);
        return $map[$key] ?? ($this->icon ?: 'fas fa-link');
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
