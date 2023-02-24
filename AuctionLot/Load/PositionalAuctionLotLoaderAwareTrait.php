<?php
/**
 * SAM-5154: Positional auction lot loader
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           09.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\AuctionLot\Load;

/**
 * Trait PositionalAuctionLotLoaderAwareTrait
 * @package Sam\AuctionLot\Load
 */
trait PositionalAuctionLotLoaderAwareTrait
{
    /**
     * @var PositionalAuctionLotLoader|null
     */
    protected ?PositionalAuctionLotLoader $positionalAuctionLotLoader = null;

    /**
     * @return PositionalAuctionLotLoader
     */
    protected function getPositionalAuctionLotLoader(): PositionalAuctionLotLoader
    {
        if ($this->positionalAuctionLotLoader === null) {
            $this->positionalAuctionLotLoader = PositionalAuctionLotLoader::new();
        }
        return $this->positionalAuctionLotLoader;
    }

    /**
     * @param PositionalAuctionLotLoader $positionalAuctionLotLoader
     * @return static
     * @noinspection PhpUnused
     */
    public function setPositionalAuctionLotLoader(PositionalAuctionLotLoader $positionalAuctionLotLoader): static
    {
        $this->positionalAuctionLotLoader = $positionalAuctionLotLoader;
        return $this;
    }
}
