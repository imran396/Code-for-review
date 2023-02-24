<?php
/**
 * SAM-10744: Split UserAccountStatisticUpdater on updaters for each entity
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 6, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotItem\Internal;

use LotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Account\Statistic\Save\UserAccountStatisticProducerAwareTrait;

/**
 * Class UserAccountStatisticUpdater
 * @package Sam\Observer\LotItem
 * @internal
 */
class UserAccountStatisticUpdaterForLotItem extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionLoaderAwareTrait;
    use UserAccountStatisticProducerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function onCreate(EntityObserverSubject $subject): void
    {
        $this->update($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->update($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isAnyPropertyModified(['AuctionId', 'ConsignorId', 'DateSold', 'HammerPrice', 'WinningBidderId']);
    }

    protected function update(EntityObserverSubject $subject): void
    {
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();

        $auction = $this->getAuctionLoader()->clear()->load($lotItem->AuctionId);
        if (
            $auction
            && $auction->AccountId
        ) {
            $this->updateWinningBidderStats($subject, $auction->AccountId);
            $this->updateConsignorStats($subject, $auction->AccountId);
        }

        $this->updateWinningBidderStats($subject, $lotItem->AccountId);
        $this->updateConsignorStats($subject, $lotItem->AccountId);
    }

    protected function updateConsignorStats(EntityObserverSubject $subject, int $accountId): void
    {
        $lotItem = $subject->getEntity();
        if (
            !$subject->isPropertyModified('ConsignorId')
            || !$lotItem->ConsignorId
        ) {
            return;
        }

        // New consignor
        $this->getUserAccountStatisticProducer()->markExpired($lotItem->ConsignorId, $accountId);

        // Old consignor
        $oldConsignorId = Cast::toInt($lotItem->__Modified['ConsignorId']);
        if (
            $oldConsignorId > 0
            && $oldConsignorId !== $lotItem->ConsignorId
        ) {
            $this->getUserAccountStatisticProducer()->markExpired($oldConsignorId, $accountId);
        }
    }

    protected function updateWinningBidderStats(EntityObserverSubject $subject, int $accountId): void
    {
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();
        if (
            !$subject->isPropertyModified('WinningBidderId')
            || !$lotItem->WinningBidderId
            || !$lotItem->HammerPrice
        ) {
            return;
        }

        // New winning bidder
        $this->getUserAccountStatisticProducer()->markExpired($lotItem->WinningBidderId, $accountId);

        // Old winning bidder
        $oldWinningBidderId = Cast::toInt($lotItem->__Modified['WinningBidderId']);
        if (
            $oldWinningBidderId > 0
            && $oldWinningBidderId !== $lotItem->WinningBidderId
        ) {
            $this->getUserAccountStatisticProducer()->markExpired($oldWinningBidderId, $accountId);
        }
    }
}
