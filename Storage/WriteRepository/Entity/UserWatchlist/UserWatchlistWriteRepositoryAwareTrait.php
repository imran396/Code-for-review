<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserWatchlist;

trait UserWatchlistWriteRepositoryAwareTrait
{
    protected ?UserWatchlistWriteRepository $userWatchlistWriteRepository = null;

    protected function getUserWatchlistWriteRepository(): UserWatchlistWriteRepository
    {
        if ($this->userWatchlistWriteRepository === null) {
            $this->userWatchlistWriteRepository = UserWatchlistWriteRepository::new();
        }
        return $this->userWatchlistWriteRepository;
    }

    /**
     * @param UserWatchlistWriteRepository $userWatchlistWriteRepository
     * @return static
     * @internal
     */
    public function setUserWatchlistWriteRepository(UserWatchlistWriteRepository $userWatchlistWriteRepository): static
    {
        $this->userWatchlistWriteRepository = $userWatchlistWriteRepository;
        return $this;
    }
}
