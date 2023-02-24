<?php
/**
 * SAM-4702: User Log modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.02.2019
 * file encoding    UTF-8
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Log\Delete;

/**
 * Trait UserLogDeleterAwareTrait
 * @package Sam\User\Log\Delete
 */
trait UserLogDeleterAwareTrait
{
    protected ?UserLogDeleter $userLogDeleter = null;

    /**
     * @return UserLogDeleter
     */
    protected function getUserLogDeleter(): UserLogDeleter
    {
        if ($this->userLogDeleter === null) {
            $this->userLogDeleter = UserLogDeleter::new();
        }
        return $this->userLogDeleter;
    }

    /**
     * @param UserLogDeleter $userLogDeleter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserLogDeleter(UserLogDeleter $userLogDeleter): static
    {
        $this->userLogDeleter = $userLogDeleter;
        return $this;
    }
}
