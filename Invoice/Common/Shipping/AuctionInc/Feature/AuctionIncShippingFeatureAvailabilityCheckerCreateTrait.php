<?php
/**
 * SAM-9137: Auction-Inc Shipping Calculator feature adjustments for v3-5
 * SAM-1539: AuctionInc Shipping calculator integration (PBA)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Shipping\AuctionInc\Feature;

/**
 * Trait AuctionIncShippingFeatureAvailabilityCheckerCreateTrait
 * @package Sam\Invoice\Common\Shipping\AuctionInc\Feature
 */
trait AuctionIncShippingFeatureAvailabilityCheckerCreateTrait
{
    /**
     * @var AuctionIncShippingFeatureAvailabilityChecker|null
     */
    protected ?AuctionIncShippingFeatureAvailabilityChecker $auctionIncShippingFeatureAvailabilityChecker = null;

    /**
     * @return AuctionIncShippingFeatureAvailabilityChecker
     */
    protected function createAuctionIncShippingFeatureAvailabilityChecker(): AuctionIncShippingFeatureAvailabilityChecker
    {
        return $this->auctionIncShippingFeatureAvailabilityChecker ?: AuctionIncShippingFeatureAvailabilityChecker::new();
    }

    /**
     * @param AuctionIncShippingFeatureAvailabilityChecker $availabilityChecker
     * @return $this
     * @internal
     */
    public function setAuctionIncShippingFeatureAvailabilityChecker(AuctionIncShippingFeatureAvailabilityChecker $availabilityChecker): static
    {
        $this->auctionIncShippingFeatureAvailabilityChecker = $availabilityChecker;
        return $this;
    }
}
