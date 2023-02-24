<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 26, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal;

use Sam\AuctionLot\Sync\Internal\ReserveNotMetCheckerCreateTrait;
use Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Dto\HybridCountdownInputDto;
use Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\HybridCountdownDataProducerAwareTrait;
use Sam\AuctionLot\Sync\Response\Concrete\PublicData\Dto\SyncAuctionLotDto;
use Sam\AuctionLot\Sync\Response\Concrete\PublicData\Generated\Message\AuctionLotData as AuctionLotDataMessage;
use Sam\AuctionLot\Sync\Response\Concrete\PublicData\Generated\Message\HybridCountdown as HybridCountdownMessage;
use Sam\Core\Auction\Bidder\AuctionBidderPureCheckerCreateTrait;
use Sam\Core\Bidding\BuyNow\BuyNowAvailabilityLiveCheckerCreateTrait;
use Sam\Core\Bidding\BuyNow\BuyNowAvailabilityTimedCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureCheckerAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Flag\UserFlagPureDetector;

/**
 * Contains methods for creating auction lot data protobuf message object for all auction types
 *
 * Class AuctionLotDataMessageFactory
 * @package Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal
 * @internal
 */
class AuctionLotDataMessageFactory extends CustomizableClass
{
    use AuctionBidderPureCheckerCreateTrait;
    use AuctionLotStatusPureCheckerAwareTrait;
    use BuyNowAvailabilityLiveCheckerCreateTrait;
    use BuyNowAvailabilityTimedCheckerCreateTrait;
    use HybridCountdownDataProducerAwareTrait;
    use ReserveNotMetCheckerCreateTrait;
    use SyncLotAccessCheckerAwareTrait;

    /**
     * @var int|null
     */
    protected ?int $editorUserId = null;
    /**
     * @var bool
     */
    protected bool $showWinnerInCatalog = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $editorUserId
     * @param array $auctionIds
     * @param bool $showWinnerInCatalog
     * @param bool $isProfilingEnabled
     * @return static
     */
    public function construct(?int $editorUserId, array $auctionIds, bool $showWinnerInCatalog, bool $isProfilingEnabled): static
    {
        $this->editorUserId = $editorUserId;
        $this->showWinnerInCatalog = $showWinnerInCatalog;
        $this->getSyncLotAccessChecker()->construct($editorUserId, $auctionIds, $isProfilingEnabled);
        $this->getHybridCountdownDataProducer()->constructForPublic();
        return $this;
    }

    /**
     * Build protobuf message object for a timed auction lot
     *
     * @param SyncAuctionLotDto $auctionLotDto
     * @param array $additionalCurrencies
     * @param array $auctionLotChanges
     * @return AuctionLotDataMessage
     */
    public function createForTimedAuction(
        SyncAuctionLotDto $auctionLotDto,
        array $additionalCurrencies,
        array $auctionLotChanges
    ): AuctionLotDataMessage {
        $message = new AuctionLotDataMessage();
        $message = $this->applyCommonData($message, $auctionLotDto, $additionalCurrencies);
        $message = $this->applyTimedSpecificData($message, $auctionLotDto, $auctionLotChanges);
        return $message;
    }

    /**
     * Build protobuf message object for a live auction lot
     *
     * @param SyncAuctionLotDto $auctionLotDto
     * @param array $additionalCurrencies
     * @return AuctionLotDataMessage
     */
    public function createForLiveAuction(
        SyncAuctionLotDto $auctionLotDto,
        array $additionalCurrencies
    ): AuctionLotDataMessage {
        $message = new AuctionLotDataMessage();
        $message = $this->applyCommonData($message, $auctionLotDto, $additionalCurrencies);
        $message = $this->applyLiveOrHybridSpecificData($message, $auctionLotDto);
        return $message;
    }

    /**
     * Build protobuf message object for a hybrid auction lot
     *
     * @param SyncAuctionLotDto $auctionLotDto
     * @param array $additionalCurrencies
     * @param array $orderNums
     * @return AuctionLotDataMessage
     */
    public function createForHybridAuction(
        SyncAuctionLotDto $auctionLotDto,
        array $additionalCurrencies,
        array $orderNums
    ): AuctionLotDataMessage {
        $message = new AuctionLotDataMessage();
        $message = $this->applyCommonData($message, $auctionLotDto, $additionalCurrencies);
        $message = $this->applyLiveOrHybridSpecificData($message, $auctionLotDto);
        $message = $this->applyHybridSpecificData($message, $auctionLotDto, $orderNums);
        return $message;
    }

    /**
     * @param AuctionLotDataMessage $message
     * @param SyncAuctionLotDto $auctionLotDto
     * @param array $auctionLotChanges
     * @return AuctionLotDataMessage
     */
    protected function applyTimedSpecificData(
        AuctionLotDataMessage $message,
        SyncAuctionLotDto $auctionLotDto,
        array $auctionLotChanges
    ): AuctionLotDataMessage {
        if ($this->getAuctionLotStatusPureChecker()->isActive($auctionLotDto->lotStatus)) {
            $message
                ->setNextBidButton($auctionLotDto->isNextBidButton)
                ->setBidCount($auctionLotDto->bidCount);
            $isBuyNowAvailable = $this->isBuyNowAvailableForTimed($this->editorUserId, $auctionLotDto);
            if ($isBuyNowAvailable) {
                $message->setBuyNowAmount($auctionLotDto->buyAmount);
            }

            if (!$auctionLotDto->isNoBidding) {
                if ($auctionLotDto->currentBid !== null) {
                    $isHighBidder = $this->isHighBidder($auctionLotDto->currentBidderId);
                    $message
                        ->setCurrentBid($auctionLotDto->currentBid)
                        ->setIsHighBidder($isHighBidder)
                        ->setAskingBid($auctionLotDto->askingBid);
                } elseif ($auctionLotDto->startingBid !== null) {
                    $message->setStartingBid($auctionLotDto->startingBid);
                }

                if (Floating::gt($auctionLotDto->bulkMasterAskingBid, 0)) {
                    $message->setBulkMasterAskingBid($auctionLotDto->bulkMasterAskingBid);
                }
            }

            $lotChangesTimestamp = $auctionLotDto->lotChangesTimestamp;
            $auctionLotChanges = array_filter(
                $auctionLotChanges,
                static function (int $userChangesTimestamp) use ($lotChangesTimestamp) {
                    return $userChangesTimestamp >= $lotChangesTimestamp;
                }
            );
            $message->setAuctionLotChangesTimestamps($auctionLotChanges);
        } elseif ($this->getAuctionLotStatusPureChecker()->isUnsold($auctionLotDto->lotStatus)) {
            $isHighBidder = $this->isHighBidder($auctionLotDto->currentBidderId);
            $message
                ->setClosed(true)
                ->setIsHighBidder($isHighBidder);
            if ($auctionLotDto->currentBid !== null) {
                $message->setCurrentBid($auctionLotDto->currentBid);
            }
        } elseif ($this->getAuctionLotStatusPureChecker()->isAmongWonStatuses($auctionLotDto->lotStatus)) {
            /*
              Not implemented
             if ($this->systemParameters['hammer_price_bp']) {
                $premium = $this->getBuyersPremium($auctionLot);
                $hammerPrice += $premium;
            }
            */
            $isHighBidder = $this->isHighBidder($auctionLotDto->winnerUserId);
            $message
                ->setClosed(true)
                ->setHammerPrice($auctionLotDto->hammerPrice)
                ->setIsHighBidder($isHighBidder)
                ->setReservePrice((float)$auctionLotDto->reservePrice);
        }

        /**
         * in case listing only lot we don't send current bid and asking bid - https://bidpath.atlassian.net/browse/SAM-4162
         * in case absence of LotBiddingInfoAccess permission we don't send current bid and asking bid - https://bidpath.atlassian.net/browse/SAM-6503
         */
        if (
            $auctionLotDto->isAuctionLotListingOnly
            || !$this->hasBiddingInfoAccess($auctionLotDto)
        ) {
            $message->clearAskingBid();
            $message->clearCurrentBid();
        }

        if (!$this->hasLotWinningBidAccess($auctionLotDto)) {
            $message->clearHammerPrice();
        }
        return $message;
    }

    /**
     * @param AuctionLotDataMessage $message
     * @param SyncAuctionLotDto $auctionLotDto
     * @return AuctionLotDataMessage
     */
    protected function applyLiveOrHybridSpecificData(AuctionLotDataMessage $message, SyncAuctionLotDto $auctionLotDto): AuctionLotDataMessage
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();

        if ($auctionLotDto->bidCount) {
            if ($this->shouldDisplayAbsenteeBidCount($auctionLotDto->absenteeBidsDisplay)) {
                $message->setBidCount($auctionLotDto->bidCount);
            }
            if (
                $auctionStatusPureChecker->isAbsenteeBidsDisplaySetAsNumberOfAbsenteeHigh($auctionLotDto->absenteeBidsDisplay)
                && $auctionLotDto->currentBid
            ) {
                $message->setAbsenteeBid($auctionLotDto->currentBid);
            }
        }

        $isBuyNowAvailable = $this->isBuyNowAvailableForLive($this->editorUserId, $auctionLotDto);
        if ($isBuyNowAvailable) {
            $message->setBuyNowAmount($auctionLotDto->buyAmount);
        }

        if (
            $auctionStatusPureChecker->isClosed($auctionLotDto->auctionStatus)
            || $this->getAuctionLotStatusPureChecker()->isAmongClosedStatuses($auctionLotDto->lotStatus)
        ) {
            $message->setClosed(true);
        } elseif ($this->getAuctionLotStatusPureChecker()->isActive($auctionLotDto->lotStatus)) {
            $isHighBidder = $this->isHighBidder($auctionLotDto->currentBidderId);
            $message
                ->setIsHighBidder($isHighBidder)
                ->setAskingBid($auctionLotDto->askingBid);
            if ($auctionLotDto->startingBid !== null) {
                $message->setStartingBid($auctionLotDto->startingBid);
            }
        }

        if ($this->getAuctionLotStatusPureChecker()->isAmongWonStatuses($auctionLotDto->lotStatus)) {
            $isHighBidder = $this->isHighBidder($auctionLotDto->winnerUserId);
            $message
                ->setHammerPrice($auctionLotDto->hammerPrice)
                ->setIsHighBidder($isHighBidder)
                ->setReservePrice((float)$auctionLotDto->reservePrice);
        }
        return $message;
    }

    /**
     * @param AuctionLotDataMessage $message
     * @param SyncAuctionLotDto $auctionLotDto
     * @param array $orderNums
     * @return AuctionLotDataMessage
     */
    protected function applyHybridSpecificData(
        AuctionLotDataMessage $message,
        SyncAuctionLotDto $auctionLotDto,
        array $orderNums
    ): AuctionLotDataMessage {
        $message->setRtbLotActive($auctionLotDto->isRtbLotActive);
        if ($this->getAuctionLotStatusPureChecker()->isActive($auctionLotDto->lotStatus)) {
            $dto = HybridCountdownInputDto::new()->fromSyncAuctionLotDto($auctionLotDto);
            $hybridData = $this->getHybridCountdownDataProducer()->produce($dto, $orderNums);
            $hybridCountdown = new HybridCountdownMessage();
            if ($hybridData['ts'] !== null) {
                $hybridCountdown->setTimestamp($hybridData['ts']);
            }
            $hybridCountdown
                ->setTimezone($hybridData['astzn'])
                ->setTimezoneCode($hybridData['astzc']);
            $message
                ->setSecondsLeft($hybridData[Constants\LotDisplay::RES_SECONDS_LEFT])
                ->setSecondsBefore($hybridData[Constants\LotDisplay::RES_SECONDS_BEFORE])
                ->setHybridCountdown($hybridCountdown);
        }
        return $message;
    }

    /**
     * @param AuctionLotDataMessage $message
     * @param SyncAuctionLotDto $auctionLotDto
     * @param array $additionalCurrencies
     * @return AuctionLotDataMessage
     */
    protected function applyCommonData(
        AuctionLotDataMessage $message,
        SyncAuctionLotDto $auctionLotDto,
        array $additionalCurrencies
    ): AuctionLotDataMessage {
        $message
            ->setAuctionReverse($auctionLotDto->auctionReverse)
            ->setAuctionId($auctionLotDto->auctionId)
            ->setLotItemId($auctionLotDto->lotItemId)
            ->setAuctionStatus($auctionLotDto->auctionStatus)
            ->setLotStatus($auctionLotDto->lotStatus)
            ->setSecondsBefore($auctionLotDto->secondsBefore < 0 ? -1 : $auctionLotDto->secondsBefore)
            ->setSecondsLeft($auctionLotDto->secondsLeft < 0 ? -1 : $auctionLotDto->secondsLeft)
            ->setLotChanges($auctionLotDto->lotChanges)
            ->setUserMaxBid((float)$auctionLotDto->userMaxBid)
            ->setCurrencySign($auctionLotDto->currencySign)
            ->setQuantity($auctionLotDto->quantity)
            ->setQuantityScale($auctionLotDto->quantityScale)
            ->setQuantityXMoney($auctionLotDto->isQuantityXMoney)
            ->setAuctionLotListingOnly($auctionLotDto->isAuctionLotListingOnly)
            ->setAuctionListingOnly($auctionLotDto->isAuctionListingOnly)
            ->setNotifyAbsenteeBidders($auctionLotDto->notifyAbsenteeBidders)
            ->setRev("cur:{$auctionLotDto->auctionCurrencyId}")
            ->setRel('cur:' . implode(',', $additionalCurrencies))
            ->setAuctionType($auctionLotDto->auctionType);

        $reserveNotMet = $this->detectReserveNotMet($auctionLotDto);
        if ($reserveNotMet !== null) {
            $message->setReserveNotMet($reserveNotMet > 0);
        }

        $wbInfo = $this->isHighBidder($auctionLotDto->winnerUserId)
            ? $auctionLotDto->winningBidderInfo
            : 'floor bidder';
        $wbInfo = $this->showWinnerInCatalog ? $wbInfo : '';
        $message->setWinningBidderInfo($wbInfo);
        return $message;
    }

    /**
     * @param SyncAuctionLotDto $auctionLotDto
     * @return int|null
     */
    protected function detectReserveNotMet(SyncAuctionLotDto $auctionLotDto): ?int
    {
        $reserveNotMet = $this->createReserveNotMetChecker()
            ->construct()
            ->detectReserveNotMet(
                $auctionLotDto->reservePrice,
                $auctionLotDto->currentMaxBid,
                $auctionLotDto->secondsLeft,
                $auctionLotDto->isReserveNotMet,
                $auctionLotDto->isReserveMet,
                $auctionLotDto->auctionReverse
            );
        return $reserveNotMet;
    }

    /**
     * @param int|null $editorUserId
     * @param SyncAuctionLotDto $auctionLotDto
     * @return bool
     */
    protected function isBuyNowAvailableForTimed(?int $editorUserId, SyncAuctionLotDto $auctionLotDto): bool
    {
        $checker = $this->createBuyNowAvailabilityTimedChecker();
        if ($editorUserId) {
            $flag = UserFlagPureDetector::new()->detectEffective($auctionLotDto->userFlag, $auctionLotDto->userAccountFlag);
            $isAuctionApproved = $this->createAuctionBidderPureChecker()->isApproved(true, $flag, $auctionLotDto->bidderNum);
            $checker
                ->enableApprovedBidder($isAuctionApproved)
                ->setUserFlag($flag);
        }
        if ($auctionLotDto->endDate === null) {
            log_error('Lot end date is absent' . composeSuffix(['a' => $auctionLotDto->auctionId, 'li' => $auctionLotDto->lotItemId]));
        }
        if ($auctionLotDto->startDate === null) {
            log_error('Lot start bidding date is absent' . composeSuffix(['a' => $auctionLotDto->auctionId, 'li' => $auctionLotDto->lotItemId]));
        }
        $isBuyNowAvailable = $checker
            // ->enableAllowedForUnsold($buyNowUnsold)
            ->enableAuctionListingOnly($auctionLotDto->isAuctionListingOnly)
            ->enableAuctionLotListingOnly($auctionLotDto->isAuctionLotListingOnly)
            ->enableBiddingPaused($auctionLotDto->isBiddingPaused)
            ->setBuyNowAmount($auctionLotDto->buyAmount)
            ->setCurrentBid($auctionLotDto->currentBid)
            ->setEndDateUtc($auctionLotDto->endDate)
            ->setLotStatus($auctionLotDto->lotStatus)
            ->setStartDateUtc($auctionLotDto->startDate)
            ->isAvailable();
        return $isBuyNowAvailable;
    }

    /**
     * @param int|null $editorUserId
     * @param SyncAuctionLotDto $auctionLotDto
     * @return bool
     */
    protected function isBuyNowAvailableForLive(?int $editorUserId, SyncAuctionLotDto $auctionLotDto): bool
    {
        $checker = $this->createBuyNowAvailabilityLiveChecker();
        if ($editorUserId) {
            $flag = UserFlagPureDetector::new()->detectEffective($auctionLotDto->userFlag, $auctionLotDto->userAccountFlag);
            $isAuctionApproved = $this->createAuctionBidderPureChecker()->isApproved(true, $flag, $auctionLotDto->bidderNum);
            $checker
                ->enableApprovedBidder($isAuctionApproved)
                ->setUserFlag($flag);
        }
        if ($auctionLotDto->startDate === null) {
            log_error('Lot start closing date is absent' . composeSuffix(['a' => $auctionLotDto->auctionId, 'li' => $auctionLotDto->lotItemId]));
        }
        $isBuyNowAvailable = $checker
            ->enableAllowedForUnsold($auctionLotDto->isBuyNowUnsold)
            ->enableAuctionListingOnly($auctionLotDto->isAuctionListingOnly)
            ->enableAuctionLotListingOnly($auctionLotDto->isAuctionLotListingOnly)
            ->enableBiddingPaused($auctionLotDto->isBiddingPaused)
            ->setAuctionStatus($auctionLotDto->auctionStatus)
            ->setBuyNowAmount($auctionLotDto->buyAmount)
            ->setCurrentAbsenteeBid($auctionLotDto->currentBid)
            ->setCurrentTransactionBid($auctionLotDto->currentTransactionBid)
            ->setLotItemId($auctionLotDto->lotItemId)
            ->setLotStatus($auctionLotDto->lotStatus)
            ->setRestriction($auctionLotDto->buyNowRestriction)
            ->setRunningLotItemId($auctionLotDto->rtbCurrentLotItemId)
            ->setStartDateUtc($auctionLotDto->startDate)
            ->isAvailable();
        return $isBuyNowAvailable;
    }

    /**
     * @param SyncAuctionLotDto $auctionLotDto
     * @return bool
     */
    protected function hasBiddingInfoAccess(SyncAuctionLotDto $auctionLotDto): bool
    {
        $hasAccess = $this->getSyncLotAccessChecker()->hasAccess(
            $auctionLotDto->lotBiddingInfoAccess,
            $auctionLotDto->auctionId,
            $auctionLotDto->consignorUserId
        );
        return $hasAccess;
    }

    /**
     * @param SyncAuctionLotDto $auctionLotDto
     * @return bool
     */
    protected function hasLotWinningBidAccess(SyncAuctionLotDto $auctionLotDto): bool
    {
        $hasAccess = $this->getSyncLotAccessChecker()->hasAccess(
            $auctionLotDto->lotWinningBidAccess,
            $auctionLotDto->auctionId,
            $auctionLotDto->consignorUserId
        );
        return $hasAccess;
    }

    /**
     * @param int|null $highBidderUserId
     * @return bool
     */
    protected function isHighBidder(?int $highBidderUserId): bool
    {
        return $this->editorUserId && $this->editorUserId === $highBidderUserId;
    }

    /**
     * @param string $absenteeBidDisplay
     * @return bool
     */
    protected function shouldDisplayAbsenteeBidCount(string $absenteeBidDisplay): bool
    {
        return in_array(
            $absenteeBidDisplay,
            [
                Constants\SettingAuction::ABD_NUMBER_OF_ABSENTEE,
                Constants\SettingAuction::ABD_NUMBER_OF_ABSENTEE_LINK,
                Constants\SettingAuction::ABD_NUMBER_OF_ABSENTEE_HIGH
            ],
            true
        );
    }
}
