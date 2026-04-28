<?php

namespace App\Repositories\Eloquent;

use App\Models\Technology;
use App\Repositories\Contracts\TechnologyRepositoryInterface;

class EloquentTechnologyRepository implements TechnologyRepositoryInterface
{
    public function all()
    {
        return Technology::orderBy('name')->get();
    }

    public function findById(int $id)
    {
        return Technology::findOrFail($id);
    }

    public function findBySlug(string $slug)
    {
        return Technology::where('slug', $slug)->firstOrFail();
    }

    public function create(array $data)
    {
        return Technology::create($data);
    }

    public function update(int $id, array $data)
    {
        $technology = $this->findById($id);
        $technology->update($data);
        return $technology;
    }

    public function delete(int $id)
    {
        $technology = $this->findById($id);
        return $technology->delete();
    }
}
