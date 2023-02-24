<?php
/**
 * SAM-4633:Refactor sales staff report
 * https://bidpath.atlassian.net/browse/SAM-4633
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: Renderer: $
 * @since           5/16/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff\Html;

use Sam\Core\Constants\Admin\SaleStaffReportFormConstants;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Locale\Formatter\DateTimeFormatterAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Report\Base\Csv\RendererBase;
use Sam\Report\SaleStaff\Calculate\Dto\SaleStaffPayoutCalculatorData;
use Sam\Report\SaleStaff\Calculate\SaleStaffPayoutCalculator;
use Sam\Report\SaleStaff\Common\FilterAwareTrait;
use Sam\Transform\Number\NumberFormatter;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Transform\Number\NumberFormatterInterface;

/**
 * Class Renderer
 * @package Sam\Report\SaleStaff\Html
 */
class Renderer extends RendererBase
{
    use CurrencyLoaderAwareTrait;
    use DateTimeFormatterAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;

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
    public function renderSalesStaff(array $row): string
    {
        return (string)$row['sales_staff'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderOfficeLocation(array $row): string
    {
        return ee($row['office_location']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderConsignor(array $row): string
    {
        $output = '';
        if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
            $output = ee($row['consignor_username']);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderConsignorCompany(array $row): string
    {
        $output = '';
        if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
            $output = ee($row['consignor_company']);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderBuyer(array $row): string
    {
        $output = '';
        if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
            $output = ee($row['buyer_username']);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderBuyerCompany(array $row): string
    {
        $output = '';
        if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
            $output = ee($row['buyer_company']);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderItemNo(array $row): string
    {
        $output = '';
        if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
            $output = $this->getLotRenderer()->makeItemNo($row['item_num'], $row['item_num_ext']);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderAuctionName(array $row): string
    {
        $output = '';
        if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
            $output = ee($row['auc_name']);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderLotNo(array $row): string
    {
        $output = '';
        if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
            $output = $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderLotName(array $row): string
    {
        $output = '';
        if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
            $output = ee($row['lot_name']);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInvoiceStatus(array $row): string
    {
        $output = '';
        if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
            $output = $row['inv_status_name'];
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderHammerPrice(array $row): string
    {
        if (isset($row['currency_sign'])) {
            $output = $row['currency_sign'] . $this->getNumberFormatter()->formatMoney($row['hammer_price']);
        } else {
            $output = $this->getCurrencyLoader()->detectDefaultSign() . $this->getNumberFormatter()->formatMoney($row['hammer_price']);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderBuyersPremium(array $row): string
    {
        $premium = $row['buyers_premium'];
        if (isset($row['currency_sign'])) {
            $output = $row['currency_sign'] . $this->getNumberFormatter()->formatMoney($premium);
        } else {
            $output = $this->getCurrencyLoader()->detectDefaultSign() . $this->getNumberFormatter()->formatMoney($premium);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderTax(array $row): string
    {
        $taxAmount = $row['sales_tax'];
        if (isset($row['currency_sign'])) {
            $output = $row['currency_sign'] . $this->getNumberFormatter()->formatMoney($taxAmount);
        } else {
            $output = $this->getCurrencyLoader()->detectDefaultSign() . $this->getNumberFormatter()->formatMoney($taxAmount);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderTotal(array $row): string
    {
        $total = $row['total'];
        if (isset($row['currency_sign'])) {
            $output = $row['currency_sign'] . $this->getNumberFormatter()->formatMoney($total);
        } else {
            $output = $this->getCurrencyLoader()->detectDefaultSign() . $this->getNumberFormatter()->formatMoney($total);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderDateSold(array $row): string
    {
        $output = '';
        if (
            $row['date_sold']
            && $this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE
        ) {
            $output = $this->getDateTimeFormatter()->format($row['date_sold']);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInvoiceDate(array $row): string
    {
        $output = '';
        if (
            $row['inv_date']
            && SaleStaffReportFormConstants::CT_NONE === $this->getConsolidationType()
        ) {
            $output = $this->getDateTimeFormatter()->format($row['inv_date']);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderPaymentDate(array $row): string
    {
        $output = '';
        if ($this->getConsolidationType() === SaleStaffReportFormConstants::CT_NONE) {
            if ($row['pay_date']) {
                $output = $this->getDateTimeFormatter()->format($row['pay_date']);
            } elseif ($row['pay_created']) {
                $output = $this->getDateTimeFormatter()->format($row['pay_created']);
            }
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderReferrer(array $row): string
    {
        return $row['buyer_referrer'] ?? '';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderReferrerHost(array $row): string
    {
        return $row['buyer_referrer_host'] ?? '';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderPayout(array $row): string
    {
        if (isset($row['payout'])) {
            $payout = (float)$row['payout'];
        } else {
            $payout = SaleStaffPayoutCalculator::new()
                ->construct(
                    $this->getPayoutType(),
                    $this->getPayoutApplyStatus(),
                    SaleStaffPayoutCalculatorData::new()->fromDbRow($row)
                )
                ->calculate();
        }
        if (isset($row['currency_sign'])) {
            $output = $row['currency_sign'] . $this->getNumberFormatter()->formatMoney($payout);
        } else {
            $output = $this->getCurrencyLoader()->detectDefaultSign() . $this->getNumberFormatter()->formatMoney($payout);
        }
        return $output;
    }
}
