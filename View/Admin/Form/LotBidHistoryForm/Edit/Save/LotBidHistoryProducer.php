<?php
/**
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

namespace Sam\View\Admin\Form\LotBidHistoryForm\Edit\Save;

use AuctionLotItem;
use BidTransaction;
use LotItem;
use Sam\AuctionLot\Load\AuctionLotLoader;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoader;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Lot\Load\LotItemLoader;
use Sam\Storage\WriteRepository\Entity\BidTransaction\BidTransactionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;

/**
 * Class LotBidHistoryProducer
 * @package Sam\View\Admin\Form\LotBidHistoryForm\Edit
 */
class LotBidHistoryProducer extends CustomizableClass
{
    use BidTransactionWriteRepositoryAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use OptionalsTrait;
    use ResultStatusCollectorAwareTrait;

    // --- Incoming values ---

    public const OP_AUCTION_LOT = OptionalKeyConstants::KEY_AUCTION_LOT; // ?AuctionLotItem
    public const OP_BID_TRANSACTION = OptionalKeyConstants::KEY_BID_TRANSACTION; // ?BidTransaction
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_LOT_ITEM = OptionalKeyConstants::KEY_LOT_ITEM; // ?LotItem
    public const OP_LAST_ACTIVE_BID_TRANSACTION = 'lastActiveBidTransaction'; // ?BidTransaction

    protected int $lotItemId;
    protected int $auctionId;
    protected int $bidTransactionId;
    protected LotBidHistoryInputDto $dto;
    protected ?int $editorUserId;

    // --- Outgoing values ---

    protected ?string $successMessage = null;
    protected ?BidTransaction $updatedBidTransaction = null;
    protected ?LotItem $updatedLotItem = null;

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotBidHistoryInputDto $dto
     * @param int $bidTransactionId
     * @param int|null $editorUserId
     * @param int $lotItemId
     * @param int $auctionId
     * @param array $optionals
     * @return static
     */
    public function construct(
        LotBidHistoryInputDto $dto,
        int $bidTransactionId,
        ?int $editorUserId,
        int $lotItemId,
        int $auctionId,
        array $optionals = []
    ): static {
        $this->lotItemId = $lotItemId;
        $this->auctionId = $auctionId;
        $this->bidTransactionId = $bidTransactionId;
        $this->dto = $dto;
        $this->editorUserId = $editorUserId;
        $this->initOptionals($optionals);
        return $this;
    }

    // --- Main command method ---

    /**
     * @return void
     */
    public function update(): void
    {
        $bidTransaction = $this->buildBidTransaction();
        $this->getBidTransactionWriteRepository()->saveWithModifier($bidTransaction, $this->editorUserId);
        $this->updatedLotItem = $this->updateLotItem($bidTransaction, $this->editorUserId);
        $this->setSuccessMessage("BidTransaction [{$this->bidTransactionId}] has been updated");
        $this->updatedBidTransaction = $bidTransaction;
    }

    // --- Read results ---

    public function updatedBidTransaction(): BidTransaction
    {
        return $this->updatedBidTransaction;
    }

    public function updatedLotItem(): LotItem
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
     * @param BidTransaction $bidTransaction
     * @param int $editorUserId
     * @return LotItem|null
     */
    protected function updateLotItem(BidTransaction $bidTransaction, int $editorUserId): ?LotItem
    {
        /** @var LotItem $lotItem */
        $lotItem = $this->fetchOptional(self::OP_LOT_ITEM);
        /** @var AuctionLotItem $auctionLot */
        $auctionLot = $this->fetchOptional(self::OP_AUCTION_LOT);
        //if the lot item is sold, update as well
        /** @var BidTransaction|null $lastBidTransaction */
        $lastBidTransaction = $this->fetchOptional(self::OP_LAST_ACTIVE_BID_TRANSACTION);
        //if this is the current bid id
        //OR if if this is the latest active bid
        //AND the lot is marked as sold
        if ((
                $auctionLot->CurrentBidId === $bidTransaction->Id
                || (
                    $lastBidTransaction
                    && $bidTransaction->Id === $lastBidTransaction->Id
                )
            )
            && $auctionLot->isAmongWonStatuses()
        ) {
            //this is the winning bid on a sold lot
            //get the lot item
            $lotItem = $this->buildLotItem($bidTransaction, $lotItem);
            $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
        }
        return $lotItem;
    }

    /**
     * @param BidTransaction $bidTransaction
     * @param LotItem $lotItem
     * @return LotItem
     */
    protected function buildLotItem(BidTransaction $bidTransaction, LotItem $lotItem): LotItem
    {
        $lotItem->HammerPrice = $bidTransaction->Bid;
        $lotItem->WinningBidderId = $bidTransaction->UserId;
        return $lotItem;
    }

    /**
     * @return BidTransaction
     */
    protected function buildBidTransaction(): BidTransaction
    {
        /** @var BidTransaction $bidTransaction */
        $bidTransaction = $this->fetchOptional(self::OP_BID_TRANSACTION);
        $bidTransaction->Bid = Cast::toFloat($this->dto->bidAmount);
        $bidTransaction->MaxBid = Cast::toFloat($this->dto->maxBid);
        $bidTransaction->UserId = Cast::toInt($this->dto->bidderUserId);
        return $bidTransaction;
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

        $optionals[self::OP_LAST_ACTIVE_BID_TRANSACTION] = $optionals[self::OP_LAST_ACTIVE_BID_TRANSACTION]
            ?? static function () use ($lotItemId, $auctionId, $isReadOnlyDb): ?BidTransaction {
                return BidTransactionLoader::new()->loadLastActiveBid($lotItemId, $auctionId, $isReadOnlyDb);
            };

        $this->setOptionals($optionals);
    }
}
