<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Save;

/**
 * Trait AuctionBidderAbsenteeBidSaverCreateTrait
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Edit\Save
 */
trait AuctionBidderAbsenteeBidSaverCreateTrait
{
    protected ?AuctionBidderAbsenteeBidSaver $auctionBidderAbsenteeBidSaver = null;

    /**
     * @return AuctionBidderAbsenteeBidSaver
     */
    protected function createAuctionBidderAbsenteeBidSaver(): AuctionBidderAbsenteeBidSaver
    {
        return $this->auctionBidderAbsenteeBidSaver ?: AuctionBidderAbsenteeBidSaver::new();
    }

    /**
     * @param AuctionBidderAbsenteeBidSaver $auctionBidderAbsenteeBidSaver
     * @return $this
     * @internal
     * @noinspection  PhpUnused
     */
    public function setAuctionBidderAbsenteeBidSaver(AuctionBidderAbsenteeBidSaver $auctionBidderAbsenteeBidSaver): static
    {
        $this->auctionBidderAbsenteeBidSaver = $auctionBidderAbsenteeBidSaver;
        return $this;
    }
}
