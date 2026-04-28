<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\AboutMeRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentAboutMeRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\ExperienceRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentExperienceRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\ProjectRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentProjectRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\SocialLinkRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentSocialLinkRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\TechnologyRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentTechnologyRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
