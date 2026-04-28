<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessThumbnailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Número máximo de tentativas antes de marcar como falha.
     */
    public int $tries = 3;

    /**
     * Criar uma nova instância do job.
     */
    public function __construct(
        protected string $thumbnailPath,
        protected int $maxWidth = 800,
        protected int $maxHeight = 600,
        protected int $quality = 80,
    ) {}

    /**
     * Executar o job: redimensiona e otimiza a thumbnail usando GD.
     */
    public function handle(): void
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($this->thumbnailPath)) {
            Log::warning("ProcessThumbnailJob: arquivo não encontrado [{$this->thumbnailPath}]");
            return;
        }

        $absolutePath = $disk->path($this->thumbnailPath);
        $imageInfo = @getimagesize($absolutePath);

        if ($imageInfo === false) {
            Log::warning("ProcessThumbnailJob: não é uma imagem válida [{$this->thumbnailPath}]");
            return;
        }

        [$originalWidth, $originalHeight, $imageType] = $imageInfo;

        // Criar resource GD a partir do tipo de imagem
        $sourceImage = match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($absolutePath),
            IMAGETYPE_PNG  => @imagecreatefrompng($absolutePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($absolutePath),
            IMAGETYPE_GIF  => @imagecreatefromgif($absolutePath),
            default        => false,
        };

        if ($sourceImage === false) {
            Log::warning("ProcessThumbnailJob: falha ao ler imagem [{$this->thumbnailPath}]");
            return;
        }

        // Calcular novas dimensões mantendo aspect ratio
        $ratio = min($this->maxWidth / $originalWidth, $this->maxHeight / $originalHeight);

        // Só redimensiona se a imagem for maior que o limite
        if ($ratio >= 1) {
            imagedestroy($sourceImage);
            return;
        }

        $newWidth = (int) round($originalWidth * $ratio);
        $newHeight = (int) round($originalHeight * $ratio);

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preservar transparência para PNG e WEBP
        if (in_array($imageType, [IMAGETYPE_PNG, IMAGETYPE_WEBP])) {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
        }

        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        // Salvar no mesmo caminho, sobrescrevendo a original
        $saved = match ($imageType) {
            IMAGETYPE_JPEG => imagejpeg($resizedImage, $absolutePath, $this->quality),
            IMAGETYPE_PNG  => imagepng($resizedImage, $absolutePath, (int) round(9 - (9 * $this->quality / 100))),
            IMAGETYPE_WEBP => imagewebp($resizedImage, $absolutePath, $this->quality),
            IMAGETYPE_GIF  => imagegif($resizedImage, $absolutePath),
            default        => false,
        };

        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        if ($saved) {
            Log::info("ProcessThumbnailJob: thumbnail otimizada [{$this->thumbnailPath}] {$originalWidth}x{$originalHeight} → {$newWidth}x{$newHeight}");
        } else {
            Log::error("ProcessThumbnailJob: falha ao salvar imagem otimizada [{$this->thumbnailPath}]");
        }
    }

    /**
     * Em caso de falha definitiva: logar e manter a imagem original.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("ProcessThumbnailJob: falha ao processar [{$this->thumbnailPath}]: {$exception->getMessage()}");
    }
}
