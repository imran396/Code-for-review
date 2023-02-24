<?php
/**
 * SAM-5651: Refactor Lot No auto filling service
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\LotNo\AtomicFill\Save;

/**
 * Trait AuctionLotNoAtomicSaverCreateTrait
 * @package Sam\AuctionLot\AtomicFill\Save
 */
trait AuctionLotNoAtomicSaverCreateTrait
{
    /**
     * @var AuctionLotNoAtomicSaver|null
     */
    protected ?AuctionLotNoAtomicSaver $auctionLotNoAtomicSaver = null;

    /**
     * @return AuctionLotNoAtomicSaver
     */
    protected function createAuctionLotNoAtomicSaver(): AuctionLotNoAtomicSaver
    {
        return $this->auctionLotNoAtomicSaver ?: AuctionLotNoAtomicSaver::new();
    }

    /**
     * @param AuctionLotNoAtomicSaver $auctionLotNoAtomicSaver
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAuctionLotNoAtomicSaver(AuctionLotNoAtomicSaver $auctionLotNoAtomicSaver): static
    {
        $this->auctionLotNoAtomicSaver = $auctionLotNoAtomicSaver;
        return $this;
    }
}
