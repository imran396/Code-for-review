<?php
/**
 * SAM-6473: Move "my_search" table related logic to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Alert\SentLot;


use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserSentLots\UserSentLotsWriteRepositoryAwareTrait;
use Sam\User\Alert\SentLot\Load\UserAlertSentLotLoaderAwareTrait;

/**
 * Class UserAlertSentLotManager
 * @package Sam\User\Alert\SentLot
 */
class UserAlertSentLotManager extends CustomizableClass
{
    use AuctionLotItemReadRepositoryCreateTrait;
    use UserAlertSentLotLoaderAwareTrait;
    use UserSentLotsWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param array $lotItemIds
     * @param int $editorUserId
     */
    public function addSentLots(int $userId, array $lotItemIds, int $editorUserId): void
    {
        $userSentLots = $this->getUserAlertSentLotLoader()->loadByUserIdOrCreate($userId);
        $sentIds = explode(',', $userSentLots->SentLots);
        $sentIds = array_merge($sentIds, $lotItemIds);
        $sentIds = array_unique($sentIds);
        $sentIds = $this->filterActiveLots($sentIds);
        $userSentLots->SentLots = implode(',', $sentIds);
        $this->getUserSentLotsWriteRepository()->saveWithModifier($userSentLots, $editorUserId);
    }

    /**
     * Load the previously sent active auction lot item ids for user
     *
     * @param int $userId
     * @return int[]
     */
    public function loadSentLotsIdList(int $userId): array
    {
        $userSentLots = $this->getUserAlertSentLotLoader()->loadByUserId($userId);
        $sentIds = $userSentLots && $userSentLots->SentLots
            ? explode(',', $userSentLots->SentLots)
            : [];
        $sentIds = $this->filterActiveLots($sentIds);
        return $sentIds;
    }

    /**
     * Filter out all closed sent lots to keep only active and upcoming
     *
     * @param array $lotItemIds
     * @return array
     */
    private function filterActiveLots(array $lotItemIds): array
    {
        if (!$lotItemIds) {
            return [];
        }
        $activeLotsIdList = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterId($lotItemIds)
            ->filterLotStatusId(Constants\Lot::LS_ACTIVE)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$openAuctionStatuses)
            ->joinLotItemFilterActive(true)
            ->select(['GROUP_CONCAT(ali.id) AS ids'])
            ->loadRow();
        $activeLotsIdList = $activeLotsIdList['ids'] ?? '';
        $activeLotsIdList = ArrayCast::makeIntArray(explode(',', $activeLotsIdList));
        return $activeLotsIdList;
    }
}
