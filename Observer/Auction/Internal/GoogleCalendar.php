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
use Sam\Auction\Cache\AuctionDbCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class GoogleCalendar
 * @package Sam\Observer\Auction
 * @internal
 */
class GoogleCalendar extends CustomizableClass implements EntityUpdateObserverHandlerInterface
{
    use AuctionDbCacheManagerAwareTrait;
    use ConfigRepositoryAwareTrait;
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
        $this->getAuctionDbCacheManager()->dropGcalChangedOn([$auction->Id], $editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var Auction $auction */
        $auction = $subject->getEntity();
        if (
            $auction->TestAuction
            || !$this->cfg()->get('core->vendor->google->calendar->enabled')
        ) {
            return false;
        }
        $checkingPropertiesForTimed = [
            'StartDate',
            'EndDate',
            'PublishDate',
        ];
        $checkingPropertiesForLive = [
            'AuctionStatusId',
            'StartDate',
            'TimezoneId',
            'StreamDisplay',
            'StartDate',
            'EndDate',
            'PublishDate',
        ];
        $checkingPropertiesForHybrid = [
            'AuctionStatusId',
            'StartDate',
            'TimezoneId',
            'LotStartGapTime',
            'StreamDisplay',
            'PublishDate',
            'ExtendTime',
        ];

        return ($auction->isTimedScheduled()
                && $subject->isAnyPropertyModified($checkingPropertiesForTimed))
            || ($auction->isLive()
                && $subject->isAnyPropertyModified($checkingPropertiesForLive))
            || ($auction->isHybrid()
                && $subject->isAnyPropertyModified($checkingPropertiesForHybrid));
    }
}
