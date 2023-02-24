<?php
/**
 * SAM-5952: Extract auction and lot dates recalculation from AuctionEditForm
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 07, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionEditForm\Date;


/**
 * Trait AuctionAndLotDatesRecalculatorCreateTrait
 * @package Sam\View\Admin\Form\AuctionEditForm\Date
 */
trait AuctionAndLotDatesRecalculatorCreateTrait
{
    protected ?AuctionAndLotDatesRecalculator $auctionAndLotDatesRecalculator = null;

    /**
     * @return AuctionAndLotDatesRecalculator
     */
    protected function createAuctionAndLotDatesRecalculator(): AuctionAndLotDatesRecalculator
    {
        return $this->auctionAndLotDatesRecalculator ?: AuctionAndLotDatesRecalculator::new();
    }

    /**
     * @param AuctionAndLotDatesRecalculator $auctionAndLotDatesRecalculator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionAndLotDatesRecalculator(AuctionAndLotDatesRecalculator $auctionAndLotDatesRecalculator): static
    {
        $this->auctionAndLotDatesRecalculator = $auctionAndLotDatesRecalculator;
        return $this;
    }
}
