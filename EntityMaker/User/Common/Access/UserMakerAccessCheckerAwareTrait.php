<?php
/**
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Common\Access;

/**
 * Trait UserMakerAccessCheckerAwareTrait
 * @package Sam\EntityMaker\User\Common\Access
 */
trait UserMakerAccessCheckerAwareTrait
{
    /**
     * @var UserMakerAccessChecker|null
     */
    protected ?UserMakerAccessChecker $userMakerAccessChecker = null;

    /**
     * @return UserMakerAccessChecker
     * @internal
     */
    public function getUserMakerAccessChecker(): UserMakerAccessChecker
    {
        if ($this->userMakerAccessChecker === null) {
            $this->userMakerAccessChecker = UserMakerAccessChecker::new();
        }
        return $this->userMakerAccessChecker;
    }

    /**
     * @param UserMakerAccessChecker $userMakerAccessChecker
     * @return $this
     * @internal
     */
    public function setUserMakerAccessChecker(UserMakerAccessChecker $userMakerAccessChecker): static
    {
        $this->userMakerAccessChecker = $userMakerAccessChecker;
        return $this;
    }
}
