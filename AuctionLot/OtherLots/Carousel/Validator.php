<?php
/**
 * Validate if possible to create a Carousel .
 * @see https://bidpath.atlassian.net/browse/SAM-3506
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 25, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\OtherLots\Carousel;

use Auction;
use LotItem;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * Class Validator
 */
class Validator extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Auction $auction
     * @param LotItem $lotItem
     * @return bool
     */
    public function isAllowToCreate(Auction $auction, LotItem $lotItem): bool
    {
        $this->setAuction($auction);
        if ($auction->isDeletedOrArchived()) {
            return false;
        }

        if ($this->isLotStatusUnknown($lotItem->Id)) {
            return false;
        }

        if ($lotItem->isDeleted()) {
            return false;
        }

        return true;
    }

    /**
     * @param int $lotItemId
     * @return bool
     */
    protected function isLotStatusUnknown(int $lotItemId): bool
    {
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $this->getAuctionId());
        $isUnknown = $auctionLot
            && !$auctionLot->isAmongAvailableLotStatuses();
        return $isUnknown;
    }
}
