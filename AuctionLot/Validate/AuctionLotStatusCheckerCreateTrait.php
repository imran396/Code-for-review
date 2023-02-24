<?php
/**
 * SAM-6033: Implement lot start bidding date
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 30, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Validate;

/**
 * Trait AuctionLotStatusCheckerCreateTrait
 * @package Sam\AuctionLot\Validate
 */
trait AuctionLotStatusCheckerCreateTrait
{
    /**
     * @var AuctionLotStatusChecker|null
     */
    protected ?AuctionLotStatusChecker $auctionLotStatusChecker = null;

    /**
     * @return AuctionLotStatusChecker
     */
    protected function createAuctionLotStatusChecker(): AuctionLotStatusChecker
    {
        return $this->auctionLotStatusChecker ?: AuctionLotStatusChecker::new();
    }

    /**
     * @param AuctionLotStatusChecker $auctionLotStatusChecker
     * @return static
     * @internal
     */
    public function setAuctionLotStatusChecker(AuctionLotStatusChecker $auctionLotStatusChecker): static
    {
        $this->auctionLotStatusChecker = $auctionLotStatusChecker;
        return $this;
    }
}
