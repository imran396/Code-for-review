<?php
/**
 * Bidder Interest data management logic.
 * It is dependency in rtb daemon, so it stores in long running session currently interested bidders.
 * It also caches data in file system, so admin console could easy and quickly access data via http request.
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
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class BidderInterestManager
 * @package Sam\Rtb\BidderInterest
 */
class BidderInterestManager extends CustomizableClass
{
    use BidderInterestCacheHelperAwareTrait;
    use ConfigRepositoryAwareTrait;

    protected array $data = [];

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
     * @param int $userId
     * @return array
     */
    public function getInterested(int $auctionId, int $userId): array
    {
        $info = $this->data[$auctionId][$userId] ?? [];
        return $info;
    }

    /**
     * @param int $auctionId
     * @param int $userId
     * @param string $key
     * @return mixed
     */
    public function getInterestedAttribute(int $auctionId, int $userId, string $key): mixed
    {
        $info = $this->getInterested($auctionId, $userId);
        $value = $info[$key] ?? null;
        return $value;
    }

    /**
     * @param int $auctionId
     * @param int $userId
     * @param array $info
     * @return static
     */
    public function setInterested(int $auctionId, int $userId, array $info): static
    {
        $this->data[$auctionId][$userId] = $info;
        $this->updateCache($auctionId);
        return $this;
    }

    /**
     * @param int $auctionId
     * @param int $userId
     * @return bool
     */
    public function hasInterested(int $auctionId, int $userId): bool
    {
        $has = isset($this->data[$auctionId][$userId]);
        return $has;
    }

    /**
     * @param int $auctionId
     * @param int $userId
     * @return static
     */
    public function removeInterested(int $auctionId, int $userId): static
    {
        unset($this->data[$auctionId][$userId]);
        $this->updateCache($auctionId);
        return $this;
    }

    /**
     * @return array
     */
    public function dropExpired(): array
    {
        $expiredUserIdsPerAuction = $this->detectExpiredUsers();
        $this->drop($expiredUserIdsPerAuction);
        return $expiredUserIdsPerAuction;
    }

    /**
     * @param int $auctionId
     * @return int[]
     */
    protected function getUserDataForAuction(int $auctionId): array
    {
        $userIds = (isset($this->data[$auctionId])
            && is_array($this->data[$auctionId]))
            ? $this->data[$auctionId]
            : [];
        return $userIds;
    }

    /**
     * @return array [<auction.id> => [<user.id>, ...], ...]
     */
    protected function detectExpiredUsers(): array
    {
        $expiredUserIdsPerAuction = [];
        foreach ($this->data as $auctionId => $interestedDataPerUser) {
            foreach ($interestedDataPerUser as $userId => $interestedData) {
                $upTime = time() - $interestedData['ts'];
                if ($upTime > $this->cfg()->get('core->rtb->biddingInterest->dropTimeout')) {
                    $expiredUserIdsPerAuction[$auctionId][] = $userId;
                }
            }
        }
        return $expiredUserIdsPerAuction;
    }

    /**
     * @param int[][] $expiredUserIdsPerAuction
     */
    protected function drop(array $expiredUserIdsPerAuction): void
    {
        foreach ($expiredUserIdsPerAuction as $auctionId => $expiredUserIds) {
            $auctionId = (int)$auctionId;
            if (
                isset($this->data[$auctionId])
                && is_array($this->data[$auctionId])
                && count($this->data[$auctionId])
            ) {
                $userIdsAsKeys = array_flip($expiredUserIds);
                $actualUsersData = array_diff_key($this->data[$auctionId], $userIdsAsKeys);
                $this->data[$auctionId] = $actualUsersData;
                $this->updateCache($auctionId);
            }
        }
    }

    /**
     * Prepare, order data and cache
     * @param int $auctionId
     */
    protected function updateCache(int $auctionId): void
    {
        $userData = $this->getUserDataForAuction($auctionId);
        $this->getBidderInterestCacheHelper()->prepareAndCache($auctionId, $userData);
    }
}
