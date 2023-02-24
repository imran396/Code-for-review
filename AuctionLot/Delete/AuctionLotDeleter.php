<?php
/**
 * Helper methods to delete auction lot item
 *
 * SAM-6697: Lot deleters for v3.5 https://bidpath.atlassian.net/browse/SAM-6697
 * SAM-3822: Lot deleter https://bidpath.atlassian.net/browse/SAM-3822
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           24 Jul, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Delete;

use AuctionLotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\BulkGroup\Delete\LotBulkGroupRevokerCreateTrait;
use Sam\AuctionLot\Delete\TimedItem\TimedItemDeleterCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;

/**
 * Class AuctionLotDeleter
 * @package Sam\Lot\Delete
 */
class AuctionLotDeleter extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use LotBulkGroupRevokerCreateTrait;
    use LotRendererAwareTrait;
    use OptionalsTrait;
    use TimedItemDeleterCreateTrait;

    /**
     * By default Piecemeal lots are not revoked from lot bulk group - for soft-delete recovering purposes.
     * (and we currently don't change this behavior)
     * @var string
     */
    public const OP_SHOULD_REVOKE_LOT_BULK_GROUPING_ROLE_ON_DELETE = 'revokeLotBulkGroupingRoleOnDelete';
    /**
     * By default we deleting related TimedOnlineItem record (def: true)
     * TODO: implement soft-deleting
     */
    public const OP_SHOULD_DELETE_TIMED_ONLINE_ITEM = 'deleteTimedOnlineItem';

    /** @var AuctionLotItem[] */
    protected array $deletedAuctionLots = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Soft-delete lot item with all dependencies
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    public function delete(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $auctionLot = $this->processRevokingOfLotBulkGroupingRole($auctionLot, $editorUserId);
        $auctionLot->toDeleted();
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        $this->deletedAuctionLots[] = $auctionLot;
        $this->log($auctionLot);
        /**
         * TODO: We should implement soft-deleting of timed online item
         */
        $this->processDeletingOfTimedOnlineItem($auctionLot, $editorUserId);
    }

    /**
     * Delete array of lot items with all dependencies
     * @param AuctionLotItem[] $auctionLots
     * @param int $editorUserId
     */
    public function deleteArray(array $auctionLots, int $editorUserId): void
    {
        foreach ($auctionLots as $auctionLot) {
            $this->delete($auctionLot, $editorUserId);
        }
    }

    /**
     * Get user by id and then delete it with all dependencies
     * @param int $auctionLotId
     * @param int $editorUserId
     */
    public function deleteById(int $auctionLotId, int $editorUserId): void
    {
        $auctionLot = $this->getAuctionLotLoader()->loadById($auctionLotId);
        if ($auctionLot) {
            $this->delete($auctionLot, $editorUserId);
        }
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function deleteByLotItemIdAndAuctionId(int $lotItemId, int $auctionId, int $editorUserId): void
    {
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId);
        if ($auctionLot) {
            $this->delete($auctionLot, $editorUserId);
        }
    }

    /**
     * @return AuctionLotItem[]
     */
    public function deletedAuctionLots(): array
    {
        return $this->deletedAuctionLots;
    }

    /**
     * @return AuctionLotItem|null
     */
    public function lastDeletedAuctionLot(): ?AuctionLotItem
    {
        return $this->deletedAuctionLots[count($this->deletedAuctionLots) - 1] ?? null;
    }

    /**
     * @param AuctionLotItem $auctionLot
     */
    protected function log(AuctionLotItem $auctionLot): void
    {
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $logData = [
            'li' => $auctionLot->LotItemId,
            'a' => $auctionLot->AuctionId,
            'ali' => $auctionLot->Id,
            'lot#' => $lotNo,
            'u' => $auctionLot->ModifiedBy
        ];
        $message = "Auction Lot soft-deleted" . composeSuffix($logData);
        log_debug($message);
    }

    /**
     * Next functionality is disabled by default by installation config option.
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     * @return AuctionLotItem
     */
    protected function processRevokingOfLotBulkGroupingRole(AuctionLotItem $auctionLot, int $editorUserId): AuctionLotItem
    {
        if ($auctionLot->hasMasterRole()) {
            if ($this->fetchOptional(self::OP_SHOULD_REVOKE_LOT_BULK_GROUPING_ROLE_ON_DELETE)) { // disabled by def.
                $auctionLot->removeFromBulkGroup();
                $this->createLotBulkGroupRevoker()->revokePiecemealLots($auctionLot->Id, $editorUserId);
            } else {
                log_debug(
                    'Master lot soft-deleting, revoking of its role and its piecemeal lots from bulk grouping is disabled'
                    . composeSuffix(['master ali' => $auctionLot->Id, 'a' => $auctionLot->AuctionId, 'li' => $auctionLot->LotItemId])
                );
            }
        } elseif ($auctionLot->hasPiecemealRole()) {
            if ($this->fetchOptional(self::OP_SHOULD_REVOKE_LOT_BULK_GROUPING_ROLE_ON_DELETE)) { // disabled by def.
                $auctionLot->removeFromBulkGroup();
            } else {
                log_debug(
                    'Piecemeal lot soft-deleting, but revoking of its piecemeal role is disabled'
                    . composeSuffix(['piecemeal ali' => $auctionLot->Id, 'a' => $auctionLot->AuctionId, 'li' => $auctionLot->LotItemId])
                );
            }
        }
        return $auctionLot;
    }

    /**
     * Delete TimedOnlineItem for passed AuctionLotItem, if it exists and deleting is required.
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    protected function processDeletingOfTimedOnlineItem(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        if (!$this->fetchOptional(self::OP_SHOULD_DELETE_TIMED_ONLINE_ITEM)) {
            return;
        }

        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId, true);
        if (
            $auction
            && $auction->isTimed()
        ) {
            $this->createTimedItemDeleter()->deleteByLotItemIdAndAuctionId(
                $auctionLot->LotItemId,
                $auctionLot->AuctionId,
                $editorUserId
            );
        }
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_SHOULD_REVOKE_LOT_BULK_GROUPING_ROLE_ON_DELETE] = $optionals[self::OP_SHOULD_REVOKE_LOT_BULK_GROUPING_ROLE_ON_DELETE]
            ?? static function () {
                return ConfigRepository::getInstance()->get('core->lot->bulkGroup->revokeRoleOnDelete');
            };
        $optionals[self::OP_SHOULD_DELETE_TIMED_ONLINE_ITEM] = $optionals[self::OP_SHOULD_DELETE_TIMED_ONLINE_ITEM]
            ?? true;
        $this->setOptionals($optionals);
    }
}
