<?php

namespace App\Actions\Admin\AboutMe;

use App\Repositories\Contracts\AboutMeRepositoryInterface;
use App\Services\FileStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class UpdateAboutMeAction
{
    public function __construct(
        protected AboutMeRepositoryInterface $aboutMeRepository,
        protected FileStorageService $fileStorageService
    ) {}

    public function execute(array $data)
    {
        return DB::transaction(function () use ($data) {
            $aboutMe = $this->aboutMeRepository->get();

            if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
                if ($aboutMe && $aboutMe->avatar_path) {
                    $this->fileStorageService->delete($aboutMe->avatar_path);
                }
                $data['avatar_path'] = $this->fileStorageService->store($data['avatar'], 'aboutme');
            }
            unset($data['avatar']);

            return $this->aboutMeRepository->update($data);
        });
    }
}
