<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal;

/**
 * Trait AuctionLotDataMessageFactoryCreateTrait
 * @package Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal
 * @internal
 */
trait AuctionLotDataMessageFactoryCreateTrait
{
    protected ?AuctionLotDataMessageFactory $auctionLotDataMessageFactory = null;

    /**
     * @return AuctionLotDataMessageFactory
     */
    protected function createAuctionLotDataMessageFactory(): AuctionLotDataMessageFactory
    {
        return $this->auctionLotDataMessageFactory ?: AuctionLotDataMessageFactory::new();
    }

    /**
     * @param AuctionLotDataMessageFactory $auctionLotDataMessageFactory
     * @return static
     * @internal
     */
    public function setAuctionLotDataMessageFactory(AuctionLotDataMessageFactory $auctionLotDataMessageFactory): static
    {
        $this->auctionLotDataMessageFactory = $auctionLotDataMessageFactory;
        return $this;
    }
}
