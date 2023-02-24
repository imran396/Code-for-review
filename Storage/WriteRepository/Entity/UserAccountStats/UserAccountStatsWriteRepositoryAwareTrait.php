<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\UserAccountStats;

trait UserAccountStatsWriteRepositoryAwareTrait
{
    protected ?UserAccountStatsWriteRepository $userAccountStatsWriteRepository = null;

    protected function getUserAccountStatsWriteRepository(): UserAccountStatsWriteRepository
    {
        if ($this->userAccountStatsWriteRepository === null) {
            $this->userAccountStatsWriteRepository = UserAccountStatsWriteRepository::new();
        }
        return $this->userAccountStatsWriteRepository;
    }

    /**
     * @param UserAccountStatsWriteRepository $userAccountStatsWriteRepository
     * @return static
     * @internal
     */
    public function setUserAccountStatsWriteRepository(UserAccountStatsWriteRepository $userAccountStatsWriteRepository): static
    {
        $this->userAccountStatsWriteRepository = $userAccountStatsWriteRepository;
        return $this;
    }
}
