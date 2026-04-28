<?php

namespace App\Actions\Admin\Experience;

use App\Repositories\Contracts\ExperienceRepositoryInterface;

class DeleteExperienceAction
{
    public function __construct(
        protected ExperienceRepositoryInterface $experienceRepository
    ) {}

    public function execute(int $experienceId): void
    {
        $this->experienceRepository->delete($experienceId);
    }
}
