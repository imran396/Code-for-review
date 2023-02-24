<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\UserAccountStats;

trait UserAccountStatsReadRepositoryCreateTrait
{
    protected ?UserAccountStatsReadRepository $userAccountStatsReadRepository = null;

    protected function createUserAccountStatsReadRepository(): UserAccountStatsReadRepository
    {
        return $this->userAccountStatsReadRepository ?: UserAccountStatsReadRepository::new();
    }

    /**
     * @param UserAccountStatsReadRepository $userAccountStatsReadRepository
     * @return static
     * @internal
     */
    public function setUserAccountStatsReadRepository(UserAccountStatsReadRepository $userAccountStatsReadRepository): static
    {
        $this->userAccountStatsReadRepository = $userAccountStatsReadRepository;
        return $this;
    }
}
