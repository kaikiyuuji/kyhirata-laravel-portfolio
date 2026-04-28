<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\AboutMeRepositoryInterface;
use App\Repositories\Contracts\ExperienceRepositoryInterface;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\SocialLinkRepositoryInterface;
use App\Repositories\Contracts\TechnologyRepositoryInterface;

class PortfolioController extends Controller
{
    public function index(
        AboutMeRepositoryInterface $aboutMeRepo,
        ExperienceRepositoryInterface $experienceRepo,
        ProjectRepositoryInterface $projectRepo,
        SocialLinkRepositoryInterface $socialLinkRepo,
        TechnologyRepositoryInterface $techRepo
    ) {
        return view('portfolio.index', [
            'aboutMe' => $aboutMeRepo->get(),
            'experiences' => $experienceRepo->allVisible(),
            'projects' => $projectRepo->allVisible(),
            'socialLinks' => $socialLinkRepo->allVisible(),
            'technologies' => $techRepo->all()
        ]);
    }
}
