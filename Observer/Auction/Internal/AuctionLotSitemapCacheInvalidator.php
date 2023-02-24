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
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Sitemap\AuctionLots\Manager as SitemapManager;

/**
 * If auction property change detect then remove site map files from sitemap cache directory.
 *
 * Class AuctionLotSitemapCacheInvalidator
 * @package Sam\Observer\Auction
 * @internal
 */
class AuctionLotSitemapCacheInvalidator extends CustomizableClass implements EntityUpdateObserverHandlerInterface
{
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
        $manager = SitemapManager::new();
        $manager->setAuction($auction);
        $manager->dropCached();
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        $isAuctionVisibilityAccessChanged = $subject->isPropertyModified('AuctionVisibilityAccess')
            && (
                $subject->getOldPropertyValue('AuctionVisibilityAccess') === Constants\Role::VISITOR
                || $subject->getEntity()->AuctionVisibilityAccess === Constants\Role::VISITOR
            );
        if ($isAuctionVisibilityAccessChanged) {
            return true;
        }

        return $subject->isAnyPropertyModified(['AuctionStatusId', 'PublishDate', 'HideUnsoldLots']);
    }
}
