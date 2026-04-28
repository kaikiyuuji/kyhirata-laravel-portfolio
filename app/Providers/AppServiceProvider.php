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
        \Illuminate\Support\Facades\Gate::define('isAdmin', function ($user) {
            return $user->email === config('admin.email');
        });

        \Illuminate\Support\Facades\Gate::policy(\App\Models\Experience::class, \App\Policies\ExperiencePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Project::class, \App\Policies\ProjectPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Technology::class, \App\Policies\TechnologyPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\SocialLink::class, \App\Policies\SocialLinkPolicy::class);
    }
}
