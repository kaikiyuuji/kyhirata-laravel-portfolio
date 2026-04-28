<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Technology extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'icon',
    ];

    public function getDisplayIconAttribute(): string
    {
        // Se houver ícone cadastrado no banco, usa ele
        if ($this->icon) {
            return $this->icon;
        }

        // Mapeamento de fallback baseado no slug
        $map = [
            'laravel'    => 'devicon-laravel-plain',
            'php'        => 'devicon-php-plain',
            'javascript' => 'devicon-javascript-plain',
            'react'      => 'devicon-react-original',
            'vue'        => 'devicon-vuejs-plain',
            'tailwind'   => 'devicon-tailwindcss-original-wordmark',
            'mysql'      => 'devicon-mysql-plain',
            'git'        => 'devicon-git-plain',
            'docker'     => 'devicon-docker-plain',
            'node'       => 'devicon-nodejs-plain',
            'python'     => 'devicon-python-plain',
            'css'        => 'devicon-css3-plain',
            'html'       => 'devicon-html5-plain',
            'linux'      => 'devicon-linux-plain',
        ];

        return $map[$this->slug] ?? 'fas fa-code'; // Ícone genérico de código
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_technology')
            ->withPivot('is_primary');
    }
}
