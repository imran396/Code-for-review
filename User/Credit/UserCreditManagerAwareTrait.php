<?php
/**
 * Trait that implements user credit manager accessors
 *
 * SAM-4091: User credit manager class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           28 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Credit;

/**
 * Trait UserCreditManagerAwareTrait
 * @package Sam\User\Credit
 */
trait UserCreditManagerAwareTrait
{
    protected ?UserCreditManager $userCreditManager = null;

    /**
     * @return UserCreditManager
     */
    protected function getUserCreditManager(): UserCreditManager
    {
        if ($this->userCreditManager === null) {
            $this->userCreditManager = UserCreditManager::new();
        }
        return $this->userCreditManager;
    }

    /**
     * @param UserCreditManager $userCreditManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserCreditManager(UserCreditManager $userCreditManager): static
    {
        $this->userCreditManager = $userCreditManager;
        return $this;
    }
}
