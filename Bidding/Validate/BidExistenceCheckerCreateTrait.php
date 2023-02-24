<?php
/**
 * SAM-5350: Bid existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           8/17/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\Validate;

/**
 * Trait BidExistenceCheckerCreateTrait
 * @package
 */
trait BidExistenceCheckerCreateTrait
{
    /**
     * @var BidExistenceChecker|null
     */
    protected ?BidExistenceChecker $bidExistenceChecker = null;

    /**
     * @return BidExistenceChecker
     */
    protected function createBidExistenceChecker(): BidExistenceChecker
    {
        $bidExistenceChecker = $this->bidExistenceChecker ?: BidExistenceChecker::new();
        return $bidExistenceChecker;
    }

    /**
     * @param BidExistenceChecker $bidExistenceChecker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setBidExistenceChecker(BidExistenceChecker $bidExistenceChecker): static
    {
        $this->bidExistenceChecker = $bidExistenceChecker;
        return $this;
    }
}
