<?php

namespace App\Actions\Admin\SocialLink;

use App\Repositories\Contracts\SocialLinkRepositoryInterface;

class DeleteSocialLinkAction
{
    public function __construct(
        protected SocialLinkRepositoryInterface $socialLinkRepository
    ) {}

    public function execute(int $socialLinkId): void
    {
        $this->socialLinkRepository->delete($socialLinkId);
    }
}
