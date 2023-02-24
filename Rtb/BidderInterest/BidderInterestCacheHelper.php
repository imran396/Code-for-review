<?php
/**
 * Cache data in file system, so admin console could easy and quickly access data via http request.
 *
 * SAM-1023: Live Clerking Improvements & Bidder Interest
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/27/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\BidderInterest;

use Sam\Core\Service\CustomizableClass;
use Sam\Cache\File\FilesystemCacheManager;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;

/**
 * Class BidderInterestManager
 * @package Sam\Rtb\BidderInterest
 */
class BidderInterestCacheHelper extends CustomizableClass
{
    use FilesystemCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @return string
     */
    public function getCachedJson(int $auctionId): string
    {
        $cached = (string)$this->getCacheManager()->get((string)$auctionId);
        return $cached;
    }

    /**
     * Prepare, order data and cache
     * @param int $auctionId
     * @param array $userData
     */
    public function prepareAndCache(int $auctionId, array $userData): void
    {
        $userDataForCache = $this->prepareData($userData);
        $json = json_encode($userDataForCache);
        $this->getCacheManager()->set((string)$auctionId, $json);
    }

    /**
     * @param array $userData
     * @return array
     */
    protected function prepareData(array $userData): array
    {
        $attributes = [];
        $orders = [];
        if (!empty($userData)) {
            foreach ($userData as $userId => $info) {
                $attributes[] = [
                    'id' => (int)$userId,
                    'lbl' => (string)$info['lbl'],
                ];
                $orders[] = (int)$info['ts'];
            }
            array_multisort($orders, SORT_DESC, SORT_NUMERIC, $attributes);
        }
        return $attributes;
    }

    /**
     * @return FilesystemCacheManager
     */
    public function getCacheManager(): FilesystemCacheManager
    {
        $cacheManager = $this->getFilesystemCacheManager()
            ->setNamespace('bidding-interest')
            ->setExtension('json');
        return $cacheManager;
    }
}
