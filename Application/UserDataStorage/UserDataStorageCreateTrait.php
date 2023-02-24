<?php
/**
 * SAM-8004: Refactor \Util_Storage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\UserDataStorage;

/**
 * Trait UserDataStorageCreateTrait
 * @package Sam\Application\UserDataStorage
 */
trait UserDataStorageCreateTrait
{
    /**
     * @var UserDataStorage|null
     */
    protected ?UserDataStorage $userDataStorage = null;

    /**
     * @return UserDataStorage
     */
    protected function createUserDataStorage(): UserDataStorage
    {
        return $this->userDataStorage ?: UserDataStorage::new();
    }

    /**
     * @param UserDataStorage $userDataStorage
     * @return static
     * @internal
     */
    public function setUserDataStorage(UserDataStorage $userDataStorage): static
    {
        $this->userDataStorage = $userDataStorage;
        return $this;
    }
}
