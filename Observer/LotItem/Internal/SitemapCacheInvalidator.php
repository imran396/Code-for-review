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
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Observer\LotItem\Internal\Load\DataLoaderCreateTrait;
use Sam\Sitemap\AuctionLots\Manager;

/**
 * if necessary change detect then remove site map files from sitemap cache directory.
 *
 * Class SitemapCacheInvalidator
 * @package Sam\Observer\LotItem
 * @internal
 */
class SitemapCacheInvalidator extends CustomizableClass implements EntityUpdateObserverHandlerInterface
{
    use AuctionLoaderAwareTrait;
    use DataLoaderCreateTrait;

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
        $manager = Manager::new();
        foreach ($auctionIds as $auctionId) {
            $manager->setAuction($this->getAuctionLoader()->load($auctionId));
            $manager->dropCached();
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();
        $isDeleted = $subject->isPropertyModified('Active')
            && $subject->getOldPropertyValue('Active')
            && $lotItem->isDeleted();
        $isNameChanged = $subject->isPropertyModified('Name');
        return $isDeleted || $isNameChanged;
    }
}
