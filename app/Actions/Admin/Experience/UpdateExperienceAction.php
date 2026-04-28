<?php

namespace App\Actions\Admin\Experience;

use App\Repositories\Contracts\ExperienceRepositoryInterface;

class UpdateExperienceAction
{
    public function __construct(
        protected ExperienceRepositoryInterface $experienceRepository
    ) {}

    public function execute(int $experienceId, array $data)
    {
        return $this->experienceRepository->update($experienceId, $data);
    }
}
