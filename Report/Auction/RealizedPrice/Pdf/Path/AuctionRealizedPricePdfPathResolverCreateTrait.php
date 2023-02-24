<?php
/**
 * SAM-6367: Continue to refactor "PDF Prices Realized" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-23, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\RealizedPrice\Pdf\Path;

/**
 * Trait AuctionRealizedPricePdfPathResolverCreateTrait
 * @package Sam\Report\Auction\RealizedPrice\Pdf\Path
 */
trait AuctionRealizedPricePdfPathResolverCreateTrait
{
    /**
     * @var AuctionRealizedPricePdfPathResolver|null
     */
    protected ?AuctionRealizedPricePdfPathResolver $auctionRealizedPricePdfPathResolver = null;

    /**
     * @return AuctionRealizedPricePdfPathResolver
     */
    protected function createAuctionRealizedPricePdfPathResolver(): AuctionRealizedPricePdfPathResolver
    {
        $auctionRealizedPricePdfPathResolver = $this->auctionRealizedPricePdfPathResolver ?: AuctionRealizedPricePdfPathResolver::new();
        return $auctionRealizedPricePdfPathResolver;
    }

    /**
     * @param AuctionRealizedPricePdfPathResolver $auctionRealizedPricePdfPathResolver
     * @return $this
     */
    public function setAuctionRealizedPricePdfPathResolver(AuctionRealizedPricePdfPathResolver $auctionRealizedPricePdfPathResolver): static
    {
        $this->auctionRealizedPricePdfPathResolver = $auctionRealizedPricePdfPathResolver;
        return $this;
    }
}
