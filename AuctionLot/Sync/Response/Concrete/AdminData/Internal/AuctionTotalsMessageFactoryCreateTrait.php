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
 * Trait AuctionTotalsMessageFactoryCreateTrait
 * @package Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal
 * @internal
 */
trait AuctionTotalsMessageFactoryCreateTrait
{
    protected ?AuctionTotalsMessageFactory $auctionTotalsMessageFactory = null;

    /**
     * @return AuctionTotalsMessageFactory
     */
    protected function createAuctionTotalsMessageFactory(): AuctionTotalsMessageFactory
    {
        return $this->auctionTotalsMessageFactory ?: AuctionTotalsMessageFactory::new();
    }

    /**
     * @param AuctionTotalsMessageFactory $auctionTotalsMessageFactory
     * @return static
     * @internal
     */
    public function setAuctionTotalsMessageFactory(AuctionTotalsMessageFactory $auctionTotalsMessageFactory): static
    {
        $this->auctionTotalsMessageFactory = $auctionTotalsMessageFactory;
        return $this;
    }
}
