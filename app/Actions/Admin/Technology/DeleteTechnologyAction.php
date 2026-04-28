<?php

namespace App\Actions\Admin\Technology;

use App\Repositories\Contracts\TechnologyRepositoryInterface;

class DeleteTechnologyAction
{
    public function __construct(
        protected TechnologyRepositoryInterface $technologyRepository
    ) {}

    public function execute(int $technologyId): void
    {
        $this->technologyRepository->delete($technologyId);
    }
}
