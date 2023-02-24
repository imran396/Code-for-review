<?php
/**
 * SAM-5637: Extract logic from Lot_Factory
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

namespace Sam\AuctionLot\Save;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\TimedOnlineItem\TimedOnlineItemWriteRepositoryAwareTrait;
use TimedOnlineItem;

/**
 * Class TimedItemProducer
 * @package Sam\AuctionLot\Save
 */
class TimedItemProducer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use LotItemLoaderAwareTrait;
    use TimedOnlineItemWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create record in timed_online_item table initialized with default values
     *
     * @param int $lotItemId
     * @param int $auctionId
     * @return TimedOnlineItem
     */
    public function create(int $lotItemId, int $auctionId): TimedOnlineItem
    {
        $timedItem = $this->createEntityFactory()->timedOnlineItem();
        $timedItem->AuctionId = $auctionId;
        $timedItem->LotItemId = $lotItemId;
        $timedItem->NoBidding = false;
        $timedItem->BestOffer = false;
        return $timedItem;
    }

    /**
     * Create record in timed_online_item table initialized with default values
     *
     * @param int $lotItemId
     * @param int $auctionId
     * @return ?TimedOnlineItem
     */
    public function createIfLotAvailable(int $lotItemId, int $auctionId): ?TimedOnlineItem
    {
        $timedItem = null;
        $auction = $this->getAuctionLoader()->load($auctionId);
        $lotItem = $this->getLotItemLoader()->load($lotItemId);
        if ($auction && $lotItem) {
            $timedItem = $this->create($lotItemId, $auctionId);
        }
        return $timedItem;
    }

    public function createPersisted(int $lotItemId, int $auctionId, int $editorUserId): TimedOnlineItem
    {
        $timedItem = $this->createIfLotAvailable($lotItemId, $auctionId);
        if ($timedItem) {
            $this->getTimedOnlineItemWriteRepository()->saveWithModifier($timedItem, $editorUserId);
        }
        return $timedItem;
    }
}
