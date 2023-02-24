<?php
/**
 * Auction lot existence checker aware trait
 *
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Validate;

/**
 * Trait AuctionLotExistenceCheckerAwareTrait
 * @package Sam\AuctionLot\Validate
 */
trait AuctionLotExistenceCheckerAwareTrait
{
    /**
     * @var AuctionLotExistenceChecker|null
     */
    protected ?AuctionLotExistenceChecker $auctionLotExistenceChecker = null;

    /**
     * @param AuctionLotExistenceChecker $auctionLotExistenceChecker
     * @return static
     * @internal
     */
    public function setAuctionLotExistenceChecker(AuctionLotExistenceChecker $auctionLotExistenceChecker): static
    {
        $this->auctionLotExistenceChecker = $auctionLotExistenceChecker;
        return $this;
    }

    /**
     * @return AuctionLotExistenceChecker
     */
    protected function getAuctionLotExistenceChecker(): AuctionLotExistenceChecker
    {
        if ($this->auctionLotExistenceChecker === null) {
            $this->auctionLotExistenceChecker = AuctionLotExistenceChecker::new();
        }
        return $this->auctionLotExistenceChecker;
    }
}
