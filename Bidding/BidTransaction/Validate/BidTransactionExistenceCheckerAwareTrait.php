<?php
/**
 * SAM-4378: BidTransaction loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           8/8/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidTransaction\Validate;

/**
 * Trait BidTransactionExistenceCheckerAwareTrait
 * @package Sam\Bidding\BidTransaction\Validate
 */
trait BidTransactionExistenceCheckerAwareTrait
{
    /**
     * @var BidTransactionExistenceChecker|null
     */
    protected ?BidTransactionExistenceChecker $bidTransactionExistenceChecker = null;

    /**
     * @return BidTransactionExistenceChecker
     */
    protected function createBidTransactionExistenceChecker(): BidTransactionExistenceChecker
    {
        return $this->bidTransactionExistenceChecker ?: BidTransactionExistenceChecker::new();
    }

    /**
     * @param BidTransactionExistenceChecker $bidTransactionExistenceChecker
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBidTransactionExistenceChecker(BidTransactionExistenceChecker $bidTransactionExistenceChecker): static
    {
        $this->bidTransactionExistenceChecker = $bidTransactionExistenceChecker;
        return $this;
    }
}
