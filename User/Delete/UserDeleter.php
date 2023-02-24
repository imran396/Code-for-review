<?php
/**
 * Helper methods to delete user with all dependencies.
 *
 * SAM-3632: User delete class https://bidpath.atlassian.net/browse/SAM-3632
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           20 Feb, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Delete;

use Sam\Auction\Cache\CacheInvalidator\AuctionCacheInvalidatorCreateTrait;
use Sam\Auction\Cache\CacheInvalidator\CacheInvalidatorFilterConditionCreateTrait;
use Sam\AuctionLot\Cache\Save\AuctionLotCacheUpdaterCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\User\Delete\UserCustomDataDeleterCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\User\UserWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoader;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;
use User;

/**
 * Class Deleter
 * @package Sam\User\Delete
 */
class UserDeleter extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionCacheInvalidatorCreateTrait;
    use AuctionLotCacheUpdaterCreateTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use CacheInvalidatorFilterConditionCreateTrait;
    use ConfigRepositoryAwareTrait;
    use LotItemReadRepositoryCreateTrait;
    use LotItemWriteRepositoryAwareTrait;
    use RoleCheckerAwareTrait;
    use UserCustomDataDeleterCreateTrait;
    use UserLoaderAwareTrait;
    use UserWriteRepositoryAwareTrait;

    protected ?Storage\DataManager $dataManager = null;
    /**
     * @var int[] count of items, in which we drop winning bidder
     */
    protected array $droppedWinnerLotItemIds = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return true if the user could be deleted. We could not delete system user and the last admin of main account
     *
     * @param User $user
     * @param int $editorUserId
     * @return bool
     */
    public function canDelete(User $user, int $editorUserId): bool
    {
        //we could not delete system user
        if ($user->Username === $this->cfg()->get(UserLoader::CFG_SYSTEM_USERNAME)) {
            return false;
        }

        $mainAccountId = $this->cfg()->get('core->portal->mainAccountId');
        //If user is under main account
        if ($user->AccountId === $mainAccountId) {
            $isAdmin = $this->getRoleChecker()->isAdmin($user->Id, true);
            if ($isAdmin) {
                $adminCount = $this->getDataManager()->countMainAccountAdmins();
                //If there left only one admin, we could not delete it
                if ($adminCount <= 1) {
                    return false;
                }
            }
        }

        $hasSubPrivilegeForDeleteUser = $this->getAdminPrivilegeChecker()
            ->initByUserId($editorUserId)
            ->hasSubPrivilegeForDeleteUser();
        if (!$hasSubPrivilegeForDeleteUser) {
            return false;
        }

        return true;
    }

    /**
     * Delete user with all dependencies
     * @param User $user
     * @param int $editorUserId
     */
    public function delete(User $user, int $editorUserId): void
    {
        if (!$this->canDelete($user, $editorUserId)) {
            return;
        }

        $user->toDeleted();
        $this->getUserWriteRepository()->saveWithModifier($user, $editorUserId);
        $this->createUserCustomDataDeleter()->deleteForUserId($user->Id, $editorUserId);
        $this->refreshAuctionAndAuctionLotCachesHavingUserBid($user->Id, $editorUserId);
        $this->dropWinningBidderOfLotItems($user->Id, $editorUserId);
        $this->log($user, $editorUserId);
    }

    /**
     * Delete array of users with all dependencies
     * @param array $users
     * @param int $editorUserId
     */
    public function deleteArray(array $users, int $editorUserId): void
    {
        foreach ($users as $user) {
            $this->delete($user, $editorUserId);
        }
    }

    /**
     * Get user by id and then delete it with all dependencies
     * @param int|null $userId
     * @param int $editorUserId
     * @return void
     */
    public function deleteById(?int $userId, int $editorUserId): void
    {
        $user = $this->getUserLoader()->load($userId);
        if ($user) {
            $this->delete($user, $editorUserId);
        }
    }

    /**
     * Remove winning bidder info of lot items
     * @param int $userId
     * @param int $editorUserId
     * @return void
     */
    protected function dropWinningBidderOfLotItems(int $userId, int $editorUserId): void
    {
        $this->droppedWinnerLotItemIds = [];
        $repo = $this->createLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterWinningBidderId($userId)
            ->limit(100);
        while ($lotItems = $repo->loadEntities()) {
            foreach ($lotItems as $lotItem) {
                $lotItem->WinningBidderId = null;
                $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
                $this->droppedWinnerLotItemIds[] = $lotItem->Id;
            }
        }
    }

    /**
     * Find auction lots and auctions, that have user bids, update lot caches, drop modified for auction caches
     * @param int $userId
     * @param int $editorUserId
     * @return void
     */
    public function refreshAuctionAndAuctionLotCachesHavingUserBid(int $userId, int $editorUserId): void
    {
        $this->refreshTimedAuctionAndAuctionLotCachesHavingUserBid($userId, $editorUserId);
        $this->refreshLiveAuctionAndAuctionLotCachesHavingUserBid($userId, $editorUserId);
    }

    /**
     * Drop modified timestamp of auction caches and refresh lot caches for items,
     * that have bid transactions of deleted user.
     * @param int $userId
     * @param int $editorUserId
     * @return void
     */
    protected function refreshTimedAuctionAndAuctionLotCachesHavingUserBid(int $userId, int $editorUserId): void
    {
        $repo = $this->createAuctionLotItemReadRepository()
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinAuctionFilterAuctionType(Constants\Auction::TIMED)
            ->joinBidTransactionFilterUserId($userId)
            ->joinBidTransactionFilterDeleted(false)
            ->joinLotItemFilterActive(true)
            ->groupById()
            ->setChunkSize(200);
        $total = $repo->count();
        log_debug(
            "Found {$total} timed lots with bids of deleted user"
            . composeSuffix(['u' => $userId]) . " Start refresh caches."
        );
        $lotCount = 0;
        $auctionLotIds = [];
        $auctionIds = [];
        while ($auctionLots = $repo->loadEntities()) {
            foreach ($auctionLots as $auctionLot) {
                $auctionLotIds[] = [
                    'a' => $auctionLot->AuctionId,
                    'li' => $auctionLot->LotItemId,
                    'ali' => $auctionLot->Id,
                ];
                $auctionIds[$auctionLot->AuctionId] = $auctionLot->AuctionId;
                $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($auctionLot, $editorUserId);
            }
            $filterCondition = $this->createCacheInvalidatorFilterCondition()->filterAuctionId($auctionIds);
            $this->createAuctionCacheInvalidator()->invalidate($filterCondition, $editorUserId);
            $lotCount += count($auctionLots);
        }
        if ($lotCount) {
            $idInfo = $this->idsToList($auctionLotIds);
            $auctionCount = count($auctionIds);
            log_info(
                "Refreshed {$lotCount} timed lot caches "
                . "and dropped modified_on timestamp for {$auctionCount} timed auction caches, "
                . "that have bids of deleted user" . composeSuffix(['u' => $userId]) . ". Lots: {$idInfo}."
            );
        } else {
            log_debug("Deleted user doesn't have bids of active timed lots" . composeSuffix(['u' => $userId]));
        }
    }

    /**
     * Drop modified timestamp of auction caches and refresh lot caches for items,
     * that have absentee bids of deleted user.
     * @param int $userId
     * @param int $editorUserId
     * @return void
     */
    public function refreshLiveAuctionAndAuctionLotCachesHavingUserBid(int $userId, int $editorUserId): void
    {
        $repo = $this->createAuctionLotItemReadRepository()
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->joinAuctionFilterAuctionType(Constants\Auction::RTB_AUCTION_TYPES)
            ->joinAbsenteeBidFilterUserId($userId)
            ->joinLotItemFilterActive(true)
            ->groupById()
            ->setChunkSize(200);
        $total = $repo->count();
        log_debug(
            "Found {$total} live/hybrid lots with absentee bids of deleted user"
            . composeSuffix(['u' => $userId]) . "Start refresh caches."
        );
        $lotCount = 0;
        $ids = [];
        $auctionIds = [];
        while ($auctionLots = $repo->loadEntities()) {
            foreach ($auctionLots as $auctionLot) {
                $ids[] = [
                    'a' => $auctionLot->AuctionId,
                    'li' => $auctionLot->LotItemId,
                    'ali' => $auctionLot->Id,
                ];
                $auctionIds[$auctionLot->AuctionId] = $auctionLot->AuctionId;
                $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($auctionLot, $editorUserId);
            }
            $lotCount += count($auctionLots);
        }
        $filterCondition = $this->createCacheInvalidatorFilterCondition()->filterAuctionId($auctionIds);
        $this->createAuctionCacheInvalidator()->invalidate($filterCondition, $editorUserId);
        if ($lotCount) {
            $idInfo = $this->idsToList($ids);
            $auctionCount = count($auctionIds);
            log_info(
                "Refreshed {$lotCount} live/hybrid lot caches "
                . "and dropped modified_on timestamp for {$auctionCount} live/hybrid auction caches, "
                . "that have bids of deleted user" . composeSuffix(['u' => $userId]) . ". Lots: {$idInfo}."
            );
        } else {
            log_debug("Deleted user doesn't have bids of active live/hybrid lots" . composeSuffix(['u' => $userId]));
        }
    }

    /**
     * @param array $allIds
     * @return string
     */
    protected function idsToList(array $allIds): string
    {
        $auctionIds = ArrayCast::arrayColumnInt($allIds, 'a');
        array_multisort($auctionIds, SORT_ASC, $allIds);
        $idInfos = [];
        foreach ($allIds as $ids) {
            $idInfos1 = [];
            foreach ($ids as $key => $val) {
                $idInfos1[] = "{$key}: {$val}";
            }
            $idInfos[] = '[' . implode(', ', $idInfos1) . ']';
        }
        $output = implode(', ', $idInfos);
        return $output;
    }

    /**
     * @param User $user
     * @param int $editorUserId
     * @return void
     */
    protected function log(User $user, int $editorUserId): void
    {
        $droppedWinnerLotCount = count($this->droppedWinnerLotItemIds);
        $lotItemIdList = implode(', ', $this->droppedWinnerLotItemIds);
        $message = "User deleted. Winning bidder is dropped for {$droppedWinnerLotCount} lots."
            . composeSuffix(
                [
                    'u' => $user->Id,
                    'username' => $user->Username,
                    'li' => $lotItemIdList,
                    'editor u' => $editorUserId
                ]
            );
        log_debug($message);
    }

    /**
     * @return Storage\DataManager
     */
    public function getDataManager(): Storage\DataManager
    {
        if ($this->dataManager === null) {
            $this->dataManager = Storage\DataManager::new();
        }
        return $this->dataManager;
    }

    /**
     * @param Storage\DataManager $dataManager
     * @return static
     */
    public function setDataManager(Storage\DataManager $dataManager): static
    {
        $this->dataManager = $dataManager;
        return $this;
    }
}
