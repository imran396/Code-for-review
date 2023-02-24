<?php
/**
 * SAM-4632 : Refactor payment report
 * https://bidpath.atlassian.net/browse/SAM-4632
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/3/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Payment\Html;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Url\Build\Config\Auction\AnySingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Invoice\AnySingleInvoiceUrlConfig;
use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Billing\Payment\Render\PaymentRendererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;
use Sam\Report\Base\Csv\RendererBase;
use Sam\Transform\Number\NumberFormatter;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Transform\Number\NumberFormatterInterface;

/**
 * Class Renderer
 * @package Sam\Report\Payment\Html
 */
class Renderer extends RendererBase
{
    use AuctionRendererAwareTrait;
    use DateTimeFormatterAwareTrait;
    use NumberFormatterAwareTrait;
    use PaymentRendererAwareTrait;
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
        return $this->getDateTimeFormatter()->format($row['payment_date']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderUsername(array $row): string
    {
        $name = $row['username'];
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb((int)$row['user_id'])
        );
        return '<a href="' . $url . '">' . ee($name) . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderReferrer(array $row): string
    {
        return ee($row['referrer']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderReferrerHost(array $row): string
    {
        return ee($row['referrer_host']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderBidderNumber(array $row): string
    {
        $params = [
            'bidder_num' => $row['bidder_num'],
            'auction_id' => (int)$row['auction_id'],
            'currency' => (int)$row['currency_id'],
            'chkFilterDate' => 0,
            'isFilterDate' => '',
        ];
        $url = $this->getServerRequestReader()->cleanUrl();
        $url = $this->getUrlParser()->replaceParams($url, $params);
        return "<a href=\"{$url}\">{$row['bidder_num']}</a>";
    }

    /**
     * @param array $row
     * @return string
     * @noinspection PhpUnused
     */
    public function renderAuctionNumber(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AnySingleAuctionUrlConfig::new()->forWeb(Constants\Url::A_AUCTIONS_LOT_LIST, (int)$row['auction_id'])
        );
        $saleNo = $this->getAuctionRenderer()->makeSaleNo($row['sale_num'], $row['sale_num_ext']);
        return '<a href="' . $url . '">' . $saleNo . '</a>';
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
        return '<a href="' . $url . '">' . $row['invoice_no'] . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderPaymentType(array $row): string
    {
        $typeSeparator = " - ";
        $paymentMethodName = $this->getPaymentRenderer()->makePaymentMethodTranslated((int)$row['payment_method_id']);
        return rtrim($paymentMethodName . $typeSeparator . $row['credit_card_type'], $typeSeparator);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderNote(array $row): string
    {
        return (string)$row['note'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderSummary(array $row): string
    {
        $paymentMethodName = $this->getPaymentRenderer()->makePaymentMethodTranslated((int)$row['payment_method_id']);
        $totalPayment = $row['currency_sign'] . $this->getNumberFormatter()->formatMoney($row['total']);
        $tableRow = <<<HTML
 <tr>
     <td width="120"> {$paymentMethodName}: </td>
     <td width="120">{$totalPayment}</td>
</tr>
HTML;
        return $tableRow;
    }

}
