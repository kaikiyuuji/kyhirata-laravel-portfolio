<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return Gate::allows('isAdmin');
    }

    public function view(User $user, Project $project): bool
    {
        return Gate::allows('isAdmin');
    }

    public function create(User $user): bool
    {
        return Gate::allows('isAdmin');
    }

    public function update(User $user, Project $project): bool
    {
        return Gate::allows('isAdmin');
    }

    public function delete(User $user, Project $project): bool
    {
        return Gate::allows('isAdmin');
    }
}
