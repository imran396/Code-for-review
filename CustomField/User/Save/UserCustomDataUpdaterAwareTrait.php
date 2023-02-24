<?php

namespace Sam\CustomField\User\Save;

/**
 * Trait UserCustomDataUpdaterAwareTrait
 * @package Sam\CustomField\Auction\Save
 */
trait UserCustomDataUpdaterAwareTrait
{
    protected ?UserCustomDataUpdater $userCustomDataUpdater = null;

    /**
     * @return UserCustomDataUpdater
     */
    protected function getUserCustomDataUpdater(): UserCustomDataUpdater
    {
        if ($this->userCustomDataUpdater === null) {
            $this->userCustomDataUpdater = UserCustomDataUpdater::new();
        }
        return $this->userCustomDataUpdater;
    }

    /**
     * @param UserCustomDataUpdater $updater
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserCustomDataUpdater(UserCustomDataUpdater $updater): static
    {
        $this->userCustomDataUpdater = $updater;
        return $this;
    }
}
