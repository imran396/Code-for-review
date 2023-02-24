<?php
/**
 *
 * SAM-4744: UserSentLots entity loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-31
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Alert\SentLot\Load;

/**
 * Trait UserAlertSentLotLoaderAwareTrait
 * @package Sam\User\Alert\SentLot\Load
 */
trait UserAlertSentLotLoaderAwareTrait
{
    protected ?UserAlertSentLotLoader $userAlertSentLotLoader = null;

    /**
     * @return UserAlertSentLotLoader
     */
    protected function getUserAlertSentLotLoader(): UserAlertSentLotLoader
    {
        if ($this->userAlertSentLotLoader === null) {
            $this->userAlertSentLotLoader = UserAlertSentLotLoader::new();
        }
        return $this->userAlertSentLotLoader;
    }

    /**
     * @param UserAlertSentLotLoader $userAlertSentLotLoader
     * @return static
     * @internal
     */
    public function setUserAlertSentLotLoader(UserAlertSentLotLoader $userAlertSentLotLoader): static
    {
        $this->userAlertSentLotLoader = $userAlertSentLotLoader;
        return $this;
    }
}
