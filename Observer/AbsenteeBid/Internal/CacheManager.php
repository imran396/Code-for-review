<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\AbsenteeBid\Internal;

use AbsenteeBid;
use Sam\Auction\Cache\CacheInvalidator\AuctionCacheInvalidatorCreateTrait;
use Sam\Auction\Cache\CacheInvalidator\CacheInvalidatorFilterConditionCreateTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Cache\Save\AuctionLotCacheUpdaterCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Account\Statistic\Save\UserAccountStatisticProducerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class CacheManager
 * @package Sam\Observer\AbsenteeBid
 * @internal
 */
class CacheManager extends CustomizableClass implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionCacheInvalidatorCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotCacheUpdaterCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use CacheInvalidatorFilterConditionCreateTrait;
    use UserAccountStatisticProducerAwareTrait;
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
    public function onCreate(EntityObserverSubject $subject): void
    {
        /** @var AbsenteeBid $absenteeBid */
        $absenteeBid = $subject->getEntity();
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->refreshAuctionLotCache($absenteeBid, $editorUserId);
        $this->invalidateAuctionCache($absenteeBid);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var AbsenteeBid $absenteeBid */
        $absenteeBid = $subject->getEntity();
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->refreshAuctionLotCache($absenteeBid, $editorUserId);
        $this->invalidateAuctionCache($absenteeBid);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isAnyPropertyModified(['MaxBid', 'BidType']);
    }

    /**
     * @param AbsenteeBid $absenteeBid
     * @param int $editorUserId
     */
    protected function refreshAuctionLotCache(AbsenteeBid $absenteeBid, int $editorUserId): void
    {
        $auctionLot = $this->getAuctionLotLoader()->load($absenteeBid->LotItemId, $absenteeBid->AuctionId);
        if ($auctionLot) {
            $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($auctionLot, $editorUserId);
        } else {
            log_error(
                "Available auction lot item not found, when want to refresh its cache"
                . composeSuffix(['li' => $absenteeBid->LotItemId, 'a' => $absenteeBid->AuctionId])
            );
        }
    }

    /**
     * @param AbsenteeBid $absenteeBid
     */
    protected function invalidateAuctionCache(AbsenteeBid $absenteeBid): void
    {
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $auction = $this->getAuctionLoader()
            ->clear()
            ->load($absenteeBid->AuctionId);
        if ($auction) {
            $this->getUserAccountStatisticProducer()->markExpired($absenteeBid->UserId, $auction->AccountId);
            $filterCondition = $this->createCacheInvalidatorFilterCondition()->filterAuctionId([$absenteeBid->AuctionId]);
            $this->createAuctionCacheInvalidator()->invalidate($filterCondition, $editorUserId);
        }
    }
}
