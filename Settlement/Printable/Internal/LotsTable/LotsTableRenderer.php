<?php
/**
 * SAM-7661: Settlement Printable & viewable version to the new layout Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           03-02, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Printable\Internal\LotsTable;

use LotItemCustField;
use Sam\Core\Constants;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settlement\Printable\Internal\Constants\InternalConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Render\Css\CustomFieldCssClassMakerCreateTrait;
use Sam\Settlement\Load\Dto\SettlementItemDto;
use Sam\Settlement\Printable\Internal\Translation\SettlementTranslatorCreateTrait;
use Settlement;

/**
 * Renders HTML table with settlement lots.
 *
 * Class LotsTableRenderer
 * @package Sam\Settlement\Printable
 */
class LotsTableRenderer extends CustomizableClass
{
    use CustomFieldCssClassMakerCreateTrait;
    use LotsTableItemRendererCreateTrait;
    use SettlementTranslatorCreateTrait;
    use SettingsManagerAwareTrait;

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

    /**
     * @param int $settlementAccountId
     * @param SettlementItemDto[] $settlementItemDtos
     * @param LotItemCustField[] $lotCustomFields
     * @param bool $isQuantityInSettlement
     * @param string $currencySign
     * @param bool $isUseTranslatableLabels
     * @param bool $isRenderLotItemCustomFields
     * @param bool $isInlineCss
     * @return string
     */
    public function render(
        int $settlementAccountId,
        array $settlementItemDtos,
        array $lotCustomFields,
        bool $isQuantityInSettlement,
        string $currencySign,
        bool $isUseTranslatableLabels = false,
        bool $isRenderLotItemCustomFields = true,
        bool $isInlineCss = false
    ): string {
        $tableHeaderTranslations = $this->createSettlementTranslator()->getTranslatedHeaders($isUseTranslatableLabels, $isQuantityInSettlement);
        $isCustomFieldInSeparateRow = $this->getSettingsManager()->get(Constants\Setting::RENDER_LOT_CUSTOM_FIELDS_IN_SEPARATE_ROW_FOR_SETTLEMENT, $settlementAccountId);
        $cssClassDto = LotsTableCssClassDto::new();
        $lotCustomFieldHeaders = '';
        if (
            $isRenderLotItemCustomFields
            && !$isCustomFieldInSeparateRow
        ) {
            $lotCustomFieldHeaders = $this->renderHeadersForLotCustomField($lotCustomFields);
        }
        $quantityHeader = $this->renderHeaderForQuantity(
            $isQuantityInSettlement,
            $tableHeaderTranslations->quantityHeader,
            $cssClassDto->quantity
        );

        $isHideLotNumAndSaleNameColumns = $this->isHideLotNumAndSaleNameColumns();
        [$lotNumStyle, $saleNameStyle] = $this->detectLotNumAndSaleNameStyles($isHideLotNumAndSaleNameColumns);
        $settlementItems = $this->createLotsTableItemRenderer()
            ->render(
                $settlementItemDtos,
                $settlementAccountId,
                $currencySign,
                $isQuantityInSettlement,
                $isHideLotNumAndSaleNameColumns,
                $isRenderLotItemCustomFields,
                $isInlineCss,
                $isCustomFieldInSeparateRow
            );
        $controlId = InternalConstants::CID_BLK_DATA_GRID;

        /**
         * YV:
         * We should find way how to sync rendered table headers with data inside these columns.
         * Currently, we render columns headers here manually,
         * and render columns with data manually too
         * in @see \Sam\Settlement\Printable\Internal\LotsTable\LotsTableItemRenderer::render
         **/
        $html = <<<HTML
<table class="footable foolarge invoice-datagrid " id="{$controlId}" cellpadding="0" cellspacing="0" border="0">
    <thead>
      <tr>
        <th class="{$cssClassDto->lotNum}" {$lotNumStyle}>{$tableHeaderTranslations->itemNumHeader}</th>
        <th class="{$cssClassDto->saleLotNumSaleNum}" data-hide="phone,tablet">{$tableHeaderTranslations->saleHeader}/{$tableHeaderTranslations->lotHeader}</th>
        <th class="{$cssClassDto->estimates}">{$tableHeaderTranslations->estimateHeader}</th>
        <th class="{$cssClassDto->lotName}" data-hide="phone,tablet">{$tableHeaderTranslations->itemNameHeader}</th>
        {$lotCustomFieldHeaders}
        {$quantityHeader}
        <th class="{$cssClassDto->saleName}" {$saleNameStyle}>{$tableHeaderTranslations->saleNameHeader}</th>
        <th class="{$cssClassDto->hammerPrice}" data-hide="phone,tablet">{$tableHeaderTranslations->hammerHeader}</th>
        <th class="{$cssClassDto->taxHp}" data-hide="phone,tablet">{$tableHeaderTranslations->taxOnHPHeader}</th>
        <th class="{$cssClassDto->commission}" data-hide="phone,tablet">{$tableHeaderTranslations->commissionHeader}</th>   
        <th class="{$cssClassDto->taxCommission}" data-hide="phone,tablet">{$tableHeaderTranslations->taxOnCommHeader}</th>
        <th class="{$cssClassDto->fee}" data-hide="phone,tablet">{$tableHeaderTranslations->feeHeader}</th>
        <th class="{$cssClassDto->subTotal}">{$tableHeaderTranslations->subtotalHeader}</th>
      </tr>
    </thead>
    <tbody>
        {$settlementItems}
    </tbody>
</table>

HTML;

        return $html;
    }

    protected function isHideLotNumAndSaleNameColumns(): bool
    {
        $isHide = $this->settlement->AuctionId ? true : false;
        return $isHide;
    }

    protected function detectLotNumAndSaleNameStyles(bool $isHideLotNumAndSaleNameColumns): array
    {
        $lotNumStyle = '';
        $saleNameStyle = ' style="width:110px"';
        if ($isHideLotNumAndSaleNameColumns) {
            $lotNumStyle = $saleNameStyle = ' style="display:none;"';
        }
        return [$lotNumStyle, $saleNameStyle];
    }

    /**
     * @param LotItemCustField[] $lotCustomFields
     * @return string
     */
    protected function renderHeadersForLotCustomField(array $lotCustomFields): string
    {
        $output = '';
        $customFieldCssClassMaker = $this->createCustomFieldCssClassMaker();
        foreach ($lotCustomFields as $lotCustomField) {
            $lotCustomFieldName = ee($lotCustomField->Name);
            $cssClassName = $customFieldCssClassMaker->makeCssClassByLotItemCustomField($lotCustomField);
            $output .= <<<HTML
                <th class="{$cssClassName}" data-hide="phone,tablet">{$lotCustomFieldName}</th>
HTML;
        }
        return $output;
    }

    /**
     * @param bool $isQuantityInSettlement
     * @param string $label
     * @param string $columnsCssClass
     * @return string
     */
    protected function renderHeaderForQuantity(bool $isQuantityInSettlement, string $label, string $columnsCssClass): string
    {
        $output = '';
        if ($isQuantityInSettlement) {
            $output = <<<HTML
        <th class="{$columnsCssClass}" data-hide="phone,tablet">$label</th>
HTML;
        }
        return $output;
    }
}
