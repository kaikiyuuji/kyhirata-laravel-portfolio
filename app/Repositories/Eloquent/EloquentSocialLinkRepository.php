<?php

namespace App\Repositories\Eloquent;

use App\Models\SocialLink;
use App\Repositories\Contracts\SocialLinkRepositoryInterface;

class EloquentSocialLinkRepository implements SocialLinkRepositoryInterface
{
    public function all()
    {
        return SocialLink::ordered()->get();
    }

    public function allVisible()
    {
        return SocialLink::visible()->ordered()->get();
    }

    public function findById(int $id)
    {
        return SocialLink::findOrFail($id);
    }

    public function create(array $data)
    {
        return SocialLink::create($data);
    }

    public function update(int $id, array $data)
    {
        $link = $this->findById($id);
        $link->update($data);
        return $link;
    }

    public function delete(int $id)
    {
        $link = $this->findById($id);
        return $link->delete();
    }
}
