<?php
/**
 * SAM-4645: Refactor "Live Auction Result" report
 * https://bidpath.atlassian.net/browse/SAM-4645
 *
 * @author        Vahagn Hovsepyan
 * @since         Mar 07, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Lot\LiveAuction;

use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\BuyersPremium\Calculate\BuyersPremiumCalculatorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class LiveAuctionResultReporter
 */
class LiveAuctionResultReporter extends ReporterBase
{
    use AuctionBidderLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use BidderNumPaddingAwareTrait;
    use BuyersPremiumCalculatorAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use FilePathHelperAwareTrait;
    use FilterAuctionAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use TimezoneLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $saleNo = $this->getAuctionRenderer()->renderSaleNo($this->getFilterAuction());
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y');
            $filename = "auction_results_{$dateIso}_{$saleNo}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->outputFileName = $filename;
        }
        return $this->outputFileName;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        $filterAuctionId = $this->getFilterAuctionId();
        $auctionLotGenerator = $this->getAuctionLotLoader()
            ->orderByLotNum(true)
            ->yieldByAuctionId($filterAuctionId);
        foreach ($auctionLotGenerator as $auctionLot) {
            $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
            if (!$lotItem) {
                log_error(
                    "Available lot item not found for auction csv export"
                    . composeSuffix(['li' => $auctionLot->LotItemId])
                );
                continue;
            }
            $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);

            $lotStatusName = '';
            if ($auctionLotStatusPureChecker->isAmongWonStatuses($auctionLot->LotStatusId)) {
                $lotStatusName = Constants\Lot::$lotStatusNames[Constants\Lot::LS_SOLD];
            } elseif ($auctionLotStatusPureChecker->isUnsold($auctionLot->LotStatusId)) {
                $lotStatusName = Constants\Lot::$lotStatusNames[Constants\Lot::LS_UNSOLD];
            } elseif ($auctionLotStatusPureChecker->isActive($auctionLot->LotStatusId)) {
                $lotStatusName = Constants\Lot::$lotStatusNames[Constants\Lot::LS_ACTIVE];
            }

            $bidderNo = '';
            $buyerPremium = 0;
            $total = 0;

            $username = '';
            $email = '';
            $name = '';

            if (
                $auctionLot->isAmongWonStatuses()
                && $lotItem->isSaleSoldAuctionLinkedWith($filterAuctionId)
            ) { // Sold should have the auction id

                $hammerPrice = $lotItem->HammerPrice;
                $buyerPremium = $this->getBuyersPremiumCalculator()
                    ->calculate($lotItem->Id, $filterAuctionId, $lotItem->WinningBidderId, $lotItem->AccountId, $hammerPrice);
                $total = $hammerPrice + $buyerPremium;

                if ($lotItem->hasWinningBidder()) {
                    $userLoader = $this->getUserLoader();
                    $winningUser = $userLoader->load($lotItem->WinningBidderId);
                    if (!$winningUser) {
                        log_error(
                            "Available winning bidder user not found"
                            . composeSuffix(['u' => $lotItem->WinningBidderId, 'li' => $lotItem->Id])
                        );
                    } else {
                        $auctionBidder = $this->getAuctionBidderLoader()->load(
                            $winningUser->Id,
                            $filterAuctionId,
                            true
                        );
                        $bidderNo = $auctionBidder
                            ? $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum) : '';
                        $username = $winningUser->Username;
                        $email = $winningUser->Email;
                        $userInfo = $userLoader->loadUserInfoOrCreate($winningUser->Id, true);
                        $name = UserPureRenderer::new()->renderFullName($userInfo);
                    }
                } else {
                    $bidderNo = 'floor bidder';
                }
            } else {
                $currentBid = $this->createBidTransactionLoader()
                    ->loadLastActiveBid($lotItem->Id, $filterAuctionId);
                $hammerPrice = $currentBid->Bid ?? null;
            }

            $priceText = $this->getCurrencyLoader()->detectDefaultSign()
                . $this->getNumberFormatter()->formatMoneyNto($hammerPrice);
            $buyerPremiumText = $this->getCurrencyLoader()->detectDefaultSign()
                . $this->getNumberFormatter()->formatMoneyNto($buyerPremium);
            $totalText = $this->getCurrencyLoader()->detectDefaultSign()
                . $this->getNumberFormatter()->formatMoneyNto($total);

            /*-------------------------------------
             * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
             * This might include adding, changing, or moving columns,
             * modifying header names,
             * modifying data or data format(s)
             *-------------------------------------*/

            $bodyRow = [
                $lotNo,
                $lotStatusName,
                $bidderNo,
                $priceText,
                $buyerPremiumText,
                $totalText,
                $username,
                $email,
                $name
            ];

            $bodyLine = $this->makeLine($bodyRow);
            $output .= $this->processOutput($bodyLine);
        }
        return $output;
    }

    /**
     * @return string
     */
    protected function outputTitles(): string
    {
        $saleDate = '';
        $saleNo = '';
        $auction = $this->getFilterAuction();
        if ($auction) {
            $auctionTimezone = $this->getTimezoneLoader()->load($this->getFilterAuction()->TimezoneId, true);
            $auctionTzLocation = $auctionTimezone->Location ?? null;
            $dateHelper = $this->getDateHelper();

            $saleNo = $this->getAuctionRenderer()->renderSaleNo($auction);

            $saleDate = $dateHelper->formatUtcDate(
                $auction->StartClosingDate,
                null,
                $auctionTzLocation,
                null,
                Constants\Date::US_DATE_TIME
            );

            if ($auction->isTimed()) {
                $saleDate .= ' - ' . $dateHelper->formatUtcDate(
                        $auction->EndDate,
                        null,
                        $auctionTzLocation,
                        null,
                        Constants\Date::US_DATE_TIME
                    );
            }
        }

        /*-------------------------------------
         * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
         * This might include adding, changing, or moving columns,
         * modifying header names,
         * modifying data or data format(s)
         *-------------------------------------*/
        $titlesAuctionInfo = [
            "Sale no. $saleNo",
            "Auction Date $saleDate",
        ]; // Auction
        $titlesHeader = [
            "Lot #",
            "Status",
            "Winning Bidder",
            "Hammer Price",
            "Buyer\'s Premium",
            "Total",
            "Username",
            "Email",
            "Name"
        ]; // Header

        $headerLine = $this->makeLine($titlesAuctionInfo) . "\n" . $this->makeLine($titlesHeader);

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
        } elseif ($auction->isActive() || $auction->isPaused()) {
            $this->errorMessage = "Auction has not started yet. You can download auction result once the auction is closed.";
        } elseif ($auction->isStarted()) {
            $this->errorMessage = "Auction is in progress. You can download auction result once the auction is closed.";
        } elseif ($auction->isDeleted()) {
            $this->errorMessage = "Auction already deleted" . composeSuffix(['a' => $this->getFilterAuctionId()]);
        }
        $success = $this->errorMessage === null;
        return $success;
    }
}
