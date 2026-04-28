<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ExperienceRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\SocialLinkRepositoryInterface;
use App\Repositories\Contracts\TechnologyRepositoryInterface;

class DashboardController extends Controller
{
    public function index(
        ExperienceRepositoryInterface $expRepo,
        ProjectRepositoryInterface $projRepo,
        TechnologyRepositoryInterface $techRepo,
        SocialLinkRepositoryInterface $socialRepo
    ) {
        $stats = [
            'experiences_count'  => $expRepo->all()->count(),
            'projects_count'     => $projRepo->all()->count(),
            'technologies_count' => $techRepo->all()->count(),
            'social_links_count' => $socialRepo->all()->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
