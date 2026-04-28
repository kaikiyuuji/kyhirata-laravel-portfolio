<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ExperienceRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TechnologyRepositoryInterface;

class DashboardController extends Controller
{
    public function index(
        ExperienceRepositoryInterface $expRepo,
        ProjectRepositoryInterface $projRepo,
        TechnologyRepositoryInterface $techRepo
    ) {
        return response()->json([
            'experiences_count' => $expRepo->all()->count(),
            'projects_count' => $projRepo->all()->count(),
            'technologies_count' => $techRepo->all()->count(),
        ]);
    }
}
