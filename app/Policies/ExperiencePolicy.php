<?php

namespace App\Policies;

use App\Models\Experience;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class ExperiencePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return Gate::allows('isAdmin');
    }

    public function view(User $user, Experience $experience): bool
    {
        return Gate::allows('isAdmin');
    }

    public function create(User $user): bool
    {
        return Gate::allows('isAdmin');
    }

    public function update(User $user, Experience $experience): bool
    {
        return Gate::allows('isAdmin');
    }

    public function delete(User $user, Experience $experience): bool
    {
        return Gate::allows('isAdmin');
    }
}
