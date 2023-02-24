<?php
/**
 * SAM-9177: User entity-maker - Account related issues for v3-4, v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Common\DirectAccount;

/**
 * Trait UserMakerAccountDetectorAwareTrait
 * @package Sam\EntityMaker\User\Common\Account
 */
trait UserDirectAccountDetectorAwareTrait
{
    /**
     * @var UserDirectAccountDetector|null
     */
    protected ?UserDirectAccountDetector $userDirectAccountDetector = null;

    /**
     * @return UserDirectAccountDetector
     */
    protected function getUserDirectAccountDetector(): UserDirectAccountDetector
    {
        if ($this->userDirectAccountDetector === null) {
            $this->userDirectAccountDetector = UserDirectAccountDetector::new();
        }
        return $this->userDirectAccountDetector;
    }

    /**
     * @param UserDirectAccountDetector $userDirectAccountDetector
     * @return $this
     * @internal
     */
    public function setUserDirectAccountDetector(UserDirectAccountDetector $userDirectAccountDetector): static
    {
        $this->userDirectAccountDetector = $userDirectAccountDetector;
        return $this;
    }
}
