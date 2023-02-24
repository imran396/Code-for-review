<?php
/**
 * SAM-4452: Apply Auction Bidder Deleter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Delete;

use Sam\Auction\Cache\CacheInvalidator\AuctionCacheInvalidatorCreateTrait;
use Sam\Auction\Cache\CacheInvalidator\CacheInvalidatorFilterConditionCreateTrait;
use Sam\Auction\Register\AuctionRegistrationManagerAwareTrait;
use Sam\AuctionLot\Cache\Save\AuctionLotCacheUpdaterCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidding\AbsenteeBid\Delete\AbsenteeBidDeleterAwareTrait;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionBidder\AuctionBidderWriteRepositoryAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;

/**
 * Class AuctionBidderDeleter
 * @package Sam\Bidder\AuctionBidder\Delete
 */
class AuctionBidderDeleter extends CustomizableClass
{
    use AbsenteeBidDeleterAwareTrait;
    use AbsenteeBidReadRepositoryCreateTrait;
    use AdminPrivilegeCheckerAwareTrait;
    use AuctionBidderReadRepositoryCreateTrait;
    use AuctionBidderWriteRepositoryAwareTrait;
    use AuctionCacheInvalidatorCreateTrait;
    use AuctionLotCacheUpdaterCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRegistrationManagerAwareTrait;
    use CacheInvalidatorFilterConditionCreateTrait;
    use ConfigRepositoryAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use RoleCheckerAwareTrait;

    public const ERR_ANONYMOUS = 1;
    public const ERR_NO_PRIVILEGES = 2;
    public const ERR_AUCTION_ACCOUNT_NOT_FOUND = 3;
    public const ERR_NOT_ADMIN_OF_AUCTION_ACCOUNT = 4;
    public const ERR_NO_BIDDERS = 5;

    protected const LOG_PREFIX = 'Restricted operation - delete auction bidders. ';

    protected const ERROR_MESSAGES = [
        self::ERR_ANONYMOUS => 'Anonymous user cannot delete bidders from auction',
        self::ERR_NO_PRIVILEGES => 'User does not have privilege for managing bidders',
        self::ERR_AUCTION_ACCOUNT_NOT_FOUND => 'Accounts of auctions not found for auction bidder records',
        self::ERR_NOT_ADMIN_OF_AUCTION_ACCOUNT => 'User is not admin of auction\'s account whose bidder removed',
        self::ERR_NO_BIDDERS => 'No one auction bidder is selected for delete',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * TODO: extract to \Sam\Bidder\AuctionBidder\Delete\Validate\Validator::validateByIds() and apply unit test
     * Check if user has permissions to delete auction bidders
     * @param int|null $editorUserId
     * @param int[] $auctionBidderIds
     * @return bool
     */
    public function canDeleteByIds(?int $editorUserId, array $auctionBidderIds): bool
    {
        $collector = $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);

        if (!$editorUserId) {
            $collector->addError(self::ERR_ANONYMOUS);
            log_error(self::LOG_PREFIX . $collector->lastAddedErrorMessage());
            return false;
        }

        if (!$auctionBidderIds) {
            $collector->addError(self::ERR_NO_BIDDERS);
            log_error(self::LOG_PREFIX . $collector->lastAddedErrorMessage());
            return false;
        }

        $adminPrivilegeChecker = $this->getAdminPrivilegeChecker()
            ->enableReadOnlyDb(true)
            ->initByUserId($editorUserId);
        $hasSubPrivilegeForBidders = $adminPrivilegeChecker->hasSubPrivilegeForBidders();
        if (!$hasSubPrivilegeForBidders) {
            $collector->addError(self::ERR_NO_PRIVILEGES);
            log_error(self::LOG_PREFIX . $collector->lastAddedErrorMessage() . composeSuffix(['u' => $editorUserId]));
            return false;
        }

        $isCrossAccountAdmin = !$this->cfg()->get('core->portal->enabled')
            || $adminPrivilegeChecker->hasPrivilegeForSuperadmin();
        if (!$isCrossAccountAdmin) {
            $rows = $this->createAuctionBidderReadRepository()
                ->enableDistinct(true)
                ->enableReadOnlyDb(true)
                ->filterId($auctionBidderIds)
                ->joinAuction()
                ->select(['a.account_id'])
                ->loadRows();
            if (!$rows) {
                $collector->addError(self::ERR_AUCTION_ACCOUNT_NOT_FOUND);
                log_error(self::LOG_PREFIX . $collector->lastAddedErrorMessage() . composeSuffix(['u' => $editorUserId]));
                return false;
            }

            $accountIds = ArrayCast::arrayColumnInt($rows, 'account_id');
            foreach ($accountIds as $accountId) {
                if (!$this->getRoleChecker()->isAdminOfAccount($editorUserId, $accountId, true)) {
                    $collector->addError(self::ERR_NOT_ADMIN_OF_AUCTION_ACCOUNT);
                    $auctionBidderIdList = implode(', ', $auctionBidderIds);
                    $logData = ['u' => $editorUserId, 'acc' => $accountId, 'is one of aub' => $auctionBidderIdList];
                    log_error(self::LOG_PREFIX . $collector->lastAddedErrorMessage() . composeSuffix($logData));
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return int[]
     * @internal for unit test
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @param int[] $auctionBidderIds
     * @param int $editorUserId
     */
    public function deleteByIds(array $auctionBidderIds, int $editorUserId): void
    {
        // TODO: we should check write access permission for editor admin
        if (!$auctionBidderIds) {
            log_error('No auction bidders selected for delete');
            return;
        }

        $lotItemIdsPerAuction = [];
        $auctionBidders = $this->createAuctionBidderReadRepository()
            ->enableReadOnlyDb(true)
            ->filterId($auctionBidderIds)
            ->loadEntities();
        foreach ($auctionBidders as $auctionBidder) {
            $auctionId = $auctionBidder->AuctionId;
            $userId = $auctionBidder->UserId;

            $auctionRegistrationManger = $this->getAuctionRegistrationManager();
            $auctionRegistrationManger->construct($userId, $auctionId, $userId);
            $auctionRegistrationManger->resetBiddersAcceptedOptions();

            $rows = $this->createAbsenteeBidReadRepository()
                ->enableReadOnlyDb(true)
                ->filterAuctionId($auctionId)
                ->filterUserId($userId)
                ->select(['ab.lot_item_id'])
                ->loadRows();
            $lotItemIds = ArrayCast::arrayColumnInt($rows, 'lot_item_id');
            if (!isset($lotItemIdsPerAuction[$auctionId])) {
                $lotItemIdsPerAuction[$auctionId] = [];
            }
            $lotItemIdsPerAuction[$auctionId] = array_merge($lotItemIdsPerAuction[$auctionId], $lotItemIds);

            $this->getAbsenteeBidDeleter()->deleteForAuctionAndUserAvoidObserver($auctionId, $userId);

            $this->getAuctionBidderWriteRepository()->deleteWithModifier($auctionBidder, $editorUserId);
        }

        foreach ($lotItemIdsPerAuction as $auctionId => $lotItemIds) {
            $lotItemIds = array_unique($lotItemIds);
            foreach ($lotItemIds as $lotItemId) {
                $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId, true);
                if (!$auctionLot) {
                    log_error(
                        "Available auction lot item not found, when want to refresh its cache"
                        . composeSuffix(['li' => $lotItemId, 'a' => $auctionId])
                    );
                    continue;
                }
                $this->createAuctionLotCacheUpdater()->refreshForAuctionLot($auctionLot, $editorUserId);
            }
            $filterCondition = $this->createCacheInvalidatorFilterCondition()->filterAuctionId([$auctionId]);
            $this->createAuctionCacheInvalidator()->invalidate($filterCondition, $editorUserId);
        }
    }
}
