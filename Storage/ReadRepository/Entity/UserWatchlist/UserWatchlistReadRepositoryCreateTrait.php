<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserWatchlist;

trait UserWatchlistReadRepositoryCreateTrait
{
    protected ?UserWatchlistReadRepository $userWatchlistReadRepository = null;

    protected function createUserWatchlistReadRepository(): UserWatchlistReadRepository
    {
        return $this->userWatchlistReadRepository ?: UserWatchlistReadRepository::new();
    }

    /**
     * @param UserWatchlistReadRepository $userWatchlistReadRepository
     * @return static
     * @internal
     */
    public function setUserWatchlistReadRepository(UserWatchlistReadRepository $userWatchlistReadRepository): static
    {
        $this->userWatchlistReadRepository = $userWatchlistReadRepository;
        return $this;
    }
}
