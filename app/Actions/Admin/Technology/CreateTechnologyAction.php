<?php

namespace App\Actions\Admin\Technology;

use App\Repositories\Contracts\TechnologyRepositoryInterface;

class CreateTechnologyAction
{
    public function __construct(
        protected TechnologyRepositoryInterface $technologyRepository
    ) {}

    public function execute(array $data)
    {
        return $this->technologyRepository->create($data);
    }
}
