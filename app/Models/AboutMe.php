<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutMe extends Model
{

    protected $table = 'about_me';

    protected $fillable = [
        'name',
        'title',
        'bio',
        'avatar_path',
        'email',
        'location',
        'is_available_for_work',
    ];

    protected function casts(): array
    {
        return [
            'is_available_for_work' => 'boolean',
        ];
    }
}
