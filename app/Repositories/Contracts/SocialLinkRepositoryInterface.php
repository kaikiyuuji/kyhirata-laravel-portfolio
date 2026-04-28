<?php

namespace App\Repositories\Contracts;

interface SocialLinkRepositoryInterface
{
    public function all();
    public function allVisible();
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
