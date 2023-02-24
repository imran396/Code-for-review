<?php
/**
 * SAM-6672: User deleter for v3.5
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Delete;

/**
 * Trait UserDeleterCreateTrait
 * @package Sam\User\Delete
 */
trait UserDeleterCreateTrait
{
    protected ?UserDeleter $userDeleter = null;

    /**
     * @return UserDeleter
     */
    protected function createUserDeleter(): UserDeleter
    {
        return $this->userDeleter ?: UserDeleter::new();
    }

    /**
     * @param UserDeleter $userDeleter
     * @return $this
     * @internal
     */
    public function setUserDeleter(UserDeleter $userDeleter): static
    {
        $this->userDeleter = $userDeleter;
        return $this;
    }
}
