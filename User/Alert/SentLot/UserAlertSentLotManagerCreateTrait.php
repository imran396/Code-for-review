<?php
/**
 * SAM-6473: Move "my_search" table related logic to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Alert\SentLot;


/**
 * Trait UserAlertSentLotManagerCreateTrait
 * @package Sam\User\Alert\SentLot
 */
trait UserAlertSentLotManagerCreateTrait
{
    protected ?UserAlertSentLotManager $userAlertSentLotManager = null;

    /**
     * @return UserAlertSentLotManager
     */
    protected function createUserAlertSentLotManager(): UserAlertSentLotManager
    {
        return $this->userAlertSentLotManager ?: UserAlertSentLotManager::new();
    }

    /**
     * @param UserAlertSentLotManager $userAlertSentLotManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserAlertSentLotManager(UserAlertSentLotManager $userAlertSentLotManager): static
    {
        $this->userAlertSentLotManager = $userAlertSentLotManager;
        return $this;
    }
}
