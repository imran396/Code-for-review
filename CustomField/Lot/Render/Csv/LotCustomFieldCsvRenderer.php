<?php
/**
 * SAM-4815: Lot Custom Field renderer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-02-07
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Render\Csv;

use LotItemCustData;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\CustomField\Base\Render\Csv\CustomFieldCsvRendererBase;
use Sam\CustomField\Lot\Help\LotCustomFieldHelperCreateTrait;

/**
 * Class LotCustomFieldCsvRenderer
 */
class LotCustomFieldCsvRenderer extends CustomFieldCsvRendererBase
{
    use LotCustomFieldHelperCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @param LotItemCustData $lotCustomData
     * @return string
     */
    public function render(LotItemCustField $lotCustomField, LotItemCustData $lotCustomData): string
    {
        $renderMethod = $this->createLotCustomFieldHelper()->makeCustomMethodName($lotCustomField->Name, 'Render');
        if (method_exists($this, $renderMethod)) {
            $output = (string)$this->$renderMethod($lotCustomField, $lotCustomData);
        } else {
            $value = $lotCustomField->isNumeric()
                ? $lotCustomData->Numeric : Cast::toInt($lotCustomData->Text, Constants\Type::F_INT_POSITIVE);
            $output = $this->renderByValue($lotCustomField->Type, $value, $lotCustomField->Parameters);
        }
        return $output;
    }
}
