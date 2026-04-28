<?php

namespace App\Actions\Admin\Experience;

use App\Repositories\Contracts\ExperienceRepositoryInterface;

class CreateExperienceAction
{
    public function __construct(
        protected ExperienceRepositoryInterface $experienceRepository
    ) {}

    public function execute(array $data)
    {
        return $this->experienceRepository->create($data);
    }
}
