<?php
/**
 * SAM-5624: Separate PrivilegeCheckersAwareTrait to traits per role
 * SAM-3560: Privilege checker class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           7/10/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Validate;

/**
 * Trait BidderPrivilegeCheckerAwareTrait
 * @package Sam\User\Privilege\Validate
 */
trait BidderPrivilegeCheckerAwareTrait
{
    protected ?BidderPrivilegeChecker $bidderPrivilegeChecker = null;

    /**
     * @return BidderPrivilegeChecker
     */
    protected function getBidderPrivilegeChecker(): BidderPrivilegeChecker
    {
        if ($this->bidderPrivilegeChecker === null) {
            $this->bidderPrivilegeChecker = BidderPrivilegeChecker::new();
        }
        return $this->bidderPrivilegeChecker;
    }

    /**
     * @param BidderPrivilegeChecker $bidderPrivilegeChecker
     * @return static
     */
    public function setBidderPrivilegeChecker(BidderPrivilegeChecker $bidderPrivilegeChecker): static
    {
        $this->bidderPrivilegeChecker = $bidderPrivilegeChecker;
        return $this;
    }
}
