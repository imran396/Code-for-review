<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Boanerge Regidor
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/15/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\BidMaster;

use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Load\AuctionLotCacheLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Report\Base\ReportRendererAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class AuctionBidMasterReporter
 * @package Sam\Report\Auction\BidMaster
 */
class AuctionBidMasterReporter extends ReporterBase
{
    use AccountLoaderAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionLotCacheLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use BidderNumPaddingAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use DbConnectionTrait;
    use FilePathHelperAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use ReportRendererAwareTrait;
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
            $filename = "bm_auction_result_{$dateIso}_{$saleNo}.tab";
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
        $auctionLotGenerator = $this->getAuctionLotLoader()
            ->orderByLotNum(true)
            ->yieldByAuctionId($this->getFilterAuctionId());
        foreach ($auctionLotGenerator as $auctionLot) {
            $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
            if (!$lotItem) {
                log_error(
                    "Available lot item not found for auction bid master report"
                    . composeSuffix(['li' => $auctionLot->LotItemId])
                );
                continue;
            }
            $auctionLotCache = $this->getAuctionLotCacheLoader()->loadById($auctionLot->Id, true);
            $currentBidderId = $secondBidderId = $viewCount = $bidCount = null;
            if ($auctionLotCache) {
                $currentBidderId = $auctionLotCache->CurrentBidderId;
                $secondBidderId = $auctionLotCache->SecondBidderId;
                $viewCount = $auctionLotCache->ViewCount;
                $bidCount = $auctionLotCache->BidCount;
            }
            $lotNumFull = $this->getLotRenderer()->renderLotNo($auctionLot);

            $lotDesc = preg_replace('/\R|\t/u', ' ', $lotItem->Description);
            $lotDesc = strip_tags($lotDesc);
            $lotLowEst = $lotItem->LowEstimate;
            $lotHighEst = $lotItem->HighEstimate;
            $lotReserve = $lotItem->ReservePrice;
            $increment = '0';
            $lotHammer = $lotItem->HammerPrice;
            $highBidderPaddle = '';
            $secondBidderPaddle = '';
            $buyer = 'null';

            $highBidderId = $lotItem->WinningBidderId ?: $currentBidderId;
            $auctionBidderLoader = $this->getAuctionBidderLoader();
            if (
                $highBidderId
                && $lotItem->isSaleSoldAuctionLinkedWith($this->getFilterAuctionId())
            ) {
                $highBidder = $auctionBidderLoader->load($highBidderId, $lotItem->AuctionId, true);
                $highBidderPaddle = $highBidder ? $this->getBidderNumberPadding()->clear($highBidder->BidderNum) : '';
            }
            if ($secondBidderId) {
                $secondBidder = $auctionBidderLoader->load($secondBidderId, $lotItem->AuctionId, true);
                $secondBidderPaddle = $secondBidder
                    ? $this->getBidderNumberPadding()->clear($secondBidder->BidderNum)
                    : '';
            }
            if ($auctionLot->isUnsold()) {
                $highBidderPaddle = 'NS';
                $secondBidderPaddle = '';
                $lotHammer = '';
            }

            $bodyRow = [
                $lotNumFull,
                $lotDesc,
                $lotLowEst,
                $lotHighEst,
                $lotReserve,
                $increment,
                $lotHammer,
                $highBidderPaddle,
                $highBidderPaddle,
                $buyer,
                $secondBidderPaddle,
                $viewCount,
                $bidCount,
            ];

            $bodyLine = $this->makeLine($bodyRow);
            $output .= $this->processOutput($bodyLine);
        }
        return $output;
    }

    /**
     * Convert array of csv values to escaped list of values as string
     * @param array $row
     * @return string
     */
    protected function rowToLine(array $row): string
    {
        $output = implode("\t", $row) . "\n";
        return $output;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = "LOT\t" .
            "DESCRIPTION\t" .
            "LOW EST\t" .
            "HIGH EST\t" .
            "RESERVE\t" .
            "INCREMENT\t" .
            "HAMMER\t" .
            "PADDLE\t" .
            "BUYER ID\t" .
            "BUYER\t" .
            "UNDER BIDDER ID\t" .
            "WEB HITS\t" .
            "BIDS\n";
        return $this->processOutput($headerTitles);
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
}
