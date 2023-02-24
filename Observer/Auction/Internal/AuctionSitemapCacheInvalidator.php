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
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Sitemap\Auctions\Manager as SitemapManager;

/**
 * If auction added,deleted,published/unpublished,changed visibility access
 * then it will remove site map index file from sitemap cache directory.
 *
 * Class AuctionSitemapCacheInvalidator
 * @package Sam\Observer\Auction
 * @internal
 */
class AuctionSitemapCacheInvalidator extends CustomizableClass implements EntityUpdateObserverHandlerInterface
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
        $manager->filterAccountId($auction->AccountId);
        $manager->dropCached();
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var Auction $auction */
        $auction = $subject->getEntity();
        $isDeleted = false;
        $isAdded = false;
        if ($subject->isPropertyModified('AuctionStatusId')) {
            $oldAuctionStatus = (int)$subject->getOldPropertyValue('AuctionStatusId');
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();
            $isAdded = !$auctionStatusPureChecker->isAvailableAuctionStatus($oldAuctionStatus)
                && $auction->isAvailableAuctionStatus()
                && $auction->PublishDate !== null;
            $isDeleted = $auctionStatusPureChecker->isAvailableAuctionStatus($oldAuctionStatus)
                && !$auction->isAvailableAuctionStatus();
        }
        $isPublished = $subject->isPropertyModified('PublishDate');
        $hasAuctionVisibilityAccess = $subject->isPropertyModified('AuctionVisibilityAccess')
            && ($subject->getOldPropertyValue('AuctionVisibilityAccess') === Constants\Role::VISITOR
                || $auction->AuctionVisibilityAccess === Constants\Role::VISITOR);

        return $isAdded
            || $isDeleted
            || $isPublished
            || $hasAuctionVisibilityAccess;
    }
}
