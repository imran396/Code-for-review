<?php
/**
 * SAM-5654 Auction lot reorderer
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 27, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder\Load\Storage;

/**
 * Trait AuctionLotReorderingRepositoryCreateTrait
 * @package Sam\AuctionLot\Order\Reorder\Load\Storage
 */
trait AuctionLotReorderingRepositoryCreateTrait
{
    protected ?AuctionLotReorderingRepository $auctionLotReorderingRepositoryCreateTrait = null;

    /**
     * @return AuctionLotReorderingRepository
     */
    protected function createAuctionLotReorderingRepository(): AuctionLotReorderingRepository
    {
        return $this->auctionLotReorderingRepositoryCreateTrait ?: AuctionLotReorderingRepository::new();
    }

    /**
     * @param AuctionLotReorderingRepository $auctionLotReorderingRepositoryCreateTrait
     * @return static
     * @internal
     */
    public function setAuctionLotReorderingRepository(AuctionLotReorderingRepository $auctionLotReorderingRepositoryCreateTrait): static
    {
        $this->auctionLotReorderingRepositoryCreateTrait = $auctionLotReorderingRepositoryCreateTrait;
        return $this;
    }
}
