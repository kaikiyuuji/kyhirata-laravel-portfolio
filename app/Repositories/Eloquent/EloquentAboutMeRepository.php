<?php

namespace App\Repositories\Eloquent;

use App\Models\AboutMe;
use App\Repositories\Contracts\AboutMeRepositoryInterface;

class EloquentAboutMeRepository implements AboutMeRepositoryInterface
{
    public function get()
    {
        return AboutMe::first();
    }

    public function update(array $data)
    {
        $aboutMe = $this->get();
        if ($aboutMe) {
            $aboutMe->update($data);
            return $aboutMe;
        }

        return AboutMe::create($data);
    }
}
