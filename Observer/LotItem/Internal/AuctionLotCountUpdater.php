<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotItem\Internal;

use LotItem;
use Sam\Auction\Cache\LotCount\AuctionLotCountCacherAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Observer\LotItem\Internal\Load\DataLoaderCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Increase/decrease auction lot count depending on properties changes
 *
 * Class AuctionLotCountUpdater
 * @package Sam\Observer\LotItem
 * @internal
 */
class AuctionLotCountUpdater extends CustomizableClass implements EntityUpdateObserverHandlerInterface
{
    use AuctionLotCountCacherAwareTrait;
    use DataLoaderCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();
        $auctionIds = $this->createDataLoader()->loadLotItemAuctionIds($lotItem);
        if ($auctionIds) {
            $auctionAddCount = $lotItem->isActive() ? 1 : -1;
            $editorUserId = $this->getUserLoader()->loadSystemUserId();
            $this->getAuctionLotCountCacher()
                ->addTotalCount($auctionIds, $auctionAddCount, $lotItem->Id)
                ->flush($editorUserId);
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isPropertyModified('Active');
    }
}
