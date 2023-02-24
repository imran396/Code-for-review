<?php
/**
 * SAM-4389: Problems with role permission check for lot custom field
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           8/29/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Access;

/**
 * Trait UnknownContextAccessCheckerAwareTrait
 * @package Sam\User\Access
 */
trait UnknownContextAccessCheckerAwareTrait
{
    protected ?UnknownContextAccessChecker $unknownContextAccessChecker = null;

    /**
     * @return UnknownContextAccessChecker
     */
    protected function getUnknownContextAccessChecker(): UnknownContextAccessChecker
    {
        if ($this->unknownContextAccessChecker === null) {
            $this->unknownContextAccessChecker = UnknownContextAccessChecker::new();
        }
        return $this->unknownContextAccessChecker;
    }

    /**
     * @param UnknownContextAccessChecker $unknownContextAccessChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUnknownContextAccessChecker(UnknownContextAccessChecker $unknownContextAccessChecker): static
    {
        $this->unknownContextAccessChecker = $unknownContextAccessChecker;
        return $this;
    }
}
