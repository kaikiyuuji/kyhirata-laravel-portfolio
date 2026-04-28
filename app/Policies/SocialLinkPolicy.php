<?php

namespace App\Policies;

use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class SocialLinkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return Gate::allows('isAdmin');
    }

    public function view(User $user, SocialLink $socialLink): bool
    {
        return Gate::allows('isAdmin');
    }

    public function create(User $user): bool
    {
        return Gate::allows('isAdmin');
    }

    public function update(User $user, SocialLink $socialLink): bool
    {
        return Gate::allows('isAdmin');
    }

    public function delete(User $user, SocialLink $socialLink): bool
    {
        return Gate::allows('isAdmin');
    }
}
