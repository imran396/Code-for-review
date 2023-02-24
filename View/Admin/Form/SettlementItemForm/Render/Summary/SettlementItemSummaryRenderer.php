<?php
/**
 * SAM-10187: Admin Settlement info page/Frontend Settlement print preview page - Need to update field management for applied payment records
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-13, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementItemForm\Render\Summary;

use Sam\Core\Constants\Admin\SettlementItemFormConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\View\Base\SettlementItemList\SettlementItemListTotalViewCreateTrait;
use Settlement;

/**
 * Class SettlementItemSummaryRenderer
 * @package Sam\View\Admin\Form\SettlementItemForm\Render\Summary
 */
class SettlementItemSummaryRenderer extends CustomizableClass
{
    use SettlementItemListTotalViewCreateTrait;
    use TranslatorAwareTrait;

    /** @var string */
    protected const SUMMARY_HTML_TPL = <<<HTML
<div class="settlementSubtotalTable-wrapper">
<table id="settlementSubtotalTable" class="settlement-datagrid subtotal borderOne">
    <thead>
        <tr>
            <th class="subtotal-labels"></th>
            <th class="subtotal-values"></th>
            <th class="subtotal-actions" style="width:96px;"></th>
        </tr>
    </thead>
    <tbody>
        %unpaidSubtotalHtml%
        %paidSubtotalHtml%
        %subtotalAmountHtml%
        %extraChargeHtml%
        %taxInclusiveHtml%
        %taxExclusiveHtml%
        %totalDueHtml%
        %paymentsMadeHtml%
        %balanceDueHtml%        
    </tbody>
</table>
</div>
HTML;


    // Pre-rendered HTML at caller
    /** @var string */
    protected string $chargeNameHtml = '';
    /** @var string */
    protected string $chargeAmountHtml = '';
    /** @var string */
    protected string $removeChargeHtml = '';
    /** @var string */
    protected string $btnAddChargeHtml = '';
    /** @var string */
    protected string $icoChargeWaitHtml = '';
    /** @var string */
    protected string $paymentNameHtml = '';
    /** @var string */
    protected string $paymentAmountHtml = '';
    /** @var string */
    protected string $removePaymentHtml = '';
    /** @var string */
    protected string $btnAddPaymentHtml = '';
    /** @var string */
    protected string $icoPaymentWaitHtml = '';
    /** @var string */
    protected string $btnRecalculateHtml = '';

    /** @var Settlement */
    protected Settlement $settlement;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(Settlement $settlement): static
    {
        $this->settlement = $settlement;
        return $this;
    }

    public function withPreRenderedControlsHtml(
        string $chargeNameHtml,
        string $chargeAmountHtml,
        string $removeChargeHtml,
        string $btnAddChargeHtml,
        string $icoChargeWaitHtml,
        string $paymentNameHtml,
        string $paymentAmountHtml,
        string $removePaymentHtml,
        string $btnAddPaymentHtml,
        string $icoPaymentWaitHtml,
        string $btnRecalculateHtml
    ): static {
        $this->chargeNameHtml = $chargeNameHtml;
        $this->chargeAmountHtml = $chargeAmountHtml;
        $this->removeChargeHtml = $removeChargeHtml;
        $this->btnAddChargeHtml = $btnAddChargeHtml;
        $this->icoChargeWaitHtml = $icoChargeWaitHtml;
        $this->paymentNameHtml = $paymentNameHtml;
        $this->paymentAmountHtml = $paymentAmountHtml;
        $this->removePaymentHtml = $removePaymentHtml;
        $this->btnAddPaymentHtml = $btnAddPaymentHtml;
        $this->icoPaymentWaitHtml = $icoPaymentWaitHtml;
        $this->btnRecalculateHtml = $btnRecalculateHtml;

        return $this;
    }

    public function render(
        int $accountId,
        bool $isChargeConsignorCommission,
        string $currencySign,
        string $subtotalLbl,
        string $totalLbl,
        string $taxExcLbl
    ): string {
        $tr = $this->getTranslator();
        $trSection = 'mysettlements';
        $unpaidLotsLbl = $tr->translate('MYSETTLEMENTS_DETAIL_UNPAID_LOTS', $trSection);
        $paidLotsLbl = $tr->translate('MYSETTLEMENTS_DETAIL_PAID_LOTS', $trSection);
        $extraChargeLbl = $tr->translate('MYSETTLEMENTS_DETAIL_EXTRA_CHARGE', $trSection);
        $paymentsMadeLbl = $tr->translate('MYSETTLEMENTS_DETAIL_PAYMENTS_MADE', $trSection);
        $balanceDueLbl = $tr->translate('MYSETTLEMENTS_DETAIL_BALANCEDUE', $trSection);
        $commissionSubtotalLbl = $tr->translate('MYSETTLEMENTS_DETAIL_COMM_SUBTOTAL', $trSection);
        $taxIncLbl = $tr->translate('MYSETTLEMENTS_DETAIL_TAX_INC', $trSection);

        $totalView = $this->createSettlementItemListTotalView()->construct($this->settlement->Id, $accountId);
        [$taxInclusiveViewFormatted, $taxInclusiveRealFormatted] = $totalView->formatSettlementTax($this->settlement->TaxInclusive);
        [$taxExclusiveViewFormatted, $taxExclusiveRealFormatted] = $totalView->formatSettlementTax($this->settlement->TaxExclusive);
        [$unpaidViewFormatted, $unpaidRealFormatted] = $totalView->makeUnpaidAmountFormatted();
        [$paidViewFormatted, $paidRealFormatted] = $totalView->makePaidAmountFormatted();

        $rowIndex = 0;

        $unpaidSubtotalHtml = $paidSubtotalHtml = $subtotalAmountHtml = $taxInclusiveHtml = $taxExclusiveHtml = '';

        if ($unpaidViewFormatted !== '') {
            $rowControlId = sprintf(SettlementItemFormConstants::CID_BLK_DATA_GRID_ROW_TPL, $rowIndex);
            $cssClassLotsSubtotalUnpaid = SettlementItemFormConstants::CSS_SETTLEMENT_LOTS_SUBTOTAL_UNPAID_WRAPPER;
            $unpaidSubtotalHtml = <<<HTML
<tr id="{$rowControlId}" class="{$cssClassLotsSubtotalUnpaid}">    
    <td class="number label">{$subtotalLbl} ($unpaidLotsLbl)[{$currencySign}]:</td>
    <td class="number" title="{$currencySign}{$unpaidRealFormatted}">{$unpaidViewFormatted}</td>
    <td></td>
</tr>
HTML;
            $rowIndex++;
        }

        if ($paidViewFormatted !== '') {
            $rowControlId = sprintf(SettlementItemFormConstants::CID_BLK_DATA_GRID_ROW_TPL, $rowIndex);
            $cssClassLotsSubtotalPaid = SettlementItemFormConstants::CSS_SETTLEMENT_LOTS_SUBTOTAL_PAID_WRAPPER;
            $paidSubtotalHtml = <<<HTML
<tr id="$rowControlId" class="{$cssClassLotsSubtotalPaid}">
    <td class="number label">{$subtotalLbl} ($paidLotsLbl)[{$currencySign}]:</td>
    <td class="number" title="{$currencySign}{$paidRealFormatted}">{$paidViewFormatted}</td>
    <td></td>
</tr>
HTML;
            $rowIndex++;
        }

        [$subTotalViewFormatted, $subTotalRealFormatted] = $totalView->makeSubtotalAmountFormatted();
        if ($subTotalViewFormatted !== '') {
            $lblSubtotal = $isChargeConsignorCommission ? $commissionSubtotalLbl : $subtotalLbl;
            $rowControlId = sprintf(SettlementItemFormConstants::CID_BLK_DATA_GRID_ROW_TPL, $rowIndex);
            $cssClassLotsSubtotal = SettlementItemFormConstants::CSS_SETTLEMENT_LOTS_SUBTOTAL_WRAPPER;
            $subtotalAmountHtml = <<<HTML
<tr id="{$rowControlId}" class="{$cssClassLotsSubtotal}">    
    <td class="number label">{$lblSubtotal}[{$currencySign}]:</td>
    <td class="number" title="{$currencySign}{$subTotalRealFormatted}">{$subTotalViewFormatted}</td>
    <td>&nbsp;</td>
</tr>
HTML;
            $rowIndex++;
        }

        $gridRowControlId = sprintf(SettlementItemFormConstants::CID_BLK_DATA_GRID_ROW_TPL, $rowIndex);
        $cssClassLotsExtraCharge = SettlementItemFormConstants::CSS_SETTLEMENT_LOTS_EXTRA_CHARGE_WRAPPER;
        $extraChargeHtml = <<<HTML
<tr id="{$gridRowControlId}" valign="bottom" class="{$cssClassLotsExtraCharge}">
    <td class="number label">{$extraChargeLbl}[{$currencySign}]:<br />{$this->chargeNameHtml}</td>
    <td class="number">{$this->icoChargeWaitHtml}{$this->btnAddChargeHtml}<br />{$this->chargeAmountHtml}</td>
    <td>{$this->removeChargeHtml}</td>
</tr>
HTML;
        $rowIndex++;

        if ($taxInclusiveViewFormatted !== '') {
            $taxAmountHtml = '<span id="' . SettlementItemFormConstants::CID_LBL_TAX_AMOUNT . '">' . $taxInclusiveViewFormatted . '</span>';
            $rowControlId = sprintf(SettlementItemFormConstants::CID_BLK_DATA_GRID_ROW_TPL, $rowIndex);
            $cssClassLotsTaxInc = SettlementItemFormConstants::CSS_SETTLEMENT_LOTS_TAX_INCLUSIVE_WRAPPER;
            $taxInclusiveHtml = <<<HTML
<tr id="{$rowControlId}" class="{$cssClassLotsTaxInc}">
    <td class="number label">{$taxIncLbl}[$currencySign]:</td>
    <td class="number" title="{$currencySign}{$taxInclusiveRealFormatted}">{$taxAmountHtml}</td>
    <td></td>
</tr>
HTML;
            $rowIndex++;
        }

        if ($taxExclusiveViewFormatted !== '') {
            $taxAmountHtml = '<span id="' . SettlementItemFormConstants::CID_LBL_TAX_AMOUNT . '">' . $taxExclusiveViewFormatted . '</span>';
            $rowControlId = sprintf(SettlementItemFormConstants::CID_BLK_DATA_GRID_ROW_TPL, $rowIndex);
            $cssClassLotsTaxExcl = SettlementItemFormConstants::CSS_SETTLEMENT_LOTS_TAX_EXCLUSIVE_WRAPPER;
            $taxExclusiveHtml = <<<HTML
<tr id="$rowControlId" class="{$cssClassLotsTaxExcl}">
    <td class="number label">{$taxExcLbl}[$currencySign]:</td>
    <td class="number" title="{$currencySign}{$taxExclusiveRealFormatted}">{$taxAmountHtml}</td>
    <td></td>
</tr>
HTML;
            $rowIndex++;
        }

        [$totalDueViewFormatted, $totalDueRealFormatted] = $totalView->makeTotalDueFormatted();
        $rowControlId = sprintf(SettlementItemFormConstants::CID_BLK_DATA_GRID_ROW_TPL, $rowIndex);
        $cssClassLotsGrandTotal = SettlementItemFormConstants::CSS_SETTLEMENT_LOTS_GRAND_TOTAL_WRAPPER;
        $totalDueHtml = <<<HTML
<tr id="$rowControlId" class="{$cssClassLotsGrandTotal}">
    <td class="number label">{$totalLbl}[$currencySign]:</td>
    <td class="number" title="{$currencySign}{$totalDueRealFormatted}">{$totalDueViewFormatted}</td>
    <td></td>
</tr>

HTML;
        $rowIndex++;

        $rowControlId = sprintf(SettlementItemFormConstants::CID_BLK_DATA_GRID_ROW_TPL, $rowIndex);
        $cssClassLotsPaymentsMade = SettlementItemFormConstants::CSS_SETTLEMENT_LOTS_PAYMENTS_MADE_WRAPPER;
        $paymentsMadeHtml = <<<HTML
<tr id="{$rowControlId}" class="{$cssClassLotsPaymentsMade}" valign="bottom">
    <td class="number label payment-method">
        <div class="heading">{$paymentsMadeLbl}[{$currencySign}]:</div>
        <div class="content">{$this->paymentNameHtml}</div>
    </td>
    <td class="number amount">
        <div class="heading">{$this->icoPaymentWaitHtml}{$this->btnAddPaymentHtml}</div>
        <div class="content">{$this->paymentAmountHtml}</div>
    </td>
    <td class="actions">
        <div class="heading">&nbsp;</div>
        <div class="content">{$this->removePaymentHtml}</div>
    </td>
</tr>

HTML;

        $rowIndex++;

        $balanceViewFormatted = $totalView->makeBalanceDueFormatted();
        $rowControlId = sprintf(SettlementItemFormConstants::CID_BLK_DATA_GRID_ROW_TPL, $rowIndex);
        $cssClassLotsBalanceDue = SettlementItemFormConstants::CSS_SETTLEMENT_LOTS_BALANCE_DUE_WRAPPER;
        $balanceDueHtml = <<<HTML
<tr id="{$rowControlId}" class="{$cssClassLotsBalanceDue}">
    <td class="number label">{$balanceDueLbl}[$currencySign]:</td>
    <td class="number">{$balanceViewFormatted}</td>
    <td>{$this->btnRecalculateHtml}</td>
</tr>

HTML;

        $html = strtr(
            self::SUMMARY_HTML_TPL,
            [
                '%unpaidSubtotalHtml%' => $unpaidSubtotalHtml,
                '%paidSubtotalHtml%' => $paidSubtotalHtml,
                '%subtotalAmountHtml%' => $subtotalAmountHtml,
                '%extraChargeHtml%' => $extraChargeHtml,
                '%taxInclusiveHtml%' => $taxInclusiveHtml,
                '%taxExclusiveHtml%' => $taxExclusiveHtml,
                '%totalDueHtml%' => $totalDueHtml,
                '%paymentsMadeHtml%' => $paymentsMadeHtml,
                '%balanceDueHtml%' => $balanceDueHtml,

            ]
        );
        return $html;
    }
}
