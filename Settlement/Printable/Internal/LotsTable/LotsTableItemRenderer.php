<?php
/**
 * Settlement Printable & viewable version to the new layout Implementation: SAM-7661
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-03, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\LotsTable;

use Sam\Settlement\Printable\Internal\Constants\InternalConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\Dto\SettlementItemDto;
use Sam\View\Base\SettlementItemList\LotItemCustomFieldValueDto;
use Sam\View\Base\SettlementItemList\SettlementItemView;
use Sam\View\Base\SettlementItemList\SettlementItemViewCreateTrait;

/**
 * Renders Settlement lots table row HTML for single settlement lot.
 *
 * Class LotsTableItemRenderer
 * @package Sam\Settlement\Printable
 */
class LotsTableItemRenderer extends CustomizableClass
{
    use SettlementItemViewCreateTrait;

    private const COLSPAN_SINGLE_ROW = 13;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SettlementItemDto[] $settlementItemDtos
     * @param int $settlementAccountId
     * @param string $currencySign
     * @param bool $isQuantityInSettlement
     * @param bool $isHideLotNumAndSaleNameColumns
     * @param bool $isRenderLotItemCustomFields
     * @param bool $isInlineCss
     * @param bool $isCustomFieldInSeparateRow
     * @return string
     */
    public function render(
        array $settlementItemDtos,
        int $settlementAccountId,
        string $currencySign,
        bool $isQuantityInSettlement,
        bool $isHideLotNumAndSaleNameColumns,
        bool $isRenderLotItemCustomFields,
        bool $isInlineCss,
        bool $isCustomFieldInSeparateRow
    ): string {
        $html = '';
        $rowIndex = 0;

        $lotNumStyle = $saleNameStyle = '';
        if ($isHideLotNumAndSaleNameColumns) {
            $lotNumStyle = $saleNameStyle = ' style="display:none;"';
        }

        foreach ($settlementItemDtos as $dto) {
            $emailTdInlineCss = $emailTdLotNumInlineCss = $emailTdSaleNameInlineCss = '';
            $itemView = $this->createSettlementItemView()->construct($dto, $currencySign, $settlementAccountId);
            $cssClassDto = LotsTableCssClassDto::new();
            $unPaidCssClass = $itemView->isUnpaid() ? ' unpaid ' : '';

            $rowClasses = [];
            if ($rowIndex % 2 === 0) {
                $rowClasses[] = 'alternate';
            }

            if ($itemView->isLotUnsold()) {
                $rowClasses[] = 'unsold';
                if ($isInlineCss) {
                    $emailTdInlineCss = $emailTdLotNumInlineCss = $emailTdSaleNameInlineCss = sprintf('style="%s"', InternalConstants::STYLE_UNSOLD_LOT);
                    if ($isHideLotNumAndSaleNameColumns) {
                        $emailTdLotNumInlineCss = $emailTdSaleNameInlineCss = '';
                    }
                }
            }

            $lotCustomFieldValueDtos = $itemView->makeLotCustomFieldValueList();
            $lotCustomFieldColumns = '';
            if (
                $isRenderLotItemCustomFields
                && !$isCustomFieldInSeparateRow
            ) {
                $lotCustomFieldColumns = $this->renderCustomFieldColumns($lotCustomFieldValueDtos, $unPaidCssClass, $emailTdInlineCss);
            }
            $quantityColumn = $this->renderQuantityColumn($isQuantityInSettlement, $itemView, $cssClassDto->quantity, $unPaidCssClass, $emailTdInlineCss);
            $rowClass = $rowClasses ? 'class="' . implode(' ', $rowClasses) . '"' : '';
            $rowControlId = sprintf(InternalConstants::CID_BLK_DATA_GRID_BOTTOM_ROW_TPL, $rowIndex);
            $html .= <<<HTML
<tr id="{$rowControlId}" {$rowClass}>
    <td {$emailTdLotNumInlineCss} class="{$unPaidCssClass}{$cssClassDto->lotNum}" {$lotNumStyle}>{$itemView->makeItemNo()}</td>
    <td {$emailTdInlineCss} class="{$unPaidCssClass}{$cssClassDto->saleLotNumSaleNum}">{$itemView->makeSaleNo()}/{$itemView->makeLotNo()}</td>
    <td {$emailTdInlineCss} class="{$unPaidCssClass}{$cssClassDto->estimates}">{$itemView->makeEstimatesFormatted()}</td>
    <td {$emailTdInlineCss} class="{$unPaidCssClass}{$cssClassDto->lotName}">{$itemView->makeLotName()}</td>
    {$lotCustomFieldColumns}
    {$quantityColumn}
    <td {$emailTdSaleNameInlineCss} class="{$unPaidCssClass}{$cssClassDto->saleName}" {$saleNameStyle}>{$itemView->makeSaleName()}</td>
    <td {$emailTdInlineCss} class="number {$unPaidCssClass}{$cssClassDto->hammerPrice}">{$itemView->makeHammerPriceFormatted()}</td>
    <td {$emailTdInlineCss} class="number {$unPaidCssClass}{$cssClassDto->taxHp}">{$itemView->makeTaxOnHpFormatted()}</td>    
    <td {$emailTdInlineCss} class="number {$unPaidCssClass}{$cssClassDto->commission}">{$itemView->makeCommissionFormatted()}</td>      
    <td {$emailTdInlineCss} class="number {$unPaidCssClass}{$cssClassDto->taxCommission}">{$itemView->makeTaxOnCommissionFormatted()}</td>        
    <td {$emailTdInlineCss} class="number {$unPaidCssClass}{$cssClassDto->fee}">{$itemView->makeFeeFormatted()}</td>
    <td {$emailTdInlineCss} class="number {$unPaidCssClass}{$cssClassDto->subTotal}">{$itemView->makeSubtotalFormatted()}</td>
</tr>
HTML;
            $lotItemCustomFieldsRowData = '';
            if (
                $isRenderLotItemCustomFields
                && $isCustomFieldInSeparateRow
            ) {
                $lotCustomFieldValues = $itemView->makeLotCustomFieldValueList();
                foreach ($lotCustomFieldValues as $lotCustomFieldValue) {
                    if ($lotCustomFieldValue->value) {
                        $lotItemCustomFieldsRowData .= <<<HTML
            <div class="{$lotCustomFieldValue->cssClassName}">
<span class="label">{$lotCustomFieldValue->fieldName}</span>
<span class="separator">:</span>
<span class="value">{$lotCustomFieldValue->value}</span>
</div>
HTML;
                    }
                }

                if ($lotItemCustomFieldsRowData) {
                    $colspan = self::COLSPAN_SINGLE_ROW;
                    $html .= <<<HTML
<tr {$rowClass}>
    <td colspan ="{$colspan}">{$lotItemCustomFieldsRowData} </td>
</tr>
HTML;
                }
            }
            $rowIndex++;
        }
        return $html;
    }

    /**
     * @param LotItemCustomFieldValueDto[] $lotCustomFieldValueDtos
     * @param string $unPaidCssClass
     * @param string $emailTdInlineCss
     * @return string
     */
    protected function renderCustomFieldColumns(
        array $lotCustomFieldValueDtos,
        string $unPaidCssClass,
        string $emailTdInlineCss
    ): string {
        $output = '';
        foreach ($lotCustomFieldValueDtos as $lotCustomFieldValue) {
            $columnCssClass = trim("{$lotCustomFieldValue->cssClassName}{$unPaidCssClass}");
            $output .= <<<HTML
<td {$emailTdInlineCss} class="{$columnCssClass}">{$lotCustomFieldValue->value}</td>
HTML;
        }
        return $output;
    }

    /**
     * @param bool $isQuantityInSettlement
     * @param SettlementItemView $itemView
     * @param string $columnCssClass
     * @param string $unPaidCssClass
     * @param string $emailTdInlineCss
     * @return string
     */
    protected function renderQuantityColumn(
        bool $isQuantityInSettlement,
        SettlementItemView $itemView,
        string $columnCssClass,
        string $unPaidCssClass,
        string $emailTdInlineCss
    ): string {
        if (!$isQuantityInSettlement) {
            return '';
        }

        $quantity = $itemView->makeQuantity();
        $output = <<<HTML
<td {$emailTdInlineCss} class="{$unPaidCssClass}{$columnCssClass}">{$quantity}</td>
HTML;
        return $output;
    }
}
