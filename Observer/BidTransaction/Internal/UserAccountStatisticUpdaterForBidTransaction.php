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

namespace Sam\Observer\BidTransaction\Internal;

use BidTransaction;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Account\Statistic\Save\UserAccountStatisticProducerAwareTrait;

/**
 * Class UserAccountStatisticUpdater
 * @package Sam\Observer\BidTransaction
 * @internal
 */
class UserAccountStatisticUpdaterForBidTransaction extends CustomizableClass
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
        return $subject->isAnyPropertyModified(['AuctionId', 'Bid', 'BidStatus', 'Deleted', 'MaxBid', 'UserId']);
    }

    protected function update(EntityObserverSubject $subject): void
    {
        /** @var BidTransaction $bidTransaction */
        $bidTransaction = $subject->getEntity();
        // Should not be a floor bidder
        if ($bidTransaction->UserId > 0) {
            $auction = $this->getAuctionLoader()->clear()->load($bidTransaction->AuctionId);
            $this->getUserAccountStatisticProducer()->markExpired($bidTransaction->UserId, $auction->AccountId ?? null);
        }
    }
}
