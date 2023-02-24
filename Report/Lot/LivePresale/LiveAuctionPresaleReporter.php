<?php
/**
 * SAM-4646: Report "Presale bids CSV" report
 * https://bidpath.atlassian.net/browse/SAM-4646
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 18, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format(s)
 */

namespace Sam\Report\Lot\LivePresale;

use AuctionLotItem;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidExistenceCheckerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Renderer\LotCategoryRendererAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class LiveAuctionPresaleReporter
 */
class LiveAuctionPresaleReporter extends ReporterBase
{
    use AbsenteeBidExistenceCheckerAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use BidderNumPaddingAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use FilePathHelperAwareTrait;
    use FilterAuctionAwareTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use LotCategoryRendererAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use TimezoneLoaderAwareTrait;

    /** @var DataLoader|null */
    protected ?DataLoader $dataLoader = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param DataLoader $dataLoader
     * @return static
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->filterAuctionId($this->getFilterAuctionId());
        }
        return $this->dataLoader;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $auction = $this->getFilterAuction();
            $header = 'auction-presales';
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y');
            $saleNo = $auction->SaleNum;
            $filename = "{$header}-{$dateIso}-{$saleNo}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->setOutputFileName($filename);
        }
        return $this->outputFileName;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $auctionLots = $this->getDataLoader()->load();
        if (!count($auctionLots)) {
            echo "No lots found!";
            return '';
        }

        $output = '';
        foreach ($auctionLots as $auctionLot) { //cycle through auction lots
            $isFound = $this->getAbsenteeBidExistenceChecker()
                ->existForLot($auctionLot->LotItemId, $auctionLot->AuctionId, true);
            if ($isFound) {
                $bodyLine = $this->buildBodyLine($auctionLot);
                $output .= $this->processOutput($bodyLine);
            }
        }
        return $output;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return string
     */
    protected function buildBodyLine(AuctionLotItem $auctionLot): string
    {
        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
        if (!$lotItem) {
            log_error(
                "Available lot item not found for live auction presale report"
                . composeSuffix(['li' => $auctionLot->LotItemId])
            );
            return '';
        }

        $highAbsAmount = '';
        $highAbsDate = '';
        $highAbsPadNo = '';
        $highAbsName = '';
        $highAbsEmail = '';
        $secondAbsAmt = '';
        $secondAbsDate = '';
        $secondAbsPadNo = '';
        $secondAbsName = '';
        $secondAbsEmail = '';
        [$highAbsentee, $secondAbsentee] = $this->createHighAbsenteeBidDetector()
            ->detectTwoHighest($auctionLot->LotItemId, $auctionLot->AuctionId);

        $auctionBidderLoader = $this->getAuctionBidderLoader();
        // First High Absentee info
        if ($highAbsentee) {
            $highAbsAmount = $highAbsentee->MaxBid;
            $highAbsAmount = $this->getNumberFormatter()->formatMoneyNto($highAbsAmount);
            $highAbsenteeDate = $this->getDateHelper()->convertUtcToSys($highAbsentee->PlacedOn);
            $highAbsDate = $highAbsenteeDate ? $highAbsenteeDate->format(Constants\Date::US_DATE_TIME) : '';
            $highBidder = $auctionBidderLoader->load($highAbsentee->UserId, $auctionLot->AuctionId, true);
            if ($highBidder) {
                $highAbsPadNo = $this->getBidderNumberPadding()->clear($highBidder->BidderNum);
            }
            $user = $this->getUserLoader()->load($highAbsentee->UserId, true);
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($highAbsentee->UserId, true);
            $highAbsEmail = $user->Email ?? '';
            $highAbsName = UserPureRenderer::new()->renderFullName($userInfo);
        }

        // Second High Absentee info
        if ($secondAbsentee) {
            $secondBidder = $auctionBidderLoader->load($secondAbsentee->UserId, $auctionLot->AuctionId, true);
            $secondAbsAmt = $secondAbsentee->MaxBid;
            $secondAbsAmt = $this->getNumberFormatter()->formatMoneyNto($secondAbsAmt);
            $secondAbsenteeDate = $this->getDateHelper()->convertUtcToSys($secondAbsentee->PlacedOn);
            $secondAbsDate = $secondAbsenteeDate ? $secondAbsenteeDate->format(Constants\Date::US_DATE_TIME) : '';
            if ($secondBidder) {
                $secondAbsPadNo = $this->getBidderNumberPadding()->clear($secondBidder->BidderNum);
            }
            $user = $this->getUserLoader()->load($secondAbsentee->UserId);
            $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($secondAbsentee->UserId, true);
            $secondAbsEmail = $user->Email ?? '';
            $secondAbsName = UserPureRenderer::new()->renderFullName($userInfo);
        }

        $bodyRow = [];
        if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
            $bodyRow[] = $this->getLotRenderer()->makeItemNo($lotItem->ItemNum, $lotItem->ItemNumExt);
        } else {
            $bodyRow = array_merge($bodyRow, [$lotItem->ItemNum, $lotItem->ItemNumExt]);
        }

        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $bodyRow[] = $this->getLotRenderer()
                ->makeLotNo($auctionLot->LotNum, $auctionLot->LotNumExt, $auctionLot->LotNumPrefix);
        } else {
            $bodyRow = array_merge($bodyRow, [$auctionLot->LotNumPrefix, $auctionLot->LotNum, $auctionLot->LotNumExt]);
        }
        $lotName = $this->getLotRenderer()->makeName($lotItem->Name, $this->getFilterAuction()->TestAuction);
        $cost = $this->getNumberFormatter()->formatMoneyNto($lotItem->Cost);
        $categoriesText = $this->getLotCategoryRenderer()->getCategoriesText($lotItem->Id);

        $bodyRow = array_merge(
            $bodyRow,
            [
                $lotName,
                $categoriesText,
                $secondAbsAmt,
                $secondAbsDate,
                $secondAbsPadNo,
                $secondAbsName,
                $secondAbsEmail,
                $highAbsAmount,
                $cost,
                $highAbsDate,
                $highAbsPadNo,
                $highAbsName,
                $highAbsEmail,
            ]
        );

        $bodyLine = $this->makeLine($bodyRow);

        return $bodyLine;
    }

    /**
     * @return string
     */
    protected function outputTitles(): string
    {
        $auction = $this->getFilterAuction();
        $timezone = $this->getTimezoneLoader()->load($auction->TimezoneId, true);
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($auction);
        $dateFormatted = $this->getDateHelper()->formatUtcDate(
            $auction->StartClosingDate,
            null,
            $timezone->Location ?? null,
            null,
            Constants\Date::US_DATE_TIME
        );
        $headerTopLine = $this->makeLine(['Sale no. ' . $saleNo, 'Auction Date ' . $dateFormatted]);

        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $lotNoHeader = ["LotFull#"];
        } else {
            $lotNoHeader = ["Lot# Prefix", "Lot#", "Lot# Ext."];
        }
        if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
            $itemNoHeader = ["ItemFull#"];
        } else {
            $itemNoHeader = ["Item#", "Item# Ext."];
        }

        $headerTitles = array_merge(
            $itemNoHeader,
            $lotNoHeader,
            [
                "Name",
                "Category",
                "2nd Highest[" . $this->getCurrencyLoader()->detectDefaultSign($auction->Id) . "]",
                "Date",
                "Paddle #",
                "Name",
                "Email",
                "Highest[" . $this->getCurrencyLoader()->detectDefaultSign($auction->Id) . "]",
                "Cost",
                "Date",
                "Paddle #",
                "Name",
                "Email",
            ]
        ); // Header
        $headerLine = $headerTopLine . $this->makeLine($headerTitles);
        return $this->processOutput($headerLine);
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $this->errorMessage = null;
        $auction = $this->getFilterAuction();
        if ($auction === null) {
            // Unknown auction situation already processed at controller layer in allow() method
            $this->errorMessage = "Auction not found" . composeSuffix(['a' => $this->getFilterAuctionId()]);
        } elseif ($auction->isDeleted()) {
            $this->errorMessage = "Auction already deleted" . composeSuffix(['a' => $this->getFilterAuctionId()]);
        }
        $success = $this->errorMessage === null;
        return $success;
    }

    /**
     * Echo output, return empty string
     * @return string
     */
    protected function outputError(): string
    {
        echo $this->errorMessage;
        return '';
    }
}
