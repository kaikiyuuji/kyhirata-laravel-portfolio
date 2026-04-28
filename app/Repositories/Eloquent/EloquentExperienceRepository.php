<?php

namespace App\Repositories\Eloquent;

use App\Models\Experience;
use App\Repositories\Contracts\ExperienceRepositoryInterface;

class EloquentExperienceRepository implements ExperienceRepositoryInterface
{
    public function all()
    {
        return Experience::ordered()->get();
    }

    public function allVisible()
    {
        return Experience::visible()->ordered()->get();
    }

    public function findById(int $id)
    {
        return Experience::findOrFail($id);
    }

    public function create(array $data)
    {
        return Experience::create($data);
    }

    public function update(int $id, array $data)
    {
        $experience = $this->findById($id);
        $experience->update($data);
        return $experience;
    }

    public function delete(int $id)
    {
        $experience = $this->findById($id);
        return $experience->delete();
    }
}
