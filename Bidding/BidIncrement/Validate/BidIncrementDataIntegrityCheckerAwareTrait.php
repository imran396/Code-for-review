<?php
/**
 * Trait for BidIncrementDataIntegrityChecker
 *
 * SAM-5075: Data integrity checker - one bid increment table (eg live, or per auction) must have exactly one range
 * starting at zero
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/13/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidIncrement\Validate;

/**
 * Trait BidIncrementDataIntegrityCheckerAwareTrait
 * @package Sam\Bidding\BidIncrement\Validate
 */
trait BidIncrementDataIntegrityCheckerAwareTrait
{
    /**
     * @var BidIncrementDataIntegrityChecker|null
     */
    protected ?BidIncrementDataIntegrityChecker $bidIncrementDataIntegrityChecker = null;

    /**
     * @return BidIncrementDataIntegrityChecker
     */
    protected function getBidIncrementDataIntegrityChecker(): BidIncrementDataIntegrityChecker
    {
        if ($this->bidIncrementDataIntegrityChecker === null) {
            $this->bidIncrementDataIntegrityChecker = BidIncrementDataIntegrityChecker::new();
        }
        return $this->bidIncrementDataIntegrityChecker;
    }

    /**
     * @param BidIncrementDataIntegrityChecker $bidIncrementDataIntegrityChecker
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBidIncrementDataIntegrityChecker(BidIncrementDataIntegrityChecker $bidIncrementDataIntegrityChecker): static
    {
        $this->bidIncrementDataIntegrityChecker = $bidIncrementDataIntegrityChecker;
        return $this;
    }
}
