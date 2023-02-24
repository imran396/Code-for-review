<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserWatchlist;

trait UserWatchlistDeleteRepositoryCreateTrait
{
    protected ?UserWatchlistDeleteRepository $userWatchlistDeleteRepository = null;

    protected function createUserWatchlistDeleteRepository(): UserWatchlistDeleteRepository
    {
        return $this->userWatchlistDeleteRepository ?: UserWatchlistDeleteRepository::new();
    }

    /**
     * @param UserWatchlistDeleteRepository $userWatchlistDeleteRepository
     * @return static
     * @internal
     */
    public function setUserWatchlistDeleteRepository(UserWatchlistDeleteRepository $userWatchlistDeleteRepository): static
    {
        $this->userWatchlistDeleteRepository = $userWatchlistDeleteRepository;
        return $this;
    }
}
