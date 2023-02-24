<?php
/**
 * SAM-4635 : Refactor tax report
 * https://bidpath.atlassian.net/browse/SAM-4635
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/14/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Tax\Html;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Url\Build\Config\Invoice\AnySingleInvoiceUrlConfig;
use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Invoice\Status\InvoiceStatusPureChecker;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\RendererBase;
use Sam\Transform\Number\NumberFormatter;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Transform\Number\NumberFormatterInterface;

/**
 * Class Renderer
 * @package Sam\Report\Tax\Html
 */
class Renderer extends RendererBase
{
    use AuctionRendererAwareTrait;
    use DateTimeFormatterAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use ServerRequestReaderAwareTrait;
    use UrlParserAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Overwrite for NumberFormatterAwareTrait::getNumberFormatter()
     * @return NumberFormatterInterface
     */
    public function getNumberFormatter(): NumberFormatterInterface
    {
        if ($this->numberFormatter === null) {
            $this->numberFormatter = NumberFormatter::new()
                ->construct($this->getSystemAccountId());
        }
        return $this->numberFormatter;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderPaymentDate(array $row): string
    {
        return $this->getDateTimeFormatter()->format($row['invoice_date']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderUsername(array $row): string
    {
        $userId = (int)$row['user_id'];
        $username = (string)$row['username'];
        if (
            !$userId
            || !$username
        ) {
            return '';
        }
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb($userId)
        );
        return '<a href="' . $url . '">' . ee($username) . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderBidderNumber(array $row): string
    {
        $output = '';
        if ($row['bidder_num'] !== null) {
            $params = [
                'auction_id' => (int)$row['auction_id'],
                'user_id' => (int)$row['user_id'],
                'currency' => (int)$row['currency_id'],
                'chkFilterDate' => 0,
            ];
            $url = $this->getServerRequestReader()->cleanUrl();
            $url = $this->getUrlParser()->replaceParams($url, $params);
            $output = "<a href=\"{$url}\">{$row['bidder_num']}</a>";
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderSaleNo(array $row): string
    {
        $output = $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderLotNo(array $row): string
    {
        return $this->getLotRenderer()
            ->makeLotNo($row['lot_number'], $row['lot_number_ext'], $row['lot_number_prefix']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInvoiceNumber(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AnySingleInvoiceUrlConfig::new()->forWeb(
                Constants\Url::A_INVOICES_EDIT,
                (int)$row['invoice_id']
            )
        );
        $link = '<a href="' . $url . '">' . $row['invoice_no'] . '</a>';
        return $link;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderLotName(array $row): string
    {
        return ee($row['lot_name']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderSalesRate(array $row): string
    {
        if (InvoiceStatusPureChecker::new()->isLegacyTaxDesignation((int)$row['tax_designation'])) {
            return $this->getNumberFormatter()->formatPercent($row['sales_tax']);
        }

        return 'N/A';
    }

    /**
     * @param array $summary
     * @return string
     */
    public function renderSummary(array $summary): string
    {
        $saleTax = $this->getNumberFormatter()->formatPercent((float)$summary['sales_tax']);
        $saleTax .= '%' . ':';
        $totalSaleTax = $summary['currency_sign'] . $this->getNumberFormatter()->formatMoney($summary['total_sales_tax']);
        $tableRow = <<<HTML
<tr>
    <td width="120"> {$saleTax} </td> 
    <td width="120"> {$totalSaleTax} </td>
</tr>
HTML;
        return $tableRow;
    }

    /**
     * @param float $price
     * @param string $currencySign
     * @return string
     */
    public function renderPrice(float $price, string $currencySign): string
    {
        $formattedPrice = $currencySign . $this->getNumberFormatter()->formatMoney($price);
        return $formattedPrice;
    }
}
