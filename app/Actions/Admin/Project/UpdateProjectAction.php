<?php

namespace App\Actions\Admin\Project;

use App\Jobs\ProcessThumbnailJob;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Services\FileStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class UpdateProjectAction
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository,
        protected FileStorageService $fileStorageService
    ) {}

    public function execute(int $projectId, array $data)
    {
        return DB::transaction(function () use ($projectId, $data) {
            $project = $this->projectRepository->findById($projectId);

            // Troca de thumbnail, caso tenha sido enviada
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
                if ($project->thumbnail_path) {
                    $this->fileStorageService->delete($project->thumbnail_path);
                }
                $data['thumbnail_path'] = $this->fileStorageService->store($data['thumbnail'], 'projects');
                ProcessThumbnailJob::dispatch($data['thumbnail_path']);
            }
            unset($data['thumbnail']);

            $technologyIds = $data['technology_ids'] ?? [];
            unset($data['technology_ids']);

            // Atualiza os dados base
            $updatedProject = $this->projectRepository->update($projectId, $data);

            // Sincroniza sempre (para permitir remover todas, se enviado um array vazio e for suportado pela regra de negócio, ou atualizar normalmente)
            if (isset($technologyIds)) {
                $this->projectRepository->syncTechnologies($projectId, $technologyIds);
            }

            return $updatedProject;
        });
    }
}
