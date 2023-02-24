<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\Auction\Internal;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\AuctionInfo\LotItemAuctionInfoUpdaterAwareTrait;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class LotItemAuctionInfoUpdater
 * @package Sam\Observer\Auction
 * @internal
 */
class LotItemAuctionInfoUpdater extends CustomizableClass implements EntityUpdateObserverHandlerInterface
{
    use LotItemAuctionInfoUpdaterAwareTrait;
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
        /** @var Auction $auction */
        $auction = $subject->getEntity();
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->getLotItemAuctionInfoUpdater()->refreshForLotsInAuction($auction->Id, $editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var Auction $auction */
        $auction = $subject->getEntity();
        return $subject->isAnyPropertyModified(['SaleNum', 'SaleNumExt'])
            || ($subject->isPropertyModified('AuctionStatusId')
                && $auction->isDeleted());
    }
}
