<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CleanOrphanedFilesJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Limpeza de thumbnails órfãos — executa diariamente às 03:00
Schedule::job(new CleanOrphanedFilesJob)->dailyAt('03:00');
