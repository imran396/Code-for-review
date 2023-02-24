<?php
/**
 * SAM-4378: BidTransaction loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           8/9/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidTransaction\Load;

/**
 * Trait BidTransactionLoaderAwareTrait
 * @package Sam\Bidding\BidTransaction\Load
 */
trait BidTransactionLoaderCreateTrait
{
    /**
     * @var BidTransactionLoader|null
     */
    protected ?BidTransactionLoader $bidTransactionLoader = null;

    /**
     * @return BidTransactionLoader
     */
    protected function createBidTransactionLoader(): BidTransactionLoader
    {
        return $this->bidTransactionLoader ?: BidTransactionLoader::new();
    }

    /**
     * @param BidTransactionLoader $bidTransactionLoader
     * @return static
     * @internal
     */
    public function setBidTransactionLoader(BidTransactionLoader $bidTransactionLoader): static
    {
        $this->bidTransactionLoader = $bidTransactionLoader;
        return $this;
    }
}
