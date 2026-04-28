<?php

namespace App\Repositories\Contracts;

interface ProjectRepositoryInterface
{
    public function all();
    public function allVisible();
    public function findById(int $id);
    public function findBySlug(string $slug);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function syncTechnologies(int $projectId, array $technologyIds);
}
