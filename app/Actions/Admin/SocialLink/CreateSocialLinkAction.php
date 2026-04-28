<?php

namespace App\Actions\Admin\SocialLink;

use App\Repositories\Contracts\SocialLinkRepositoryInterface;

class CreateSocialLinkAction
{
    public function __construct(
        protected SocialLinkRepositoryInterface $socialLinkRepository
    ) {}

    public function execute(array $data)
    {
        return $this->socialLinkRepository->create($data);
    }
}
