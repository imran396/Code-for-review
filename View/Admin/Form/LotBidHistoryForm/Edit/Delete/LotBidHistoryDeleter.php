<?php
/**
 * It is used to delete BidTransaction records. if the deleted item is the current bid then it updates auctionLot and lotItem data.
 *
 * SAM-6684: Merge the two admin bidding histories and Improvement for Lot bidding History
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/29/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotBidHistoryForm\Edit\Delete;

use AuctionLotItem;
use BidTransaction;
use LotItem;
use Sam\AuctionLot\Load\AuctionLotLoader;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoader;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Lot\Load\LotItemLoader;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\BidTransaction\BidTransactionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;

/**
 * Class Deleter
 * @package Sam\View\Admin\Form\LotBidHistoryForm\Edit
 */
class LotBidHistoryDeleter extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use BidTransactionWriteRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use OptionalsTrait;

    // --- Incoming values ---

    public const OP_AUCTION_LOT = OptionalKeyConstants::KEY_AUCTION_LOT; // ?AuctionLotItem
    public const OP_BID_TRANSACTION = 'bidTransaction'; // ?BidTransaction
    public const OP_BID_TRANSACTION_LAST_ACTIVE_BID = 'bidTransactionLastActiveBid'; // ?BidTransaction
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_LOT_ITEM = OptionalKeyConstants::KEY_LOT_ITEM; // ?LotItem

    protected int $lotItemId;
    protected int $auctionId;
    protected int $bidTransactionId;
    protected ?int $editorUserId;

    // --- Outgoing values ---

    protected ?string $successMessage = null;
    protected ?BidTransaction $deletedBidTransaction = null;
    protected ?AuctionLotItem $updatedAuctionLot = null;
    protected ?LotItem $updatedLotItem = null;

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $bidTransactionId
     * @param int $lotItemId
     * @param int $auctionId
     * @param int|null $editorUserId
     * @param array $optionals
     * @return $this
     */
    public function construct(
        int $bidTransactionId,
        int $lotItemId,
        int $auctionId,
        ?int $editorUserId,
        array $optionals = []
    ): static {
        $this->bidTransactionId = $bidTransactionId;
        $this->auctionId = $auctionId;
        $this->lotItemId = $lotItemId;
        $this->editorUserId = $editorUserId;
        $this->initOptionals($optionals);
        return $this;
    }

    // --- Main command method ---

    /**
     * @return void
     */
    public function delete(): void
    {
        /** @var BidTransaction $deletedBidTransaction */
        $deletedBidTransaction = $this->fetchOptional(self::OP_BID_TRANSACTION);
        $deletedBidTransaction->Deleted = true;
        $this->getBidTransactionWriteRepository()->saveWithModifier($deletedBidTransaction, $this->editorUserId);
        $this->deletedBidTransaction = $deletedBidTransaction;
        $this->updateAuctionLot();
        $this->setSuccessMessage("BidTransaction [" . $deletedBidTransaction->Id . "] has been deleted");
    }

    // --- Read results ---

    /**
     * @return BidTransaction|null
     */
    public function getDeletedBidTransaction(): ?BidTransaction
    {
        return $this->deletedBidTransaction;
    }

    /**
     * @return AuctionLotItem|null
     */
    public function getUpdatedAuctionLot(): ?AuctionLotItem
    {
        return $this->updatedAuctionLot;
    }

    /**
     * @return LotItem|null
     */
    public function getUpdatedLotItem(): ?LotItem
    {
        return $this->updatedLotItem;
    }

    /**
     * @return string|null
     */
    public function getSuccessMessage(): ?string
    {
        return $this->successMessage;
    }

    // --- Internal logic ---

    /**
     * Update auctionLot and lotItem for bid transaction deletion
     * @return void
     */
    protected function updateAuctionLot(): void
    {
        /** @var AuctionLotItem|null $auctionLot */
        $auctionLot = $this->fetchOptional(self::OP_AUCTION_LOT);
        /** @var LotItem|null $lotItem */
        $lotItem = $this->fetchOptional(self::OP_LOT_ITEM);
        //if the deleted item is the current bid
        if (
            $auctionLot
            && $auctionLot->CurrentBidId === $this->bidTransactionId
        ) {
            //get the latest bid after deletion
            /** @var BidTransaction|null $lastBidTransaction */
            $lastBidTransaction = $this->fetchOptional(self::OP_BID_TRANSACTION_LAST_ACTIVE_BID);
            if ($lastBidTransaction) {
                [$auctionLot, $lotItem] = $this->updateForLastBidTransaction(
                    $auctionLot,
                    $lastBidTransaction,
                    $lotItem,
                    $this->editorUserId
                );
            } else {
                [$auctionLot, $lotItem] = $this->updateForPreviousBidTransaction($auctionLot, $lotItem, $this->editorUserId);
            }
        }
        $this->updatedAuctionLot = $auctionLot;
        $this->updatedLotItem = $lotItem;
    }

    /**
     * @param int|null $currentBidId null- means currentBidId absent, by this way we unset currentBidId value form auctionLot
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     * @return AuctionLotItem
     */
    protected function updateCurrentBidId(?int $currentBidId, AuctionLotItem $auctionLot, int $editorUserId): AuctionLotItem
    {
        $currentBidId
            ? $auctionLot->linkCurrentBid($currentBidId)
            : $auctionLot->unlinkCurrentBid();
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        return $auctionLot;
    }

    /**
     * @param float $hammerPrice
     * @param int $winningBidderId
     * @param LotItem $lotItem
     * @param int $editorUserId
     * @return LotItem
     */
    protected function updateSoldLotItemInfo(float $hammerPrice, int $winningBidderId, LotItem $lotItem, int $editorUserId): LotItem
    {
        $lotItem->HammerPrice = $hammerPrice;
        $lotItem->WinningBidderId = $winningBidderId;
        $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
        return $lotItem;
    }

    /**
     * @param LotItem $lotItem
     * @param int $editorUserId
     * @return LotItem
     */
    protected function removeSoldLotItemInfo(LotItem $lotItem, int $editorUserId): LotItem
    {
        $lotItem->wipeOutSoldInfo();
        $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
        return $lotItem;
    }

    /**
     * Update auction lot current bid id and lot item bid information
     * @param AuctionLotItem $auctionLot
     * @param BidTransaction $lastBidTransaction
     * @param LotItem|null $lotItem
     * @param int $editorUserId
     * @return array
     */
    protected function updateForLastBidTransaction(
        AuctionLotItem $auctionLot,
        BidTransaction $lastBidTransaction,
        ?LotItem $lotItem,
        int $editorUserId
    ): array {
        $auctionLot = $this->updateCurrentBidId($lastBidTransaction->Id, $auctionLot, $editorUserId);
        //if the lot item is sold, update as well
        if ($auctionLot->isAmongWonStatuses()) {
            //this is the winning bid on a sold lot
            //get the lot item
            $lotItem = $lotItem
                ? $this->updateSoldLotItemInfo($lastBidTransaction->Bid, $lastBidTransaction->UserId, $lotItem, $editorUserId)
                : null;
        }
        return [$auctionLot, $lotItem];
    }

    /**
     * Remove auction lot current bid id, make auctionLot unsold and wiped lot item bid information
     * @param AuctionLotItem $auctionLot
     * @param LotItem|null $lotItem
     * @param int $editorUserId
     * @return array
     */
    protected function updateForPreviousBidTransaction(
        AuctionLotItem $auctionLot,
        ?LotItem $lotItem,
        int $editorUserId
    ): array {
        $auctionLot = $this->updateCurrentBidId(null, $auctionLot, $editorUserId);
        //if the lot item is sold, update as well
        if ($auctionLot->isAmongWonStatuses()) {
            $auctionLot->toUnsold();
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
            $lotItem = $lotItem ? $this->removeSoldLotItemInfo($lotItem, $editorUserId) : null;
        }
        return [$auctionLot, $lotItem];
    }

    /**
     * @param string $successMessage
     * @return $this
     */
    protected function setSuccessMessage(string $successMessage): static
    {
        $this->successMessage = $successMessage;
        return $this;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $lotItemId = $this->lotItemId;
        $auctionId = $this->auctionId;
        $bidTransactionId = $this->bidTransactionId;
        $isReadOnlyDb = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $optionals[self::OP_AUCTION_LOT] = $optionals[self::OP_AUCTION_LOT]
            ?? static function () use ($lotItemId, $auctionId, $isReadOnlyDb): ?AuctionLotItem {
                return AuctionLotLoader::new()->load($lotItemId, $auctionId, $isReadOnlyDb);
            };

        $optionals[self::OP_LOT_ITEM] = $optionals[self::OP_LOT_ITEM]
            ?? static function () use ($lotItemId, $isReadOnlyDb): ?LotItem {
                return LotItemLoader::new()->load($lotItemId, $isReadOnlyDb);
            };

        $optionals[self::OP_BID_TRANSACTION] = $optionals[self::OP_BID_TRANSACTION]
            ?? static function () use ($bidTransactionId, $isReadOnlyDb): ?BidTransaction {
                return BidTransactionLoader::new()->loadById($bidTransactionId, $isReadOnlyDb);
            };
        $optionals[self::OP_BID_TRANSACTION_LAST_ACTIVE_BID] = $optionals[self::OP_BID_TRANSACTION_LAST_ACTIVE_BID]
            ?? static function () use ($lotItemId, $auctionId, $isReadOnlyDb): ?BidTransaction {
                return BidTransactionLoader::new()->loadLastActiveBid($lotItemId, $auctionId, $isReadOnlyDb);
            };
        $this->setOptionals($optionals);
    }
}
