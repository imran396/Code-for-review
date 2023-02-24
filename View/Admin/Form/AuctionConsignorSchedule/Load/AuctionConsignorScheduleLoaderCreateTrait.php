<?php
/**
 * SAM-5667: Extract logic for Auction lot info for Consignor Schedule page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionConsignorSchedule\Load;

/**
 * Trait AuctionConsignorScheduleLoaderCreateTrait
 * @package Sam\View\Admin\Form\AuctionConsignorSchedule\Load
 */
trait AuctionConsignorScheduleLoaderCreateTrait
{
    protected ?AuctionConsignorScheduleLoader $auctionConsignorScheduleLoader = null;

    /**
     * @return AuctionConsignorScheduleLoader
     */
    protected function createAuctionConsignorScheduleLoader(): AuctionConsignorScheduleLoader
    {
        return $this->auctionConsignorScheduleLoader ?: AuctionConsignorScheduleLoader::new();
    }

    /**
     * @param AuctionConsignorScheduleLoader $auctionConsignorScheduleLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionConsignorScheduleLoader(AuctionConsignorScheduleLoader $auctionConsignorScheduleLoader): static
    {
        $this->auctionConsignorScheduleLoader = $auctionConsignorScheduleLoader;
        return $this;
    }
}
