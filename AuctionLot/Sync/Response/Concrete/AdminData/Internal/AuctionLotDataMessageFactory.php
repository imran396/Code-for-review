<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal;

use DateTime;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\AuctionLot\Sync\Internal\ReserveNotMetCheckerCreateTrait;
use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Dto\SyncAdminAuctionLotDto;
use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Generated\Message\AuctionLotData;
use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Generated\Message\HighBidder;
use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Generated\Message\Winner;
use Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\Dto\HybridCountdownInputDto;
use Sam\AuctionLot\Sync\Response\Concrete\HybridCountdown\HybridCountdownDataProducerAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidder\Render\BidderRendererCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureCheckerAwareTrait;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;

/**
 * Contains methods for creating AuctionLotData protobuf message object for all auction types
 *
 * Class AuctionLotDataMessageFactory
 * @package Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal
 * @internal
 */
class AuctionLotDataMessageFactory extends CustomizableClass
{
    use AuctionLotStatusPureCheckerAwareTrait;
    use BidderNumPaddingAwareTrait;
    use BidderRendererCreateTrait;
    use DateHelperAwareTrait;
    use DateTimeFormatterAwareTrait;
    use HybridCountdownDataProducerAwareTrait;
    use ReserveNotMetCheckerCreateTrait;
    use StaggerClosingHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initInstance(): static
    {
        $this->getHybridCountdownDataProducer()->constructForAdmin();
        return $this;
    }

    /**
     * Build protobuf message object for a timed auction lot
     *
     * @param SyncAdminAuctionLotDto $auctionLotDto
     * @return AuctionLotData
     */
    public function createForTimedAuction(SyncAdminAuctionLotDto $auctionLotDto): AuctionLotData
    {
        $message = new AuctionLotData();
        $message = $this->applyCommonData($message, $auctionLotDto);
        return $message;
    }

    /**
     * Build protobuf message object for a live auction lot
     *
     * @param SyncAdminAuctionLotDto $auctionLotDto
     * @return AuctionLotData
     */
    public function createForLiveAuction(SyncAdminAuctionLotDto $auctionLotDto): AuctionLotData
    {
        $message = new AuctionLotData();
        $message = $this->applyCommonData($message, $auctionLotDto);
        return $message;
    }

    /**
     * Build protobuf message object for a hybrid auction lot
     *
     * @param SyncAdminAuctionLotDto $auctionLotDto
     * @param int|null $rtbCurrentLotOrderNum
     * @return AuctionLotData
     */
    public function createForHybridAuction(SyncAdminAuctionLotDto $auctionLotDto, ?int $rtbCurrentLotOrderNum): AuctionLotData
    {
        $message = new AuctionLotData();
        $message = $this->applyCommonData($message, $auctionLotDto);
        $message = $this->applyHybridSpecificData($message, $auctionLotDto, $rtbCurrentLotOrderNum);
        return $message;
    }

    /**
     * @param AuctionLotData $message
     * @param SyncAdminAuctionLotDto $auctionLotDto
     * @return AuctionLotData
     */
    protected function applyCommonData(AuctionLotData $message, SyncAdminAuctionLotDto $auctionLotDto): AuctionLotData
    {
        $message
            ->setLotStatus($auctionLotDto->lotStatusId)
            ->setEndDate($this->detectEndDate($auctionLotDto))
            ->setLotViewCount($auctionLotDto->lotViewCount)
            ->setAskingBid($auctionLotDto->askingBid)
            ->setBidCount($auctionLotDto->bidCount)
            ->setAuctionStatus($auctionLotDto->auctionStatusId)
            ->setAuctionType($auctionLotDto->auctionType)
            ->setSecondsLeft($auctionLotDto->secondsLeft < 0 ? -1 : $auctionLotDto->secondsLeft)
            ->setSecondsBefore($auctionLotDto->secondsBefore < 0 ? -1 : $auctionLotDto->secondsBefore);

        if ($auctionLotDto->reservePrice !== null) {
            $message->setReservePrice($auctionLotDto->reservePrice);
        }

        if ($auctionLotDto->currentMaxBid) {
            $message->setCurrentMaxBid($auctionLotDto->currentMaxBid);
        }

        if ($auctionLotDto->currentBid !== null) {
            $message->setCurrentBid($auctionLotDto->currentBid);
        }

        $highBidder = $this->constructHighBidder($auctionLotDto);
        if ($highBidder !== null) {
            $message->setHighBidder($highBidder);
        }

        if (
            LotSellInfoPureChecker::new()->isHammerPrice($auctionLotDto->hammerPrice)
            && $this->getAuctionLotStatusPureChecker()->isAmongWonStatuses($auctionLotDto->lotStatusId)
        ) {
            $message->setHammerPrice($auctionLotDto->hammerPrice);
        }

        $reserveNotMet = $this->detectReserveNotMet($auctionLotDto);
        if ($reserveNotMet !== null) {
            $message->setReserveNotMet($reserveNotMet);
        }

        if ($auctionLotDto->currentBidDateIso) {
            $lastBidPlacedDate = $this->getDateTimeFormatter()->format($auctionLotDto->currentBidDateIso, $auctionLotDto->lotTimezoneLocation);
            $message->setLastBidDate($lastBidPlacedDate);
        }

        $winner = $this->constructWinner($auctionLotDto);
        if ($winner !== null) {
            $message->setWinner($winner);
        }
        return $message;
    }

    /**
     * @param AuctionLotData $message
     * @param SyncAdminAuctionLotDto $auctionLotDto
     * @param int|null $rtbCurrentLotOrderNum
     * @return AuctionLotData
     */
    protected function applyHybridSpecificData(
        AuctionLotData $message,
        SyncAdminAuctionLotDto $auctionLotDto,
        ?int $rtbCurrentLotOrderNum
    ): AuctionLotData {
        if ($this->getAuctionLotStatusPureChecker()->isActive($auctionLotDto->lotStatusId)) {
            $dto = HybridCountdownInputDto::new()->fromSyncAdminAuctionLotDto($auctionLotDto);
            $orderNums = [];
            if ($rtbCurrentLotOrderNum !== null) {
                $orderNums = [
                    $auctionLotDto->rtbCurrentLotItemId => [
                        'order_num' => $rtbCurrentLotOrderNum
                    ]
                ];
            }
            $hybridData = $this->getHybridCountdownDataProducer()->produce($dto, $orderNums);
            $message
                ->setSecondsLeft($hybridData[Constants\LotDisplay::RES_SECONDS_LEFT])
                ->setSecondsBefore($hybridData[Constants\LotDisplay::RES_SECONDS_BEFORE]);
        }
        return $message;
    }

    /**
     * @param SyncAdminAuctionLotDto $auctionLotDto
     * @return HighBidder|null
     */
    protected function constructHighBidder(SyncAdminAuctionLotDto $auctionLotDto): ?HighBidder
    {
        $highBidder = null;
        if ($auctionLotDto->highBidderUserId) {
            $highBidder = (new HighBidder())
                ->setCompany($auctionLotDto->highBidderCompany)
                ->setEmail($auctionLotDto->highBidderEmail)
                ->setHouse($auctionLotDto->highBidderHouse)
                ->setUsername($auctionLotDto->highBidderUsername)
                ->setFirstName($auctionLotDto->highBidderFirstName)
                ->setLastName($auctionLotDto->highBidderLastName)
                ->setUserId($auctionLotDto->highBidderUserId);
        }
        return $highBidder;
    }

    /**
     * @param SyncAdminAuctionLotDto $auctionLotDto
     * @return Winner|null
     */
    protected function constructWinner(SyncAdminAuctionLotDto $auctionLotDto): ?Winner
    {
        $winner = null;
        if ($auctionLotDto->winningBidderId) {
            $bidderNum = '';
            if ($auctionLotDto->auctionId === $auctionLotDto->winningAuctionId) {
                $bidderNum = $auctionLotDto->winnerBidderNum;
            }
            $winnerInfo = $this->createBidderRenderer()->makeShortWinningBidderInfo(
                $bidderNum,
                $auctionLotDto->winnerCompany,
                $auctionLotDto->winnerUsername
            );
            $winner = (new Winner())
                ->setCompany($auctionLotDto->winnerCompany)
                ->setEmail($auctionLotDto->winnerEmail)
                ->setInfo($winnerInfo);
        }
        return $winner;
    }

    /**
     * @param SyncAdminAuctionLotDto $auctionLotDto
     * @return string
     */
    protected function detectEndDate(SyncAdminAuctionLotDto $auctionLotDto): string
    {
        if ($auctionLotDto->isExtendAll) {
            $auctionStartClosingDate = new DateTime($auctionLotDto->auctionStartClosingDateIso);
            if ($auctionLotDto->staggerClosing) {
                $lotEndDate = $this->createStaggerClosingHelper()->calcEndDate(
                    $auctionStartClosingDate,
                    $auctionLotDto->lotsPerInterval,
                    $auctionLotDto->staggerClosing,
                    $auctionLotDto->orderNum
                );
            } else {
                $lotEndDate = $auctionStartClosingDate;
            }
            $endDate = $this->getDateTimeFormatter()->format($lotEndDate, $auctionLotDto->auctionTimezoneLocation);
        } else {
            $endDate = $this->getDateTimeFormatter()->format($auctionLotDto->lotEndDateIso, $auctionLotDto->lotTimezoneLocation);
        }
        return $endDate;
    }

    /**
     * @param SyncAdminAuctionLotDto $auctionLotDto
     * @return int|null
     */
    protected function detectReserveNotMet(SyncAdminAuctionLotDto $auctionLotDto): ?int
    {
        $reserveNotMet = $this->createReserveNotMetChecker()
            ->construct()
            ->detectReserveNotMet(
                $auctionLotDto->reservePrice,
                $auctionLotDto->currentMaxBid,
                $auctionLotDto->secondsLeft,
                $auctionLotDto->isReserveNotMet,
                $auctionLotDto->isReserveMet,
                $auctionLotDto->isAuctionReverse
            );
        return $reserveNotMet;
    }
}
