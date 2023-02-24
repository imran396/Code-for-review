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

namespace Sam\Observer\AbsenteeBid\Internal;

use AbsenteeBid;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Account\Statistic\Save\UserAccountStatisticProducerAwareTrait;

/**
 * Class UserAccountStatisticUpdater
 * @package Sam\Observer\AbsenteeBid
 * @internal
 */
class UserAccountStatisticUpdaterForAbsenteeBid extends CustomizableClass
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
        return $subject->isAnyPropertyModified(['MaxBid']);
    }

    protected function update(EntityObserverSubject $subject): void
    {
        /** @var AbsenteeBid $absenteeBid */
        $absenteeBid = $subject->getEntity();
        $auction = $this->getAuctionLoader()->clear()->load($absenteeBid->AuctionId);
        $this->getUserAccountStatisticProducer()->markExpired($absenteeBid->UserId, $auction->AccountId ?? null);
    }
}
