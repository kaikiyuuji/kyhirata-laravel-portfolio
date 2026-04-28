<?php

namespace App\Actions\Admin\SocialLink;

use App\Repositories\Contracts\SocialLinkRepositoryInterface;

class UpdateSocialLinkAction
{
    public function __construct(
        protected SocialLinkRepositoryInterface $socialLinkRepository
    ) {}

    public function execute(int $socialLinkId, array $data)
    {
        return $this->socialLinkRepository->update($socialLinkId, $data);
    }
}
