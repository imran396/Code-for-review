<?php
/**
 * Trait for BidIncrementDeleter
 *
 * SAM-4474: Modules for Bid Increments
 *
 * @author        Victor Pautoff
 * @since         Oct 17, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidding\BidIncrement\Delete;

/**
 * Trait BidIncrementDeleterAwareTrait
 * @package Sam\Bidding\BidIncrement\Delete
 */
trait BidIncrementDeleterAwareTrait
{
    /**
     * @var BidIncrementDeleter|null
     */
    protected ?BidIncrementDeleter $bidIncrementDeleter = null;

    /**
     * @return BidIncrementDeleter
     */
    protected function getBidIncrementDeleter(): BidIncrementDeleter
    {
        if ($this->bidIncrementDeleter === null) {
            $this->bidIncrementDeleter = BidIncrementDeleter::new();
        }
        return $this->bidIncrementDeleter;
    }

    /**
     * @param BidIncrementDeleter $bidIncrementDeleter
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBidIncrementDeleter(BidIncrementDeleter $bidIncrementDeleter): static
    {
        $this->bidIncrementDeleter = $bidIncrementDeleter;
        return $this;
    }
}
