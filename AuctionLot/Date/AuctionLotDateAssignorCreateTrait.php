<?php
/**
 * SAM-6079: Implement lot start closing date
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Date;

/**
 * Trait AuctionLotDateAssignorCreateTrait
 * @package Sam\AuctionLot\Date
 */
trait AuctionLotDateAssignorCreateTrait
{
    /**
     * @var AuctionLotDateAssignor|null
     */
    protected ?AuctionLotDateAssignor $auctionLotDateAssignor = null;

    /**
     * @return AuctionLotDateAssignor
     */
    protected function createAuctionLotDateAssignor(): AuctionLotDateAssignor
    {
        return $this->auctionLotDateAssignor ?: AuctionLotDateAssignor::new();
    }

    /**
     * @param AuctionLotDateAssignor $auctionLotDateAssignor
     * @return static
     * @internal
     */
    public function setAuctionLotDateAssignor(AuctionLotDateAssignor $auctionLotDateAssignor): static
    {
        $this->auctionLotDateAssignor = $auctionLotDateAssignor;
        return $this;
    }
}
