<?php
/**
 * SAM-8007: Invoice and settlement layout adjustments for custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\CustomField\Lot;

use LotItemCustField;
use Sam\Application\Url\Build\Config\Barcode\BarcodeUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\CustomField\Base\Render\Css\CustomFieldCssClassMakerCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Renderer
 * @package ${NAMESPACE}
 */
class InvoiceLotCustomFieldRenderer extends CustomizableClass
{
    use CustomFieldCssClassMakerCreateTrait;
    use DateHelperAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldsAwareTrait;
    use NumberFormatterAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    public function renderCustomFieldsHeader(): string
    {
        $lotCustomFieldHeader = '';
        foreach ($this->getLotCustomFields() as $lotCustomField) {
            $lotCustomFieldName = ee($lotCustomField->Name);
            $lotCustomFieldHeader .= <<<HTML
<th data-hide="phone,tablet" class="item-cust-field">{$lotCustomFieldName}</th>
HTML;
        }
        return $lotCustomFieldHeader;
    }

    public function renderCustomFieldsColumn(int $lotItemId): string
    {
        $customFieldColumn = '';
        foreach ($this->getLotCustomFields() as $lotCustomField) {
            $custData = $this->getLotCustomFieldData($lotCustomField, $lotItemId);
            $customFieldColumn .= <<<HTML
          <td class="item-cust-field">{$custData}</td>
          HTML;
        }
        return $customFieldColumn;
    }

    public function renderCustomFieldsDataInSingleRow(int $lotItemId): string
    {
        $lotItemCustomFieldsRowData = '';
        foreach ($this->getLotCustomFields() as $lotCustomField) {
            $cssClassName = $this->createCustomFieldCssClassMaker()->makeCssClassByLotItemCustomField($lotCustomField);
            $customData = $this->getLotCustomFieldData($lotCustomField, $lotItemId);
            $lotCustomFieldName = ee($lotCustomField->Name);
            if ($customData) {
                $lotItemCustomFieldsRowData .= <<<HTML
<div class="{$cssClassName}">
    <span class="label">{$lotCustomFieldName}</span><span class="separator">:</span> <span class="value">{$customData}</span>
</div>
HTML;
            }
        }
        return $lotItemCustomFieldsRowData;
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @param int $lotItemId
     * @return string|int|null
     */
    protected function getLotCustomFieldData(LotItemCustField $lotCustomField, int $lotItemId): string|int|null
    {
        $customFieldData = '';
        $lotCustomData = $this->createLotCustomDataLoader()->load($lotCustomField->Id, $lotItemId, true);
        if (
            $lotCustomData
            && $lotCustomField->isNumeric()
        ) {
            if ($lotCustomField->Type === Constants\CustomField::TYPE_DATE) {
                $dateSys = $this->getDateHelper()->convertUtcToSysByTimestamp($lotCustomData->Numeric);
                $customFieldData = $this->getDateHelper()->formattedDate($dateSys);
            } elseif ($lotCustomField->Type === Constants\CustomField::TYPE_DECIMAL) {
                $precision = (int)$lotCustomField->Parameters;
                $value = $lotCustomData->calcDecimalValue($precision);
                $customFieldData = $this->getNumberFormatter()->format($value, $precision);
            } else {
                $customFieldData = $lotCustomData->Numeric;
            }
        } elseif ($lotCustomData) { //text
            if (
                $lotCustomField->Type === Constants\CustomField::TYPE_TEXT
                && $lotCustomField->Barcode
                && $lotCustomData->Text !== ''
            ) { // Barcode
                $url = $this->getUrlBuilder()->build(
                    BarcodeUrlConfig::new()->forWeb($lotCustomData->Text, $lotCustomField->BarcodeType)
                );
                $customFieldData = '<img src="' . $url . '" alt="' . ee($lotCustomData->Text) . '" title="" />';
            } else {
                $customFieldData = ee($lotCustomData->Text);
            }
        }
        return $customFieldData;
    }
}
