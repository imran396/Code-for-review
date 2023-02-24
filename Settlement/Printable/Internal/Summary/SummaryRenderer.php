<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-28, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\Summary;

use Sam\Settlement\Printable\Internal\Constants\InternalConstants;
use Sam\Core\Constants\SettlementItemPrintableConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Printable\Internal\Translation\SettlementTranslatorCreateTrait;
use Sam\Settlement\Printable\Internal\Translation\SettlementTranslatorDto;
use Sam\View\Base\SettlementItemList\SettlementItemListTotalView;
use Sam\View\Base\SettlementItemList\SettlementItemListTotalViewCreateTrait;
use Settlement;

/**
 * Renders settlement summary HTML.
 *
 * Class SummaryRenderer
 * @package Sam\Settlement\Printable
 */
class SummaryRenderer extends CustomizableClass
{
    use SettlementItemListTotalViewCreateTrait;
    use SettlementTranslatorCreateTrait;

    protected Settlement $settlement;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Settlement $settlement
     * @return $this
     */
    public function construct(Settlement $settlement): static
    {
        $this->settlement = $settlement;
        return $this;
    }

    /**
     * @param int $settlementAccountId
     * @param bool $isChargeConsignorCommission
     * @param string $currencySign
     * @param bool $isTranslatableLabels
     * @return string
     */
    public function render(
        int $settlementAccountId,
        bool $isChargeConsignorCommission,
        string $currencySign,
        bool $isTranslatableLabels = false
    ): string {
        $html = '';
        $totalView = $this->createSettlementItemListTotalView()->construct($this->settlement->Id, $settlementAccountId);

        $commonTranslations = $this->createSettlementTranslator()->getTranslatedCommon($isTranslatableLabels);

        $html .= $this->renderSubTotalHtml($totalView, $commonTranslations, $isChargeConsignorCommission, $currencySign);
        $html .= $this->renderExtraChargesHtml($totalView, $commonTranslations, $currencySign);

        [$taxInclusiveFormatted] = $totalView->formatSettlementTax($this->settlement->TaxInclusive);
        $html .= $this->renderTaxInclusiveHtml($taxInclusiveFormatted, $commonTranslations, $currencySign);
        [$taxExclusiveFormatted] = $totalView->formatSettlementTax($this->settlement->TaxExclusive);
        $html .= $this->renderTaxExclusiveHtml($taxExclusiveFormatted, $commonTranslations, $currencySign);
        $html .= $this->renderGrandTotalHtml($totalView, $commonTranslations, $currencySign);
        $html .= $this->renderPaymentsMadeHtml($totalView, $commonTranslations, $currencySign);
        $html .= $this->renderBalanceDueHtml($totalView, $commonTranslations, $currencySign);

        return $html;
    }

    /**
     * @param SettlementItemListTotalView $totalView
     * @param SettlementTranslatorDto $commonTranslations
     * @param bool $isChargeConsignorCommission
     * @param string $currencySign
     * @return string
     */
    protected function renderSubTotalHtml(
        SettlementItemListTotalView $totalView,
        SettlementTranslatorDto $commonTranslations,
        bool $isChargeConsignorCommission,
        string $currencySign
    ): string {
        $html = '';

        // Subtotal unpaidLots
        [$unpaidFormatted] = $totalView->makeUnpaidAmountFormatted();
        if ($unpaidFormatted !== '') {
            $cssClass = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUMMARY_UNPAID_SUBTOTAL;

            $html .= <<<HTML
<section class="{$cssClass}">
    <i><span>{$commonTranslations->subtotalLbl} ($commonTranslations->unpaidLotsLbl):</span></i>    
    <em>{$currencySign}{$unpaidFormatted}</em> 
    <div class="clearfix"></div>
</section>

HTML;
        }

        // Subtotal paidLots
        [$paidFormatted] = $totalView->makePaidAmountFormatted();
        if ($paidFormatted !== '') {
            $cssClass = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUMMARY_PAID_SUBTOTAL;

            $html .= <<<HTML
<section class="{$cssClass}">
    <i><span>{$commonTranslations->subtotalLbl} ({$commonTranslations->paidLotsLbl}):</span></i>
    <em>{$currencySign}{$paidFormatted}</em>
    <div class="clearfix"></div>
</section>
HTML;
        }

        // Subtotal Amount
        [$subTotalFormatted] = $totalView->makeSubtotalAmountFormatted();
        if ($subTotalFormatted !== '') {
            $lblSubtotal = $isChargeConsignorCommission
                ? $commonTranslations->commissionSubtotalLbl
                : $commonTranslations->subtotalLbl;
            $cssClass = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUMMARY_SUBTOTAL;

            $html .= <<<HTML
<section class="{$cssClass}">
    <i><span>{$lblSubtotal}:</span></i>
    <em>{$currencySign}{$subTotalFormatted}</em>
    <div class="clearfix"></div>
</section>
HTML;
        }

        return $html;
    }

    /**
     * @param SettlementItemListTotalView $totalView
     * @param SettlementTranslatorDto $commonTranslations
     * @param string $currencySign
     * @return string
     */
    protected function renderExtraChargesHtml(
        SettlementItemListTotalView $totalView,
        SettlementTranslatorDto $commonTranslations,
        string $currencySign
    ): string {
        $chargeNameHtml = $chargeAmountHtml = '';
        $charges = $totalView->makeExtraChargeList();
        foreach ($charges as $charge) {
            $chargeNameHtml .= '<span class="charge-name">' . $charge['name'] . '</span><br />';
            $chargeAmountHtml .= '<span class="charge-amount">' . $currencySign . $charge['amountFormatted'] . '</span><br />';
        }
        $cssClass = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUMMARY_EXTRA_CHARGE;

        $html = <<<HTML
<section class="{$cssClass}">
    <i><span>{$commonTranslations->extraChargeLbl}: </span></i>
    <i><span>{$chargeNameHtml}</span></i>
    <em>{$chargeAmountHtml}</em>
    <div class="clearfix"></div>
</section>
HTML;
        return $html;
    }

    /**
     * @param string $taxInclusiveFormatted
     * @param SettlementTranslatorDto $commonTranslations
     * @param string $currencySign
     * @return string
     */
    protected function renderTaxInclusiveHtml(
        string $taxInclusiveFormatted,
        SettlementTranslatorDto $commonTranslations,
        string $currencySign
    ): string {
        $html = '';
        if ($taxInclusiveFormatted !== '') {
            $spanTaxAmount = '<span id="' . InternalConstants::CID_BLK_TAX_AMOUNT . '">' . $taxInclusiveFormatted . '</span>';
            $cssClass = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUMMARY_TAX_INCL;

            $html = <<<HTML
<section class="{$cssClass}">
    <i><span>{$commonTranslations->taxIncLbl}:</span></i>
    <em>{$currencySign}{$spanTaxAmount}</em>
    <div class="clearfix"></div>
</section>
HTML;
        }

        return $html;
    }

    /**
     * @param string $taxExclusiveFormatted
     * @param SettlementTranslatorDto $commonTranslations
     * @param string $currencySign
     * @return string
     */
    protected function renderTaxExclusiveHtml(
        string $taxExclusiveFormatted,
        SettlementTranslatorDto $commonTranslations,
        string $currencySign
    ): string {
        $html = '';
        if ($taxExclusiveFormatted !== '') {
            $spanTaxAmount = '<span id="' . InternalConstants::CID_BLK_TAX_AMOUNT . '">' . $taxExclusiveFormatted . '</span>';
            $cssClass = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUMMARY_TAX_EXCL;

            $html = <<<HTML
<section class="{$cssClass}">
    <i><span>{$commonTranslations->taxExcLbl}:</span></i>
    <em>{$currencySign}{$spanTaxAmount}</em>
    <div class="clearfix"></div>
</section>
HTML;
        }

        return $html;
    }

    /**
     * @param SettlementItemListTotalView $totalView
     * @param SettlementTranslatorDto $commonTranslations
     * @param string $currencySign
     * @return string
     */
    protected function renderGrandTotalHtml(
        SettlementItemListTotalView $totalView,
        SettlementTranslatorDto $commonTranslations,
        string $currencySign
    ): string {
        [$totalDueFormatted] = $totalView->makeTotalDueFormatted();
        $cssClass = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUMMARY_GARND_TOTAL;

        $html = <<<HTML
<section class="{$cssClass}">
    <i><span>{$commonTranslations->totalLbl}:</span></i>
    <em>{$currencySign}{$totalDueFormatted}</em>
    <div class="clearfix"></div>
</section>
HTML;
        return $html;
    }

    /**
     * @param SettlementItemListTotalView $totalView
     * @param SettlementTranslatorDto $commonTranslations
     * @param string $currencySign
     * @return string
     */
    protected function renderPaymentsMadeHtml(
        SettlementItemListTotalView $totalView,
        SettlementTranslatorDto $commonTranslations,
        string $currencySign
    ): string {
        $paymentsHtml = '';
        $payments = $totalView->makePaymentList();
        foreach ($payments as $payment) {
            $paymentNote = $payment['note']
                ? '<br /><span class="payment-note">' . $payment['note'] . '</span>'
                : '';
            $paymentMethodHtml = '<span class="payment-method">' . $payment['label'] . $paymentNote . '</span><br />';
            $paymentAmountHtml = '<span class="payment-amount">' . $currencySign . $payment['amountFormatted'] . '</span><br />';
            $paymentsHtml .= <<<HTML
    <ul class="single-payment">
        <li class="method"><em>{$paymentMethodHtml}</em></li>
        <li class="amount"><em>{$paymentAmountHtml}</em></li>
    </ul>    

HTML;
        }
        $cssClass = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUMMARY_PAYMENTS_MADE;

        $html = <<<HTML
<h4><span>{$commonTranslations->paymentsMadeLbl}:</span></h4>
<section class="{$cssClass}">
    {$paymentsHtml}
    <div class="clearfix"></div>
</section>
HTML;

        return $html;
    }

    /**
     * @param SettlementItemListTotalView $totalView
     * @param SettlementTranslatorDto $commonTranslations
     * @param string $currencySign
     * @return string
     */
    protected function renderBalanceDueHtml(
        SettlementItemListTotalView $totalView,
        SettlementTranslatorDto $commonTranslations,
        string $currencySign
    ): string {
        $balanceDueFormatted = $totalView->makeBalanceDueFormatted();
        $cssClass = SettlementItemPrintableConstants::CSS_SETTLEMENT_PRINT_SUMMARY_BALANCE_DUE;

        $html = <<<HTML
<section class="total {$cssClass}">
    <i><span>{$commonTranslations->balanceDueLbl}:</span></i>
    <em>{$currencySign}{$balanceDueFormatted}</em>
    <div class="clearfix"></div>
</section>
HTML;

        return $html;
    }
}
