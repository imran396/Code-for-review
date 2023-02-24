<?php
/**
 * DI trait for On-increment Bid service
 *
 * SAM-6909: Refactor on-increment bid validator for v3.6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\OnIncrementBid;

/**
 * Trait OnIncrementBidCheckerAwareTrait
 * @package Sam\Bidding\OnIncrementBid
 */
trait OnIncrementBidServiceCreateTrait
{
    /**
     * @var OnIncrementBidService|null
     */
    protected ?OnIncrementBidService $onIncrementBidService = null;

    /**
     * @return OnIncrementBidService
     */
    protected function createOnIncrementBidService(): OnIncrementBidService
    {
        return $this->onIncrementBidService ?: OnIncrementBidService::new();
    }

    /**
     * @param OnIncrementBidService $onIncrementBidService
     * @return static
     * @internal
     */
    public function setOnIncrementBidService(OnIncrementBidService $onIncrementBidService): static
    {
        $this->onIncrementBidService = $onIncrementBidService;
        return $this;
    }
}
