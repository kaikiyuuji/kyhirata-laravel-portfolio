<?php

namespace App\Actions\Admin\Project;

use App\Repositories\Contracts\ProjectRepositoryInterface;

class ToggleProjectButtonAction
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository
    ) {}

    public function execute(int $projectId)
    {
        $project = $this->projectRepository->findById($projectId);
        
        return $this->projectRepository->update($projectId, [
            'show_project_button' => !$project->show_project_button
        ]);
    }
}
