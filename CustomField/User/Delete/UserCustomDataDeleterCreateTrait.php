<?php
/**
 * SAM-3632: User delete class
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

namespace Sam\CustomField\User\Delete;

/**
 * Trait UserCustomDataDeleterCreateTrait
 * @package Sam\CustomField\User\Delete
 */
trait UserCustomDataDeleterCreateTrait
{
    /**
     * @var UserCustomDataDeleter|null
     */
    protected ?UserCustomDataDeleter $userCustomDataDeleter = null;

    /**
     * @return UserCustomDataDeleter
     */
    protected function createUserCustomDataDeleter(): UserCustomDataDeleter
    {
        return $this->userCustomDataDeleter ?: UserCustomDataDeleter::new();
    }

    /**
     * @param UserCustomDataDeleter $userCustomDataDeleter
     * @return $this
     * @internal
     */
    public function setUserCustomDataDeleter(UserCustomDataDeleter $userCustomDataDeleter): static
    {
        $this->userCustomDataDeleter = $userCustomDataDeleter;
        return $this;
    }
}
