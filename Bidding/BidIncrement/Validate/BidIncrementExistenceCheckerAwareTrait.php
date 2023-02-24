<?php
/**
 * Trait for BidIncrementExistenceChecker
 *
 * SAM-4474: Modules for Bid Increments
 *
 * @author        Victor Pautoff
 * @since         Oct 15, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\BidIncrement\Validate;

/**
 * Trait BidIncrementExistenceCheckerAwareTrait
 * @package Sam\Bidding\BidIncrement\Validate
 */
trait BidIncrementExistenceCheckerAwareTrait
{
    /**
     * @var BidIncrementExistenceChecker|null
     */
    protected ?BidIncrementExistenceChecker $bidIncrementExistenceChecker = null;

    /**
     * @return BidIncrementExistenceChecker
     */
    protected function getBidIncrementExistenceChecker(): BidIncrementExistenceChecker
    {
        if ($this->bidIncrementExistenceChecker === null) {
            $this->bidIncrementExistenceChecker = BidIncrementExistenceChecker::new();
        }
        return $this->bidIncrementExistenceChecker;
    }

    /**
     * @param BidIncrementExistenceChecker $bidIncrementExistenceChecker
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBidIncrementExistenceChecker(BidIncrementExistenceChecker $bidIncrementExistenceChecker): static
    {
        $this->bidIncrementExistenceChecker = $bidIncrementExistenceChecker;
        return $this;
    }
}
