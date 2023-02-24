<?php
/**
 * SAM-6431: Refactor Auction_Lots_DbCacheManager for 2020 year version
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Cache\SeoUrl\Load;

use AuctionLotItem;
use Sam\AuctionLot\Load\AuctionLotCacheLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Lot\SeoUrl\Build\LotSeoUrlBuilderCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItemCache\AuctionLotItemCacheWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AuctionLotCacheSeoUrlLoader
 * @package Sam\AuctionLot\Cache
 */
class AuctionLotCacheSeoUrlLoader extends CustomizableClass
{
    use AuctionLotCacheLoaderAwareTrait;
    use AuctionLotItemCacheWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use EditorUserAwareTrait;
    use LotSeoUrlBuilderCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Load cached SeoUrl. Re-calculate on-fly if it is dropped and persist.
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function load(?int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): string
    {
        if (
            !$lotItemId
            || !$auctionId
        ) {
            return '';
        }

        /**
         * TODO: #CQS-violation, IK, we shouldn't perform data update operation in query logic of data loader.
         * This is temporary solution, that creates AuctionLotItemCache entity and then calculates SeoUrl and saves entity.
         */
        $editorUserId = $this->detectModifierUserId();

        $auctionLotCache = $this->getAuctionLotCacheLoader()->loadOrCreate($lotItemId, $auctionId, $editorUserId, $isReadOnlyDb);
        if (!$auctionLotCache) {
            log_debug(
                "Available auction lot item cache not found, when detecting seo url"
                . composeSuffix(['li' => $lotItemId, 'a' => $auctionId])
            );
            return '';
        }

        if ($auctionLotCache->SeoUrl === null) {
            $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId, $isReadOnlyDb);
            if ($auctionLot) {
                $auctionLotCache->SeoUrl = $this->buildForAuctionLot($auctionLot);
                $this->getAuctionLotItemCacheWriteRepository()->saveWithModifier($auctionLotCache, $editorUserId);
            } else {
                $logData = ['a' => $auctionId, 'li' => $lotItemId];
                log_error("Available auction lot not found for updating cached seo url" . composeSuffix($logData));
            }
        }
        return $auctionLotCache->SeoUrl;
    }

    /**
     * Decorator-function over seo url builder
     * @param AuctionLotItem $auctionLot
     * @return string
     */
    protected function buildForAuctionLot(AuctionLotItem $auctionLot): string
    {
        $seoUrls = $this->createLotSeoUrlBuilder()
            ->construct($auctionLot->AccountId, [$auctionLot->Id])
            ->render();
        $seoUrl = current($seoUrls);
        return $seoUrl;
    }

    /**
     * Return entity modifier user - he is either authorized user, or system user when the current user is anonymous or not defined.
     * @return int
     */
    protected function detectModifierUserId(): int
    {
        return $this->getEditorUserId() ?: $this->getUserLoader()->loadSystemUserId();
    }
}
