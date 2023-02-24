<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\UserAccountStats;

trait UserAccountStatsDeleteRepositoryCreateTrait
{
    protected ?UserAccountStatsDeleteRepository $userAccountStatsDeleteRepository = null;

    protected function createUserAccountStatsDeleteRepository(): UserAccountStatsDeleteRepository
    {
        return $this->userAccountStatsDeleteRepository ?: UserAccountStatsDeleteRepository::new();
    }

    /**
     * @param UserAccountStatsDeleteRepository $userAccountStatsDeleteRepository
     * @return static
     * @internal
     */
    public function setUserAccountStatsDeleteRepository(UserAccountStatsDeleteRepository $userAccountStatsDeleteRepository): static
    {
        $this->userAccountStatsDeleteRepository = $userAccountStatsDeleteRepository;
        return $this;
    }
}
