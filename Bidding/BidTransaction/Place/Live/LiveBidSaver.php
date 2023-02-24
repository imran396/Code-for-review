<?php
/**
 * SAM-6143: Simplify live and timed bid registration logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           5/27/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidTransaction\Place\Live;

use Auction;
use AuctionLotItem;
use BidTransaction;
use Exception;
use LotItem;
use RtbCurrent;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Bidding\BidTransaction\Place\Base\AnyBidSaverCreateTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Load\RtbLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class LiveBidSaver
 * @package
 */
class LiveBidSaver extends CustomizableClass
{
    use AnyBidSaverCreateTrait;
    use AuctionAwareTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use DbConnectionTrait;
    use LotItemAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use RtbLoaderAwareTrait;
    use UserAwareTrait;
    use UserLoaderAwareTrait;

    public const ERR_LOT_ITEM_ABSENT = 1;
    public const ERR_AUCTION_ABSENT = 2;
    public const ERR_AUCTION_LOT_ABSENT = 3;
    public const ERR_AMOUNT_INVALID = 4;
    public const ERR_BID_AGAINST_SELF = 5;
    public const ERR_RTB_STATE_ABSENT = 6;
    public const ERR_DB_TRANSACTION_FAILED = 7;

    /**
     * @var string
     */
    protected string $errorMessage = '';
    /**
     * @var float
     */
    protected float $amount = 0.;
    /**
     * @var string
     */
    protected string $httpReferrer = '';
    /**
     * @var RtbCurrent|null null leads to lazy load by auction id
     */
    protected ?RtbCurrent $rtbCurrent = null;
    /**
     * Flag that prevents duplicated loading query
     * @var bool
     */
    protected bool $isLoadedLastBidTransaction = false;
    /**
     * @var BidTransaction|null
     * null + $this->isLoadedLastBidTransaction == true: means absent bid transaction,
     * null + $this->isLoadedLastBidTransaction == false: leads to lazy load of last bid
     */
    protected ?BidTransaction $lastBidTransaction = null;
    /**
     * @var int|null
     */
    protected ?int $editorUserId = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return BidTransaction|null null means that bid cannot be placed
     */
    public function place(): ?BidTransaction
    {
        if (!$this->checkInvariants()) {
            $logData = [
                'li' => $this->getLotItemId(),
                'a' => $this->getAuctionId(),
                'u' => $this->getUserId() ?: 'floor',
                'amount' => $this->amount,
            ];
            log_error($this->errorMessage() . composeSuffix($logData));
            return null;
        }

        /** @var LotItem $lotItem - LotItem existence should be guaranteed by invariants check */
        $lotItem = $this->getLotItem();
        /** @var Auction $auction - Auction existence should be guaranteed by invariants check */
        $auction = $this->getAuction();
        /** @var AuctionLotItem $auctionLot - AuctionLot existence should be guaranteed by invariants check */
        $auctionLot = $this->getAuctionLotLoader()->load($this->getLotItemId(), $this->getAuctionId());

        $isFailed = $this->isFailed();
        if ($isFailed) {
            $bidStatus = Constants\BidTransaction::BS_FAILED;
            $outbidTransaction = null;
        } else {
            $bidStatus = Constants\BidTransaction::BS_WINNER;
            $outbidTransaction = $this->getLastBidTransaction();
        }

        // start the transaction outside try-catch (makes no sense to rollback a failed begin)
        $db = $this->getDb();
        $db->TransactionBegin();
        try {
            $winnerBidTransaction = $this->createAnyBidSaver()
                ->enableBidFailed($isFailed)
                ->enableFloorBidder(!$this->getUserId())
                ->setAuction($auction)
                ->setBidAmount($this->amount)
                ->setBidStatus($bidStatus)
                ->setEditorUserId($this->editorUserId)
                ->setHttpReferrer($this->httpReferrer)
                ->setLotItem($lotItem)
                ->setOutbidTransaction($outbidTransaction) // is null for first bid on lot and in case of failed bid
                ->setUser($this->getUser())
                ->create();
            $this->finalize($winnerBidTransaction, $auctionLot);
            $db->TransactionCommit();
        } catch (Exception $e) {
            $db->TransactionRollback();
            $collector = $this->getResultStatusCollector();
            $collector->addErrorWithAppendedMessage(
                self::ERR_DB_TRANSACTION_FAILED,
                composeSuffix(['message' => $e->getMessage(), 'code' => $e->getCode()])
            );
            return null;
        }
        return $winnerBidTransaction;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount(float $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param string $httpReferrer
     * @return $this
     */
    public function setHttpReferrer(string $httpReferrer): static
    {
        $this->httpReferrer = $httpReferrer;
        return $this;
    }

    /**
     * @param int $editorUserId
     * @return $this
     */
    public function setEditorUserId(int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return bool
     */
    protected function checkInvariants(): bool
    {
        $this->initResultStatusCollector();
        $collector = $this->getResultStatusCollector();

        if (!$this->getLotItem()) {
            $collector->addError(self::ERR_LOT_ITEM_ABSENT);
            return false;
        }

        if (!$this->getAuction()) {
            $collector->addError(self::ERR_AUCTION_ABSENT);
            return false;
        }

        $auctionLot = $this->getAuctionLotLoader()->load($this->getLotItemId(), $this->getAuctionId());
        if (!$auctionLot) {
            $collector->addError(self::ERR_AUCTION_LOT_ABSENT);
            return false;
        }

        if (Floating::lteq($this->amount, 0.)) {
            $collector->addError(self::ERR_AMOUNT_INVALID);
            return false;
        }

        $lastBidTransaction = $this->getLastBidTransaction();
        if (
            $lastBidTransaction
            && $lastBidTransaction->UserId // is not floor bidder
            && $lastBidTransaction->UserId === $this->getUserId()
        ) {
            // Incorrect bid request coming for the same user
            $collector->addError(self::ERR_BID_AGAINST_SELF);
            return false;
        }

        // IK: We need to implement SAM-2784 first
        // Check outstanding limit (SAM-2710)
        // if ($auctionBidder    // it should be internet user, not floor bidder
        //     && \Sam\Bidder\Outstanding\Helper::new()->isLimitExceeded($auctionBidder))
        // {
        //     $message = $this->getTranslator()->translate('GENERAL_OUTSTANDING_LIMIT_EXCEEDED', ..);
        //     log_error($message);
        //     return false;
        // }

        $rtbCurrent = $this->getRtbCurrent();
        if (!$rtbCurrent) {
            $collector->addError(self::ERR_RTB_STATE_ABSENT);
            return false;
        }

        return true;
    }

    /**
     * Store high bid id in auction lot for caching purpose.
     * @param BidTransaction $winnerBidTransaction
     * @param AuctionLotItem $auctionLot
     */
    protected function finalize(BidTransaction $winnerBidTransaction, AuctionLotItem $auctionLot): void
    {
        if (!$winnerBidTransaction->Failed) {
            // Reload avoiding memory cache for reducing race condition probability (SAM-4179)
            $auctionLot->Reload();
            $auctionLot->linkCurrentBid($winnerBidTransaction->Id);
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $this->editorUserId);
        }
    }

    /**
     * Check, if incoming bid is failed (SAM-1065).
     * Existing online user cannot outbid another user, if his bid size is less or equal to current asking bid.
     * We want to register failed bid in result.
     * @return bool
     */
    protected function isFailed(): bool
    {
        /** @var RtbCurrent $rtbCurrent - RtbCurrent existence should be guaranteed by invariants check */
        $rtbCurrent = $this->getRtbCurrent();
        $isFailed = $this->getUserId()
            // RtbCurrent->NewBidBy can be deleted user
            && $this->getUserId() !== $rtbCurrent->NewBidBy
            && Floating::lteq($this->amount, $rtbCurrent->AskBid);
        return $isFailed;
    }

    /**
     * Initialize result status collector with error codes and messages
     */
    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            self::ERR_LOT_ITEM_ABSENT => 'Available lot item not found for saving live bid',
            self::ERR_AUCTION_ABSENT => 'Available auction not found for saving live bid',
            self::ERR_AUCTION_LOT_ABSENT => 'Available auction lot not found for saving live bid',
            self::ERR_AMOUNT_INVALID => 'Bid amount should be positive for saving live bid',
            self::ERR_BID_AGAINST_SELF => 'Cannot place bid against the same high bidder',
            self::ERR_RTB_STATE_ABSENT => 'Cannot place live bid, when rtbd state is undefined',
            self::ERR_DB_TRANSACTION_FAILED => 'Unable to place live bid, because db transaction failed',
        ];
        $this->getResultStatusCollector()->initAllErrors($errorMessages);
    }

    /**
     * @return BidTransaction|null null means there are no bids on lot
     */
    protected function getLastBidTransaction(): ?BidTransaction
    {
        if (
            $this->lastBidTransaction === null
            && !$this->isLoadedLastBidTransaction
        ) {
            $this->lastBidTransaction = $this->createBidTransactionLoader()
                ->loadLastActiveBid($this->getLotItemId(), $this->getAuctionId());
            $this->isLoadedLastBidTransaction = true;
        }
        return $this->lastBidTransaction;
    }

    /**
     * @param BidTransaction|null $bidTransaction null means absent bid transaction
     * @return $this
     * @internal
     */
    public function setLastBidTransaction(?BidTransaction $bidTransaction): static
    {
        $this->lastBidTransaction = $bidTransaction;
        $this->isLoadedLastBidTransaction = true;
        return $this;
    }

    /**
     * @return RtbCurrent|null
     */
    protected function getRtbCurrent(): ?RtbCurrent
    {
        if ($this->rtbCurrent === null) {
            $this->rtbCurrent = $this->getRtbLoader()->loadByAuctionId($this->getAuctionId());
        }
        return $this->rtbCurrent;
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return $this
     * @internal
     */
    public function setRtbCurrent(RtbCurrent $rtbCurrent): static
    {
        $this->rtbCurrent = $rtbCurrent;
        return $this;
    }
}
