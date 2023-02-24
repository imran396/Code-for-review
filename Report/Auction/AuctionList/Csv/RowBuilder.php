<?php
/**
 * SAM-4627: Refactor auction list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-04-26
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\AuctionList\Csv;

use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;
use Sam\Locale\Formatter\LocaleNumberFormatterAwareTrait;
use Sam\Report\Base\Csv\ReportToolAwareTrait;
use Sam\Report\Base\Csv\RowBuilderBase;

/**
 * Class Renderer
 */
class RowBuilder extends RowBuilderBase
{
    use AuctionRendererAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DateTimeFormatterAwareTrait;
    use LocaleNumberFormatterAwareTrait;
    use ReportToolAwareTrait;

    protected bool $isAccountFiltering = false;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function enableAccountFiltering(bool $enable): static
    {
        $this->isAccountFiltering = $enable;
        return $this;
    }

    /**
     * Build Header Titles
     * @return string
     */
    public function buildHeaderLine(): string
    {
        return $this->getReportTool()->rowToLine($this->getFields());
    }

    /**
     * @param array $row
     * @return array
     */
    public function buildBodyRow(array $row): array
    {
        $eventType = (int)$row['event_type'];
        $auctionType = $row['auction_type'];
        $accountName = $row['account_name'];
        $auctionName = $row['auction_name'];
        $description = $row['description'];
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimedOngoing($auctionType, $eventType)) {
            $startDateIso = 'N/A';
            $startClosingDateIso = 'N/A';
        } else {
            $startDateIso = $this->getDateTimeFormatter()->format($row['start_date'], $row['timezone_location']);
            $startClosingDateIso = $this->getDateTimeFormatter()->format($row['start_closing_date'], $row['timezone_location']);
        }

        if ($auctionStatusPureChecker->isTimedScheduled($auctionType, $eventType)) {
            $endDateIso = $this->getDateTimeFormatter()->format($row['end_date'], $row['timezone_location']);
        } else {
            $endDateIso = 'N/A';
        }
        $currencyCode = $row['currency_code'] ?? $this->getCurrencyLoader()->findPrimaryCurrencyCode();
        $auctionTypeName = AuctionPureRenderer::new()->makeAuctionType($auctionType);
        $numberFormatter = $this->getLocaleNumberFormatter();
        $saleNo = $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);
        $pageViewCount = $numberFormatter->formatDecimal((int)$row['page_views']);
        $id = $numberFormatter->formatDecimal($row['id']);
        $categoryCount = $numberFormatter->formatDecimal((int)$row['categories']);
        $lotCount = $numberFormatter->formatDecimal((int)$row['lots']);
        $lotWithBidsCount = $numberFormatter->formatDecimal((int)$row['lots_with_bids']);
        $currentBidTotal = $this->amountRender($row, 'current_bid_total', $currencyCode);
        $currentBidTotalAboveReserve = $this->amountRender($row, 'current_bid_total_above_reserve', $currencyCode);
        $maxBidTotal = $this->amountRender($row, 'max_bid_total', $currencyCode);
        $maxBidTotalAboveReserve = $this->amountRender($row, 'max_bid_total_above_reserve', $currencyCode);
        $maxBidTotalAboveCost = $this->amountRender($row, 'max_bid_total_above_cost', $currencyCode);
        $lotsSoldTotal = $this->amountRender($row, 'lots_sold_total', $currencyCode);
        $lotsSoldOnline = $this->amountRender($row, 'lots_sold_online', $currencyCode);
        $registeredBidderCount = $numberFormatter->formatDecimal((int)$row['registered_bidders']);
        $approvedBidderCount = $numberFormatter->formatDecimal((int)$row['approved_bidders']);
        $liveBidderCount = $numberFormatter->formatDecimal((int)$row['live_bidders']);
        $underBidderCount = $numberFormatter->formatDecimal((int)$row['under_bidders']);
        $winningBidderCount = $numberFormatter->formatDecimal((int)$row['winning_bidders']);
        $hammerPrice = $this->amountRender($row, 'hammer_price', $currencyCode);
        $invoiceCount = $numberFormatter->formatDecimal((int)$row['invoices']);
        $invoiceTotal = $this->amountRender($row, 'invoice_total', $currencyCode);
        $invoiceShippedTotal = $this->amountRender($row, 'invoice_shipped', $currencyCode);
        $invoicePaidTotal = $this->amountRender($row, 'invoice_paid', $currencyCode);
        $invoicePendingTotal = $this->amountRender($row, 'invoice_pending', $currencyCode);
        $invoiceOpenTotal = $this->amountRender($row, 'invoice_open', $currencyCode);
        $invoiceCanceledTotal = $this->amountRender($row, 'invoice_canceled', $currencyCode);
        $invoiceDeletedTotal = $this->amountRender($row, 'invoice_deleted', $currencyCode);

        $account = [];
        if ($this->isAccountFiltering) {
            $account = [$accountName];
        }
        $bodyRow = [
            $auctionName,
            $description,
            $startDateIso,
            $startClosingDateIso,
            $endDateIso,
            $auctionTypeName,
            $saleNo,
            $pageViewCount,
            $id,
            $categoryCount,
            $lotCount,
            $lotWithBidsCount,
            $currentBidTotal,
            $currentBidTotalAboveReserve,
            $maxBidTotal,
            $maxBidTotalAboveReserve,
            $maxBidTotalAboveCost,
            $lotsSoldTotal,
            $lotsSoldOnline,
            $registeredBidderCount,
            $approvedBidderCount,
            $liveBidderCount,
            $underBidderCount,
            $winningBidderCount,
            $hammerPrice,
            $invoiceCount,
            $invoiceTotal,
            $invoiceShippedTotal,
            $invoicePaidTotal,
            $invoicePendingTotal,
            $invoiceOpenTotal,
            $invoiceCanceledTotal,
            $invoiceDeletedTotal,
        ];
        $bodyRow = array_merge($account, $bodyRow);

        foreach ($bodyRow as $i => $value) {
            $bodyRow[$i] = $this->getReportTool()->prepareValue($value, $this->getEncoding());
        }

        return $bodyRow;
    }

    /**
     * @param array $row
     * @param string $key
     * @param string $currencyCode
     * @return string
     */
    protected function amountRender(array $row, string $key, string $currencyCode): string
    {
        $amount = $row[$key] ?? 0;
        if (
            (string)$row[$key] === ''
            || is_numeric($row[$key])
        ) {
            return $this->getLocaleNumberFormatter()->formatCurrency((float)$amount, $currencyCode);
        }
        return (string)$row[$key];
    }

    /**
     * Get CSV header titles
     * @return array
     */
    protected function getFields(): array
    {
        $accountName = [];
        if ($this->isAccountFiltering) {
            $accountName = ["Account"];
        }
        $headerTitles = [
            "Sale",
            "Description",
            "Start Date",
            "Start Closing Date",
            "End Date",
            "Type",
            "Sale #",
            "Page Views",
            "ID",
            "Categories",
            "Lots",
            "Lots with bids",
            "Current Bid Total",
            "Current Bid Total Above Reserve",
            "Max Bid Total",
            "Max Bid Total Above Reserve",
            "Max Bid Total Above Cost",
            "Lots Sold Total",
            "Lots Sold Online",
            "Registered Bidders",
            "Approved Bidders",
            "Live Bidders",
            "Under Bidders",
            "Winning Bidders",
            "Hammer Price",
            "Invoices",
            "Invoice Total",
            "Shipped",
            "Paid",
            "Pending",
            "Open",
            "Canceled",
            "Deleted",
        ];

        return array_merge($accountName, $headerTitles);
    }
}
