<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\AuctionLotItem\Internal;

use AuctionLotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Sitemap\AuctionLots\Manager as SitemapManager;

/**
 * Remove site map files from sitemap directory if necessary
 *
 * Class SitemapCacheInvalidator
 * @package Sam\Observer\AuctionLotItem\Internal
 */
class SitemapCacheInvalidator extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionLoaderAwareTrait;

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
        $this->invalidate($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->invalidate($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();
        $logData = [
            'li' => $auctionLot->LotItemId,
            'a' => $auctionLot->AuctionId,
        ];
        $isSeoUrlChanged = $subject->isPropertyModified('SeoUrl');
        if ($isSeoUrlChanged) {
            log_trace("Drop sitemap auction's cache when SeoUrl is changed" . composeSuffix($logData));
            return true;
        }

        $isLotStatusIdChanged = $subject->isPropertyModified('LotStatusId');
        if ($isLotStatusIdChanged) {
            $lotStatusOld = (int)$subject->getOldPropertyValue('LotStatusId');
            $logData += [
                'old status' => $lotStatusOld,
                'new status' => $auctionLot->LotStatusId,
            ];
            $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
            $isAdded = !$auctionLotStatusPureChecker->isAmongAvailableLotStatuses($lotStatusOld)
                && $auctionLot->isAmongAvailableLotStatuses();
            if ($isAdded) {
                log_trace("Drop sitemap auction's cache when lot item is added to auction" . composeSuffix($logData));
                return true;
            }

            $isRemoved = $auctionLotStatusPureChecker->isAmongAvailableLotStatuses($lotStatusOld)
                && !$auctionLot->isAmongAvailableLotStatuses();
            if ($isRemoved) {
                log_trace("Drop sitemap auction's cache when lot item is removed from auction" . composeSuffix($logData));
                return true;
            }

            $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
            if (!$auction) {
                log_trace("Don't drop sitemap auction's cache if assigned auction is deleted" . composeSuffix($logData));
                return false;
            }

            if (
                $auction->HideUnsoldLots
                && (
                    $auctionLotStatusPureChecker->isUnsold($lotStatusOld)
                    || $auctionLot->isUnsold()
                )
            ) {
                log_trace(
                    "Drop sitemap auction's cache when lot status is changed from/to \"Unsold\""
                    . " and auction is configured to \"Hide Unsold Lots\"" . composeSuffix($logData)
                );
                return true;
            }
        }
        return false;
    }

    protected function invalidate(EntityObserverSubject $subject): void
    {
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $subject->getEntity();
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            log_trace("Don't drop sitemap auction's cache if assigned auction is deleted");
            return;
        }

        $manager = SitemapManager::new();
        $manager->setAuction($auction);
        $manager->dropCached();
    }
}
