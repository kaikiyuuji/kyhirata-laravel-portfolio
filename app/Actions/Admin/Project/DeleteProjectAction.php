<?php

namespace App\Actions\Admin\Project;

use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Services\FileStorageService;
use Illuminate\Support\Facades\DB;

class DeleteProjectAction
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository,
        protected FileStorageService $fileStorageService
    ) {}

    public function execute(int $projectId): void
    {
        DB::transaction(function () use ($projectId) {
            $project = $this->projectRepository->findById($projectId);

            if ($project->thumbnail_path) {
                $this->fileStorageService->delete($project->thumbnail_path);
            }

            $this->projectRepository->delete($projectId);
        });
    }
}
