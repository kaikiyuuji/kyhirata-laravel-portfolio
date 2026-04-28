<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function all()
    {
        return Project::ordered()->get();
    }

    public function allVisible()
    {
        return Project::visible()->ordered()->get();
    }

    public function findById(int $id)
    {
        return Project::findOrFail($id);
    }

    public function findBySlug(string $slug)
    {
        return Project::where('slug', $slug)->firstOrFail();
    }

    public function create(array $data)
    {
        return Project::create($data);
    }

    public function update(int $id, array $data)
    {
        $project = $this->findById($id);
        $project->update($data);
        return $project;
    }

    public function delete(int $id)
    {
        $project = $this->findById($id);
        return $project->delete();
    }

    public function syncTechnologies(int $projectId, array $technologyIds)
    {
        $project = $this->findById($projectId);
        $project->technologies()->sync($technologyIds);
    }
}
