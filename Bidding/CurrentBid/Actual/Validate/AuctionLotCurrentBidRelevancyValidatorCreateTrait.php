<?php
/**
 * SAM-5620: Refactoring and unit tests for Actual Current Bid Detector module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\CurrentBid\Actual\Validate;

/**
 * Trait AuctionLotCurrentBidRelevancyValidatorCreateTrait
 * @package Sam\Bidding\CurrentBid\Actual\Validate
 */
trait AuctionLotCurrentBidRelevancyValidatorCreateTrait
{
    /**
     * @var AuctionLotCurrentBidRelevancyValidator|null
     */
    protected ?AuctionLotCurrentBidRelevancyValidator $auctionLotCurrentBidRelevancyValidator = null;

    /**
     * @return AuctionLotCurrentBidRelevancyValidator
     */
    protected function createAuctionLotCurrentBidRelevancyValidator(): AuctionLotCurrentBidRelevancyValidator
    {
        return $this->auctionLotCurrentBidRelevancyValidator ?: AuctionLotCurrentBidRelevancyValidator::new();
    }

    /**
     * @param AuctionLotCurrentBidRelevancyValidator $auctionLotCurrentBidRelevancyValidator
     * @return $this
     * @internal
     */
    public function setAuctionLotCurrentBidRelevancyValidator(AuctionLotCurrentBidRelevancyValidator $auctionLotCurrentBidRelevancyValidator): static
    {
        $this->auctionLotCurrentBidRelevancyValidator = $auctionLotCurrentBidRelevancyValidator;
        return $this;
    }
}
