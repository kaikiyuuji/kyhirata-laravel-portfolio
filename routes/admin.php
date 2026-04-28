<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AboutMeController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Admin\SocialLinkController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/about/edit', [AboutMeController::class, 'edit'])->name('about.edit');
Route::put('/about', [AboutMeController::class, 'update'])->name('about.update');

Route::resource('experiences', ExperienceController::class)->except(['show']);
Route::resource('projects', ProjectController::class)->except(['show']);
Route::resource('technologies', TechnologyController::class)->except(['show']);
Route::resource('social-links', SocialLinkController::class)->except(['show']);

Route::post('/projects/{project}/toggle', [ProjectController::class, 'toggle'])->name('projects.toggle');
