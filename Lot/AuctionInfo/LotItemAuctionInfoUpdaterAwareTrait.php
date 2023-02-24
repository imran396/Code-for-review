<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/17/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\AuctionInfo;

/**
 * Trait LotItemAuctionInfoUpdaterAwareTrait
 * @package Sam\Lot\AuctionInfo
 */
trait LotItemAuctionInfoUpdaterAwareTrait
{
    /**
     * @var Updater|null
     */
    protected ?Updater $lotItemAuctionInfoUpdater = null;

    /**
     * @return Updater
     */
    protected function getLotItemAuctionInfoUpdater(): Updater
    {
        if ($this->lotItemAuctionInfoUpdater === null) {
            $this->lotItemAuctionInfoUpdater = Updater::new();
        }
        return $this->lotItemAuctionInfoUpdater;
    }

    /**
     * @param Updater $lotItemAuctionInfoUpdater
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotItemAuctionInfoUpdater(Updater $lotItemAuctionInfoUpdater): static
    {
        $this->lotItemAuctionInfoUpdater = $lotItemAuctionInfoUpdater;
        return $this;
    }
}
