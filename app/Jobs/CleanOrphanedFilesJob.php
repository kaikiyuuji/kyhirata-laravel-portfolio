<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanOrphanedFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Executar o job: listar thumbnails no storage e remover as órfãs.
     */
    public function handle(): void
    {
        $disk = Storage::disk('public');
        $directory = 'projects';

        if (! $disk->exists($directory)) {
            return;
        }

        $files = $disk->files($directory);

        // Buscar todos os thumbnail_path referenciados no banco
        $referencedPaths = Project::whereNotNull('thumbnail_path')
            ->pluck('thumbnail_path')
            ->toArray();

        $deletedCount = 0;

        foreach ($files as $file) {
            if (! in_array($file, $referencedPaths)) {
                $disk->delete($file);
                $deletedCount++;
                Log::info("CleanOrphanedFilesJob: arquivo órfão removido [{$file}]");
            }
        }

        Log::info("CleanOrphanedFilesJob: limpeza finalizada. {$deletedCount} arquivo(s) removido(s) de {$directory}/.");
    }

    /**
     * Em caso de falha: logar sem interromper.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("CleanOrphanedFilesJob: falha na limpeza: {$exception->getMessage()}");
    }
}
