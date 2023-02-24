<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Help;


use LotItemCustField;
use Sam\Core\Constants;
use Sam\CustomField\Base\Help\BaseCustomFieldHelper;

/**
 * Class LotCustomFieldHelper
 * @package Sam\CustomField\Lot\Help
 */
class LotCustomFieldHelper extends BaseCustomFieldHelper
{
    /**
     * Custom Method Prefix
     */
    protected string $customMethodPrefix = 'LotCustomField_';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array of lot custom field types: key: type id, value: type name
     */
    public function getTypeNames(): array
    {
        $typeNames = array_intersect_key(Constants\CustomField::$typeNames, array_flip(Constants\LotCustomField::$availableTypes));
        return $typeNames;
    }

    /**
     * Checks if custom field type is included to lot category custom fields
     *
     * @param LotItemCustField $lotCustomField
     * @return bool
     */
    public function isApplicableForLotCategory(LotItemCustField $lotCustomField): bool
    {
        return in_array($lotCustomField->Type, Constants\LotCustomField::$lotCategoryTypes, true);
    }
}
