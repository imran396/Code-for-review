<?php
/**
 * Move lots from one auction to another
 *
 * SAM-4005: Lot moving logic
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           05 Dec, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Move;

use Auction;
use AuctionLotItem;
use DateInterval;
use InvalidArgumentException;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\AdminLotPresaleUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Date\Calculate\AuctionDateFromLotsDetectorCreateTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\AuctionLotDatesUpdaterCreateTrait;
use Sam\AuctionLot\Date\Dto\TimedAuctionLotDates;
use Sam\AuctionLot\Delete\AuctionLotDeleter;
use Sam\AuctionLot\Delete\AuctionLotDeleterCreateTrait;
use Sam\AuctionLot\Delete\TimedItem\TimedItemDeleterCreateTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\AuctionLot\LotNo\Fill\LotNoAutoFillerAwareTrait;
use Sam\AuctionLot\Validate\TimedItemExistenceChecker;
use Sam\Bidding\AbsenteeBid\Load\AbsenteeBidLoaderAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Text\TextChecker;
use Sam\Date\CurrentDateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\WriteRepository\Entity\AbsenteeBid\AbsenteeBidWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\TimedOnlineItem\TimedOnlineItemWriteRepositoryAwareTrait;

/**
 * Class AuctionLotMover
 * @package Sam\Lot\Move
 */
class AuctionLotMover extends CustomizableClass
{
    use AbsenteeBidLoaderAwareTrait;
    use AbsenteeBidWriteRepositoryAwareTrait;
    use AuctionDateFromLotsDetectorCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotDateAssignorCreateTrait;
    use AuctionLotDatesUpdaterCreateTrait;
    use AuctionLotDeleterCreateTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use LotItemAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotNoAutoFillerAwareTrait;
    use LotRendererAwareTrait;
    use SettingsManagerAwareTrait;
    use TimedItemDeleterCreateTrait;
    use TimedItemLoaderAwareTrait;
    use TimedOnlineItemWriteRepositoryAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    protected bool $isBidMovingEnabled = false;
    protected ?int $sourceAuctionId = null;
    protected ?int $targetAuctionId = null;
    protected ?int $movedBidCount = null;
    protected ?int $timezoneId = null;
    protected ?AuctionLotItem $sourceAuctionLot = null;
    protected ?AuctionLotItem $targetAuctionLot = null;
    protected ?Auction $sourceAuction = null;
    protected ?Auction $targetAuction = null;
    protected string $errorMessage = '';
    protected string $successMessage = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $lotItem = $this->getLotItem();
        if (!$lotItem) {
            log_error("Available lot item not found, when auction lot moving" . composeSuffix(['li' => $this->getLotItemId()]));
            return false;
        }
        $targetAuction = $this->getTargetAuction();
        $targetAuctionLot = $this->getAuctionLotLoader()->load($lotItem->Id, $targetAuction->Id);
        if ($targetAuctionLot instanceof AuctionLotItem) {
            $itemNo = $this->getLotRenderer()->renderItemNo($lotItem);
            $auctionName = $this->getAuctionRenderer()->renderName($targetAuction);
            $auctionName = ee($auctionName);
            $this->setErrorMessage(
                "Lot (item # {$itemNo}) already exists in auction {$auctionName}"
                . " cannot move this item."
            );
        } else {
            $blacklistPhrase = $targetAuction->BlacklistPhrase;
            if (
                $lotItem->Name !== ''
                && $blacklistPhrase !== ''
            ) {
                $foundPhrase = TextChecker::new()->isInBlacklistPhrase($lotItem->Name, $blacklistPhrase);
                if ($foundPhrase !== '') {
                    $itemNo = $this->getLotRenderer()->renderItemNo($lotItem);
                    $errorMessage = "Lot item # {$itemNo} cannot be moved. "
                        . $this->getTranslator()->translate('ITEM_BLACKLIST_PHRASE', 'item') . $foundPhrase;
                    $this->setErrorMessage($errorMessage);
                }
            }
        }
        $isSuccess = $this->errorMessage === '';
        return $isSuccess;
    }

    /**
     */
    public function move(): void
    {
        $sourceAuctionLot = $this->getSourceAuctionLot();
        $targetAuction = $this->getTargetAuction();

        $targetAuctionLot = $this->produceTargetAuctionLot();

        if ($targetAuction->isTimed()) {
            $this->produceTimedItem();
        }

        if ($this->isBidMovingEnabled) {
            $bidMover = BidMover::new()
                ->setSourceAuctionLot($sourceAuctionLot)
                ->setTargetAuctionLot($targetAuctionLot)
                ->setSourceAuction($this->getSourceAuction())
                ->setTargetAuction($this->getTargetAuction());
            $bidMover->move();
            $this->movedBidCount = $bidMover->countMovedBids();
        } elseif ($this->getSourceAuction()->isTimed()) {
            // TODO
        } else {
            $absenteeBids = $this->getAbsenteeBidLoader()
                ->loadForAuctionLot($sourceAuctionLot->LotItemId, $sourceAuctionLot->AuctionId, true);
            foreach ($absenteeBids as $absenteeBid) {
                $this->getAbsenteeBidWriteRepository()->deleteWithModifier($absenteeBid, $this->getEditorUserId());
            }
        }

        $this->produceSuccessMessage();

        $this->createAuctionLotDeleter()
            ->construct([AuctionLotDeleter::OP_SHOULD_DELETE_TIMED_ONLINE_ITEM => false])
            ->delete($sourceAuctionLot, $this->getEditorUserId());
    }

    /**
     * @return AuctionLotItem
     */
    protected function produceTargetAuctionLot(): AuctionLotItem
    {
        $targetAuction = $this->getTargetAuction();
        $targetAuctionLot = $this->createEntityFactory()->auctionLotItem();
        $targetAuctionLot->AccountId = $targetAuction->AccountId;
        $targetAuctionLot->AuctionId = $targetAuction->Id;
        $targetAuctionLot->LotItemId = $this->getLotItemId();
        $targetAuctionLot->StartClosingDate = $targetAuction->StartClosingDate;
        $targetAuctionLot->TimezoneId = $targetAuction->TimezoneId;
        $targetAuctionLot->toActive();
        $lastBidTransaction = $this->createBidTransactionLoader()->loadLastActiveBid($this->getLotItemId(), $targetAuction->Id);
        if ($lastBidTransaction) {
            $targetAuctionLot->linkCurrentBid($lastBidTransaction->Id);
        }
        $this->getLotNoAutoFiller()
            ->setAutoIncrementMode(Constants\Lot::LOT_NO_AUTO_INC_AUCTION_OPTION)
            ->fill($targetAuctionLot);

        $this->getAuctionLotItemWriteRepository()->saveWithModifier($targetAuctionLot, $this->getEditorUserId());
        $this->targetAuctionLot = $targetAuctionLot;
        return $targetAuctionLot;
    }

    /**
     * @return void
     */
    protected function produceTimedItem(): void
    {
        $lotItem = $this->getLotItem();
        if (!$lotItem) {
            log_error("LotItem cannot be found");
            return;
        }

        $sourceAuction = $this->getSourceAuction();
        $targetAuction = $this->getTargetAuction();
        if ($targetAuction->isTimedOngoing()) {
            // Dates of ongoing auctions are not defined
            $startBiddingDate = $this->getCurrentDateUtc();
            $startClosingDate = $this->getCurrentDateUtc()->add(new DateInterval('P7D'));
        } else {    // Constants\Auction::ET_SCHEDULED
            $startBiddingDate = clone $targetAuction->StartBiddingDate;
            $startClosingDate = clone $targetAuction->StartClosingDate;
        }
        if (
            $targetAuction->isTimedScheduled()
            && !$targetAuction->ExtendAll
        ) {
            if ($targetAuction->isAuctionToItemsDateAssignment()) {
                if ($targetAuction->StaggerClosing) {
                    // TODO: extract to MultipleLotMover
                    $this->createAuctionLotDatesUpdater()->update($targetAuction->Id, $this->getEditorUserId());
                }
            } elseif ($targetAuction->isItemsToAuctionDateAssignment()) {
                $startClosingDate = $this->getCurrentDateUtc()->add(new DateInterval('P7D'));
                $auctionStartClosingDateFromLots = $this->createAuctionDateFromLotsDetector()->detectStartClosingDate($targetAuction->Id);
                if ($auctionStartClosingDateFromLots) {
                    $startClosingDate = $auctionStartClosingDateFromLots;
                }
            }
        }

        $isTimedItem = TimedItemExistenceChecker::new()->exist($lotItem->Id, $sourceAuction->Id);
        if ($isTimedItem) {  // if timed info exists in source (old) auction
            // then we delete timed info in target (new) auction
            $this->createTimedItemDeleter()->deleteByLotItemIdAndAuctionId(
                $lotItem->Id,
                $targetAuction->Id,
                $this->getEditorUserId()
            );

            // we get TimedOnlineItem of source auction, because we want to keep previous values
            $timedItem = $this->getTimedItemLoader()->loadOrCreate($lotItem->Id, $sourceAuction->Id);
            // and replace its auction
            $timedItem->AuctionId = $targetAuction->Id;
            log_debug(
                "Auction value replaced in current TimedOnlineItem"
                . composeSuffix(
                    [
                        'li' => $lotItem->Id,
                        'target a' => $targetAuction->Id,
                        'source a' => $sourceAuction->Id,
                    ]
                )
            );
        } else {
            $timedItem = $this->getTimedItemLoader()->loadOrCreate($lotItem->Id, $targetAuction->Id);
        }
        $this->getTimedOnlineItemWriteRepository()->saveWithModifier($timedItem, $this->getEditorUserId());

        // Then we define date values
        $targetAuctionLot = $this->getAuctionLotLoader()->load($timedItem->LotItemId, $timedItem->AuctionId);
        if ($targetAuctionLot) {
            $auctionLotDates = TimedAuctionLotDates::new()
                ->setStartBiddingDate($startBiddingDate)
                ->setStartClosingDate($startClosingDate);
            $this->createAuctionLotDateAssignor()->assignForTimed($targetAuctionLot, $auctionLotDates, $this->getEditorUserId());
        } else {
            log_error(
                "Available target auction lot not found"
                . composeSuffix(['li' => $timedItem->LotItemId, 'a' => $timedItem->AuctionId])
            );
        }
    }

    /**
     * @return string
     */
    protected function produceSuccessMessage(): string
    {
        $itemNo = $this->getLotRenderer()->renderItemNo($this->getLotItem());
        $sourceLotNo = $this->getLotRenderer()->renderLotNo($this->getSourceAuctionLot());
        // Link to target Lot Edit page
        $targetLotNo = $this->getLotRenderer()->renderLotNo($this->targetAuctionLot);
        $targetLotEditUrl = $this->getUrlBuilder()->build(
            AdminLotEditUrlConfig::new()->forWeb(
                $this->targetAuctionLot->LotItemId,
                $this->targetAuctionLot->AuctionId
            )
        );
        $targetLotEditLink = "<a href=\"{$targetLotEditUrl}\">{$targetLotNo}</a>";
        // Link to target Auction Lots page
        $targetAuctionName = $this->getAuctionRenderer()->renderName($this->getTargetAuction());
        $targetAuctionLotsUrl = $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(
                Constants\Url::A_AUCTIONS_LOT_LIST,
                $this->targetAuctionLot->AuctionId
            )
        );
        $targetAuctionLotsLink = "<a href=\"{$targetAuctionLotsUrl}\">{$targetAuctionName}</a>";
        // Link to target Lot Bids page
        $movedBidText = '';
        if ($this->isBidMovingEnabled) {
            if ($this->getTargetAuction()->isTimed()) {
                // TODO
            } else {
                // TODO: sold lot bid_transaction
                $movedBidText = $this->movedBidCount === 1
                    ? "{$this->movedBidCount} absentee bid is"
                    : "{$this->movedBidCount} absentee bids are";
                $bidsUrl = $this->getUrlBuilder()->build(
                    AdminLotPresaleUrlConfig::new()->forWeb(
                        $this->targetAuctionLot->LotItemId,
                        $this->targetAuctionLot->AuctionId
                    )
                );
                $bidsLink = "<a href=\"{$bidsUrl}\">{$movedBidText}</a>";
                $movedBidText = " and {$bidsLink} moved";
            }
        }
        // Result success message
        $message = "Lot# {$sourceLotNo} (item# {$itemNo})"
            . " has been moved to auction \"{$targetAuctionLotsLink}\""
            . " with assigned new lot# {$targetLotEditLink}{$movedBidText}";
        $this->setSuccessMessage($message);
        return $message;
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function enableBidMoving(bool $enabled): static
    {
        $this->isBidMovingEnabled = $enabled;
        return $this;
    }

    /**
     * @param int $auctionId
     * @return static
     */
    public function setSourceAuctionId(int $auctionId): static
    {
        $this->sourceAuctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @param Auction $auction
     * @return static
     */
    public function setSourceAuction(Auction $auction): static
    {
        $this->sourceAuction = $auction;
        $this->setSourceAuctionId($auction->Id);
        return $this;
    }

    /**
     * @return Auction
     */
    protected function getSourceAuction(): Auction
    {
        if ($this->sourceAuction === null) {
            $this->sourceAuction = $this->getAuctionLoader()->load($this->sourceAuctionId);
        }
        if (!$this->sourceAuction) {
            throw new InvalidArgumentException(
                "Source auction not found"
                . composeSuffix(['a' => $this->sourceAuctionId])
            );
        }
        return $this->sourceAuction;
    }

    /**
     * @param int $auctionId
     * @return static
     */
    public function setTargetAuctionId(int $auctionId): static
    {
        $this->targetAuctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @param Auction $auction
     * @return static
     */
    public function setTargetAuction(Auction $auction): static
    {
        $this->targetAuction = $auction;
        $this->setTargetAuctionId($auction->Id);
        return $this;
    }

    /**
     * @return Auction
     */
    protected function getTargetAuction(): Auction
    {
        if ($this->targetAuction === null) {
            $this->targetAuction = $this->getAuctionLoader()->load($this->targetAuctionId);
        }
        if (!$this->targetAuction) {
            throw new InvalidArgumentException(
                "Target auction not found"
                . composeSuffix(['a' => $this->targetAuctionId])
            );
        }
        return $this->targetAuction;
    }

    /**
     * @param AuctionLotItem $sourceAuctionLot
     * @return static
     */
    public function setSourceAuctionLot(AuctionLotItem $sourceAuctionLot): static
    {
        $this->sourceAuctionLot = $sourceAuctionLot;
        return $this;
    }

    /**
     * @return AuctionLotItem
     */
    protected function getSourceAuctionLot(): AuctionLotItem
    {
        if ($this->sourceAuctionLot === null) {
            $this->sourceAuctionLot = $this->getAuctionLotLoader()->load($this->getLotItemId(), $this->sourceAuctionId);
        }
        if (!$this->sourceAuctionLot) {
            throw new InvalidArgumentException(
                "Source AuctionLotItem not found "
                . composeSuffix(['li' => $this->getLotItemId(), 'a' => $this->sourceAuctionId])
            );
        }
        return $this->sourceAuctionLot;
    }

    /**
     * @param string $message
     * @return static
     */
    protected function setErrorMessage(string $message): static
    {
        $this->errorMessage = trim($message);
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $successMessage
     * @return static
     */
    public function setSuccessMessage(string $successMessage): static
    {
        $this->successMessage = trim($successMessage);
        return $this;
    }

    /**
     * @return string
     */
    public function getSuccessMessage(): string
    {
        return $this->successMessage;
    }

    /**
     * @return int
     */
    public function getTimezoneId(): int
    {
        if ($this->timezoneId === null) {
            $this->timezoneId = (int)$this->getSettingsManager()->get(Constants\Setting::TIMEZONE_ID, $this->getSourceAuction()->AccountId);
        }
        return $this->timezoneId;
    }

    /**
     * @param int $timezoneId
     * @return static
     */
    public function setTimezoneId(int $timezoneId): static
    {
        $this->timezoneId = Cast::toInt($timezoneId, Constants\Type::F_INT_POSITIVE);
        return $this;
    }
}
