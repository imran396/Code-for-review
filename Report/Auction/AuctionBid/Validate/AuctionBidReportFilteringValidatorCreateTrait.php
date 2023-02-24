<?php
/**
 * SAM-5771 User not able to download all bids report in Auctions page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/8/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\Report\Auction\AuctionBid\Validate;


/**
 * Trait AuctionBidReportFilteringValidatorCreateTrait
 * @package Sam\Report\Auction\AuctionBid\Validate
 */
trait AuctionBidReportFilteringValidatorCreateTrait
{
    protected ?AuctionBidReportFilteringValidator $auctionBidReportFilteringValidator = null;

    /**
     * @return AuctionBidReportFilteringValidator
     */
    protected function createAuctionBidReportFilteringValidator(): AuctionBidReportFilteringValidator
    {
        $auctionBidReportFilteringValidator = $this->auctionBidReportFilteringValidator ?: AuctionBidReportFilteringValidator::new();
        return $auctionBidReportFilteringValidator;
    }

    /**
     * @param AuctionBidReportFilteringValidator $auctionBidReportFilteringValidator
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAuctionBidReportFilteringValidator(AuctionBidReportFilteringValidator $auctionBidReportFilteringValidator): static
    {
        $this->auctionBidReportFilteringValidator = $auctionBidReportFilteringValidator;
        return $this;
    }
}
