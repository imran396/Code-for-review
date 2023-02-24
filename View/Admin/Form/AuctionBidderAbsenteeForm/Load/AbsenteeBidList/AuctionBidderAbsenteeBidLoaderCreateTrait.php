<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Load\AbsenteeBidList;

/**
 * Trait AuctionBidderAbsenteeBidLoaderCreateTrait
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Load\AbsenteeBidList
 */
trait AuctionBidderAbsenteeBidLoaderCreateTrait
{
    protected ?AuctionBidderAbsenteeBidLoader $auctionBidderAbsenteeBidLoader = null;

    /**
     * @return AuctionBidderAbsenteeBidLoader
     */
    protected function createAuctionBidderAbsenteeBidLoader(): AuctionBidderAbsenteeBidLoader
    {
        return $this->auctionBidderAbsenteeBidLoader ?: AuctionBidderAbsenteeBidLoader::new();
    }

    /**
     * @param AuctionBidderAbsenteeBidLoader $auctionBidderAbsenteeBidLoader
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionBidderAbsenteeBidLoader(AuctionBidderAbsenteeBidLoader $auctionBidderAbsenteeBidLoader): static
    {
        $this->auctionBidderAbsenteeBidLoader = $auctionBidderAbsenteeBidLoader;
        return $this;
    }
}
