<?php
/**
 * SAM-6079: Implement lot start closing date
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Date;

/**
 * Trait AuctionLotDatesUpdaterCreateTrait
 * @package Sam\AuctionLot\Date
 */
trait AuctionLotDatesUpdaterCreateTrait
{
    /**
     * @var AuctionLotDatesUpdater|null
     */
    protected ?AuctionLotDatesUpdater $auctionLotDatesUpdater = null;

    /**
     * @return AuctionLotDatesUpdater
     */
    protected function createAuctionLotDatesUpdater(): AuctionLotDatesUpdater
    {
        return $this->auctionLotDatesUpdater ?: AuctionLotDatesUpdater::new();
    }

    /**
     * @param AuctionLotDatesUpdater $auctionLotDatesUpdater
     * @return static
     * @internal
     */
    public function setAuctionLotDatesUpdater(AuctionLotDatesUpdater $auctionLotDatesUpdater): static
    {
        $this->auctionLotDatesUpdater = $auctionLotDatesUpdater;
        return $this;
    }
}
