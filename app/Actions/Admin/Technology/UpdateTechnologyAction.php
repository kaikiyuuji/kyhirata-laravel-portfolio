<?php

namespace App\Actions\Admin\Technology;

use App\Repositories\Contracts\TechnologyRepositoryInterface;

class UpdateTechnologyAction
{
    public function __construct(
        protected TechnologyRepositoryInterface $technologyRepository
    ) {}

    public function execute(int $technologyId, array $data)
    {
        return $this->technologyRepository->update($technologyId, $data);
    }
}
