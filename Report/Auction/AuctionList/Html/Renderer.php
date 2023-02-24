<?php
/**
 * SAM-4627: Refactor auction list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-05-02
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\AuctionList\Html;

use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Renderer
 */
class Renderer extends CustomizableClass
{
    use CurrencyLoaderAwareTrait;
    use DateTimeFormatterAwareTrait;
    use NumberFormatterAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // Render method

    /**
     * @param array $row
     * @return string
     */
    public function renderAccount(array $row): string
    {
        return ee($row['account_name']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderName(array $row): string
    {
        return ee($row['auction_name']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderDescription(array $row): string
    {
        return (string)$row['description'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderStartDate(array $row): string
    {
        $auctionType = $row['auction_type'];
        $eventType = (int)$row['event_type'];
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if (
            $auctionStatusPureChecker->isLiveOrHybrid($auctionType)
            || $auctionStatusPureChecker->isTimedScheduled($auctionType, $eventType)
        ) {
            return $this->getDateTimeFormatter()->format($row['start_date'], $row['timezone_location']);
        }
        return 'N/A';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderStartClosingDate(array $row): string
    {
        $auctionType = $row['auction_type'];
        $eventType = (int)$row['event_type'];
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if (
            $auctionStatusPureChecker->isLiveOrHybrid($auctionType)
            || $auctionStatusPureChecker->isTimedScheduled($auctionType, $eventType)
        ) {
            return $this->getDateTimeFormatter()->format($row['start_closing_date'], $row['timezone_location']);
        }
        return 'N/A';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderEndDate(array $row): string
    {
        $auctionType = $row['auction_type'];
        $eventType = (int)$row['event_type'];
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isTimedScheduled($auctionType, $eventType)) {
            return $this->getDateTimeFormatter()->format($row['end_date'], $row['timezone_location']);
        }
        return 'N/A';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderAuctionType(array $row): string
    {
        return AuctionPureRenderer::new()->makeAuctionType($row['auction_type']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderSaleNo(array $row): string
    {
        return (string)$row['sale_num'];
    }

    /**
     * @param array $row
     * @return int
     */
    public function renderViewCount(array $row): int
    {
        return (int)$row['page_views'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderId(array $row): string
    {
        return (string)$row['id'];
    }

    /**
     * @param array $row
     * @return int
     */
    public function renderCategories(array $row): int
    {
        return (int)$row['categories'];
    }

    /**
     * @param array $row
     * @return int
     */
    public function renderLotCount(array $row): int
    {
        return (int)$row['lots'];
    }

    /**
     * @param array $row
     * @return int
     */
    public function renderLotWithBidCount(array $row): int
    {
        return (int)$row['lots_with_bids'];
    }

    /**
     * @param array $row
     * @return int
     */
    public function renderTotalBidCount(array $row): int
    {
        return (int)$row['total_bids'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderCurrentBidTotal(array $row): string
    {
        return $this->amountRender($row, 'current_bid_total');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderCurrentBidTotalAboveReserve(array $row): string
    {
        return $this->amountRender($row, 'current_bid_total_above_reserve');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderMaxBidTotal(array $row): string
    {
        return $this->amountRender($row, 'max_bid_total');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderMaxBidTotalAboveReserve(array $row): string
    {
        return $this->amountRender($row, 'max_bid_total_above_reserve');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderMaxBidTotalAboveCost(array $row): string
    {
        return $this->amountRender($row, 'max_bid_total_above_cost');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderLotsSoldTotal(array $row): string
    {
        return $this->amountRender($row, 'lots_sold_total');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderLotsSoldOnline(array $row): string
    {
        return $this->amountRender($row, 'lots_sold_online');
    }

    /**
     * @param array $row
     * @return int
     */
    public function renderRegisteredBidders(array $row): int
    {
        return (int)$row['registered_bidders'];
    }

    /**
     * @param array $row
     * @return int
     */
    public function renderApprovedBidders(array $row): int
    {
        return (int)$row['approved_bidders'];
    }

    /**
     * @param array $row
     * @return int
     */
    public function renderLiveBidders(array $row): int
    {
        return (int)$row['live_bidders'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderUnderBidders(array $row): string
    {
        $underBiddersReportUrl = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_REPORTS_UNDER_BIDDERS)
        );
        $underBidders = (int)$row['under_bidders'];
        return '<a href="' . $underBiddersReportUrl . '?auction_id=' . (int)$row['id'] . '">' . $underBidders . '</a>';
    }

    /**
     * @param array $row
     * @return int
     */
    public function renderWinningBidders(array $row): int
    {
        return (int)$row['winning_bidders'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderHammerPrice(array $row): string
    {
        return $this->amountRender($row, 'hammer_price');
    }

    /**
     * @param array $row
     * @return int
     */
    public function renderInvoices(array $row): int
    {
        return (int)$row['invoices'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInvoiceTotal(array $row): string
    {
        return $this->amountRender($row, 'invoice_total');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInvoiceShipped(array $row): string
    {
        return $this->amountRender($row, 'invoice_shipped');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInvoicePaid(array $row): string
    {
        return $this->amountRender($row, 'invoice_paid');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInvoicePending(array $row): string
    {
        return $this->amountRender($row, 'invoice_pending');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInvoiceOpen(array $row): string
    {
        return $this->amountRender($row, 'invoice_open');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInvoiceCanceled(array $row): string
    {
        return $this->amountRender($row, 'invoice_canceled');
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInvoiceDeleted(array $row): string
    {
        return $this->amountRender($row, 'invoice_deleted');
    }

    /**
     * @param array $row
     * @param string $key
     * @return string
     */
    protected function amountRender(array $row, string $key): string
    {
        $currency = $row['currency_sign'] ?? $this->getCurrencyLoader()->detectDefaultSign();
        if (isset($row[$key])) {
            if (is_numeric($row[$key])) {
                return $currency . $this->getNumberFormatter()->formatMoneyNto($row[$key]);
            }
            return (string)$row[$key];
        }
        return $currency . '0';
    }
}
