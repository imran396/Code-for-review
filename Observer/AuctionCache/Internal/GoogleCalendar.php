<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\AuctionCache\Internal;

use AuctionCache;
use Sam\Auction\Cache\AuctionDbCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class GoogleCalendar
 * @package Sam\Observer\AuctionCache
 * @internal
 */
class GoogleCalendar extends CustomizableClass implements EntityUpdateObserverHandlerInterface
{
    use AuctionDbCacheManagerAwareTrait;
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
        /** @var AuctionCache $auctionCache */
        $auctionCache = $subject->getEntity();
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->getAuctionDbCacheManager()->dropGcalChangedOn([$auctionCache->AuctionId], $editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isAnyPropertyModified(['TotalLots', 'TotalActiveLots']);
    }
}
