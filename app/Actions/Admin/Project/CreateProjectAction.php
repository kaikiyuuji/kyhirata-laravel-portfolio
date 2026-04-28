<?php

namespace App\Actions\Admin\Project;

use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Services\FileStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CreateProjectAction
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository,
        protected FileStorageService $fileStorageService
    ) {}

    public function execute(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Upload de thumbnail
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
                $data['thumbnail_path'] = $this->fileStorageService->store($data['thumbnail'], 'projects');
            }
            unset($data['thumbnail']);

            $technologyIds = $data['technology_ids'] ?? [];
            unset($data['technology_ids']);

            // Cria o registro no banco
            $project = $this->projectRepository->create($data);

            // Sincroniza relacionamentos
            if (!empty($technologyIds)) {
                $this->projectRepository->syncTechnologies($project->id, $technologyIds);
            }

            return $project;
        });
    }
}
