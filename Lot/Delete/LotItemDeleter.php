<?php
/**
 * Helper methods to delete auction lot item
 *
 * SAM-6697: Lot deleters for v3.5 https://bidpath.atlassian.net/browse/SAM-6697
 * SAM-3822: Lot deleters https://bidpath.atlassian.net/browse/SAM-3822
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

namespace Sam\Lot\Delete;

use LotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Delete\AuctionLotDeleterCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Order\Reorder\AuctionLotReordererAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Delete\Access\LotItemDeleteAccessCheckerAwareTrait;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;

/**
 * Class LotItemDeleter
 * @package Sam\Lot\Delete
 */
class LotItemDeleter extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotDeleterCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionLotReordererAwareTrait;
    use EditorUserAwareTrait;
    use LotItemDeleteAccessCheckerAwareTrait;
    use LotItemReadRepositoryCreateTrait;
    use LotItemWriteRepositoryAwareTrait;

    /**
     * @var LotItem[]
     */
    protected array $deletedLotItems = [];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Soft-delete lot item with all dependencies
     * @param LotItem $targetLotItem
     * @param int $editorUserId
     */
    public function delete(LotItem $targetLotItem, int $editorUserId): void
    {
        $canDeleteResult = $this->getLotItemDeleteAccessChecker()
            ->canDeleteLotItem($targetLotItem, $editorUserId);
        if ($canDeleteResult->hasError()) {
            log_error('Access denied to delete lot item' . composeSuffix($canDeleteResult->logData()));
            return;
        }
        log_debug('Access granted for deleting lot item' . composeSuffix($canDeleteResult->logData()));

        $this->deleteFromAllAuctions($targetLotItem->Id, $editorUserId);
        // Reload LotItem, because it may be updated in AuctionLotItemObserver, it affects to LotItem->AuctionInfo
        // It doesn't cause process break, because then we perform OLC-retry successfully.
        $targetLotItem->Reload();
        $targetLotItem->toDeleted();
        $this->getLotItemWriteRepository()->saveWithModifier($targetLotItem, $editorUserId);
        $this->deletedLotItems[] = $targetLotItem;
        $this->log($targetLotItem, $editorUserId);
    }

    /**
     * Delete array of lot items with all dependencies
     * @param LotItem[] $targetLotItems
     * @param int $editorUserId
     */
    public function deleteArray(array $targetLotItems, int $editorUserId): void
    {
        foreach ($targetLotItems as $lotItem) {
            $this->delete($lotItem, $editorUserId);
        }
    }

    /**
     * Get user by id and then delete it with all dependencies
     * @param int $targetLotItemId
     * @param int $editorUserId
     */
    public function deleteById(int $targetLotItemId, int $editorUserId): void
    {
        $lotItem = $this->createLotItemReadRepository()
            ->filterId($targetLotItemId)
            ->loadEntity();
        if ($lotItem) {
            $this->delete($lotItem, $editorUserId);
        }
    }

    /**
     * Mark AuctionLotItem LotStatusId as "Deleted" for each auction where LotItem presents
     *
     * @param int $targetLotItemId lot_item.id
     * @param int $editorUserId
     */
    public function deleteFromAllAuctions(int $targetLotItemId, int $editorUserId): void
    {
        $auctionLots = $this->getAuctionLotLoader()->loadByLotItemId($targetLotItemId);
        $auctionLotDeleter = $this->createAuctionLotDeleter()->construct();
        foreach ($auctionLots as $auctionLot) {
            $auctionLotDeleter->delete($auctionLot, $editorUserId);
            $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId, true);
            if (!$auction) {
                $logInfo = composeSuffix(['a' => $auctionLot->AuctionId, 'ali' => $auctionLot->Id]);
                log_error("Available auction not found for lot reordering after auction lot delete" . $logInfo);
                continue;
            }
            $this->getAuctionLotReorderer()->reorder($auction, $editorUserId);
        }
    }

    /**
     * @return LotItem
     */
    public function getDeletedLotItem(): LotItem
    {
        return $this->deletedLotItems[0];
    }

    /**
     * @return LotItem[]
     */
    public function getDeletedLotItems(): array
    {
        return $this->deletedLotItems;
    }

    /**
     * @param LotItem $lotItem
     * @param int $editorUserId
     */
    protected function log(LotItem $lotItem, int $editorUserId): void
    {
        $message = "Lot item soft-deleted" . composeSuffix(['li' => $lotItem->Id, 'u' => $editorUserId]);
        log_debug($message);
    }
}
