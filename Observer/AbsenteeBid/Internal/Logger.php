<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\AbsenteeBid\Internal;

use AbsenteeBid;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Report\Base\Csv\ReportToolAwareTrait;

/**
 * Class Logger
 * @package Sam\Observer\AbsenteeBid
 * @internal
 */
class Logger extends CustomizableClass implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AuctionBidderLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use FileManagerCreateTrait;
    use LotRendererAwareTrait;
    use ReportToolAwareTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function onCreate(EntityObserverSubject $subject): void
    {
        /** @var AbsenteeBid $absenteeBid */
        $absenteeBid = $subject->getEntity();
        $messagePatterns = 'Absentee bid of %1$s placed on lot %2$s for user %3$s';
        $message = $this->makeMessage($messagePatterns, $subject);
        $this->log($absenteeBid, $message);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var AbsenteeBid $absenteeBid */
        $absenteeBid = $subject->getEntity();
        $messagePatterns = 'Absentee bid modified from %4$s to %1$s on lot %2$s for user %3$s';
        $message = $this->makeMessage($messagePatterns, $subject);
        $this->log($absenteeBid, $message);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isPropertyModified('MaxBid')
            || $subject->isPropertyModified('OrId');
    }

    /**
     * @param string $messagePattern
     * @param EntityObserverSubject $subject
     * @return string
     */
    protected function makeMessage(string $messagePattern, EntityObserverSubject $subject): string
    {
        /** @var AbsenteeBid $absenteeBid */
        $absenteeBid = $subject->getEntity();
        $maxBidOld = $subject->getOldPropertyValue('MaxBid');
        $auctionLot = $this->getAuctionLotLoader()->load($absenteeBid->LotItemId, $absenteeBid->AuctionId);
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $logInfo = ['li' => $absenteeBid->LotItemId, 'a' => $absenteeBid->AuctionId, 'lot#' => $lotNo];
        $logInfoMessage = composeLogData($logInfo);
        $message = sprintf($messagePattern, $absenteeBid->MaxBid, $logInfoMessage, $absenteeBid->UserId, $maxBidOld);
        $auctionBidder = $this->getAuctionBidderLoader()->load($absenteeBid->UserId, $absenteeBid->AuctionId, true);
        $bidderNum = $auctionBidder ? $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum) : false;
        if ($bidderNum) {
            $message .= ' (bidder num: ' . $bidderNum . ')';
        }
        if ($subject->isPropertyModified('OrId')) {
            $message .= ' (or-id: ' . $absenteeBid->OrId .
                ($subject->getOldPropertyValue('OrId') !== $absenteeBid->OrId ? '; was: ' . $absenteeBid->__Modified['OrId'] : '') . ')';
        }
        return $message;
    }

    /**
     * @param AbsenteeBid $absenteeBid
     * @param $message
     */
    protected function log(AbsenteeBid $absenteeBid, string $message): void
    {
        $remoteAddr = $this->getServerRequestReader()->remoteAddr() ?: 'n/a';
        $remotePort = $this->getServerRequestReader()->remotePort() ?: 'n/a';
        $userId = $absenteeBid->ModifiedBy ?: $absenteeBid->CreatedBy;

        $csvRow = [
            date(Constants\Date::ISO),
            "UTC",
            $remoteAddr,
            $remotePort,
            $userId,
            $message
        ];
        $csvRow = $this->getReportTool()->prepareValues($csvRow, 'UTF-8');
        $csvLine = $this->getReportTool()->rowToLine($csvRow);
        $filePath = substr(path()->docRoot(), strlen(path()->sysRoot())) . '/lot-info/rtb-' . $absenteeBid->AuctionId . '.log';
        $fileManager = $this->createFileManager();
        $fileManager->append($csvLine, $filePath);
    }
}
