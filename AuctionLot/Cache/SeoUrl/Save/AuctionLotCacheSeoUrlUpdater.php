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

namespace Sam\AuctionLot\Cache\SeoUrl\Save;

use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Lot\SeoUrl\Build\LotSeoUrlBuilderCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItemCache\AuctionLotItemCacheReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItemCache\AuctionLotItemCacheWriteRepositoryAwareTrait;

/**
 * Class AuctionLotCacheSeoUrlUpdater
 * @package Sam\AuctionLot\Cache
 */
class AuctionLotCacheSeoUrlUpdater extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AuctionLotItemCacheReadRepositoryCreateTrait;
    use AuctionLotItemCacheWriteRepositoryAwareTrait;
    use LotSeoUrlBuilderCreateTrait;

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
     * Refresh auction_lot_item_cache.seo_url by parsed setting_seo.lot_seo_url
     * @param int $editorUserId
     * @param int $maxExecTime
     * @return int
     */
    public function refreshForAll(int $editorUserId, int $maxExecTime): int
    {
        $execStartTime = time();
        $count = 0;

        $accounts = $this->getAccountLoader()->loadAll(true);
        foreach ($accounts as $account) {
            log_debug('Start building seo urls for account' . composeSuffix(['acc' => $account->Id, 'name' => $account->Name]));
            $repo = $this->createAuctionLotItemCacheReadRepository()
                ->filterSeoUrl(null)
                ->joinAuctionLotItemFilterAccountId($account->Id)
                ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                // don't iterate by chunks, because current updating and filtering are mutually related,
                // i.e. we should always fetch data from beginning, since we search alic.seo_url = null and update it then.
                ->limit(100);

            while ($auctionLotCaches = $repo->loadEntities()) {
                $auctionLotIds = ArrayHelper::toArrayByProperty($auctionLotCaches, 'AuctionLotItemId');
                log_debug('Start building seo urls for auction lots' . composeSuffix(['acc' => $account->Id, 'ali' => $auctionLotIds]));
                $seoUrls = $this->createLotSeoUrlBuilder()
                    ->construct($account->Id, $auctionLotIds)
                    ->render();

                foreach ($auctionLotCaches as $auctionLotCache) {
                    if (isset($seoUrls[$auctionLotCache->AuctionLotItemId])) {
                        $auctionLotCache->SeoUrl = $seoUrls[$auctionLotCache->AuctionLotItemId];
                        $this->getAuctionLotItemCacheWriteRepository()->saveWithModifier($auctionLotCache, $editorUserId);
                        $count++;
                    }

                    // If we run from cron script, then we have execution time limitation
                    if (
                        $maxExecTime
                        && time() > ($execStartTime + $maxExecTime)
                    ) {
                        log_debug('Execution time limit exceeded' . composeSuffix(['limit' => $maxExecTime]));
                        return $count;
                    }
                }
            }
        }

        return $count;
    }
}
