<?php

namespace App\Policies;

use App\Models\Technology;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class TechnologyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return Gate::allows('isAdmin');
    }

    public function view(User $user, Technology $technology): bool
    {
        return Gate::allows('isAdmin');
    }

    public function create(User $user): bool
    {
        return Gate::allows('isAdmin');
    }

    public function update(User $user, Technology $technology): bool
    {
        return Gate::allows('isAdmin');
    }

    public function delete(User $user, Technology $technology): bool
    {
        return Gate::allows('isAdmin');
    }
}
