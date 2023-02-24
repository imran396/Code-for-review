<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\Csv;

use LotItemCustField;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\Import\Csv\Read\CsvRow;
use UserCustField;

/**
 * Contains helper methods for working with custom fields in a CSV file
 *
 * Class CustomFieldCsvHelper
 * @package Sam\CustomField\Base\Csv
 */
class CustomFieldCsvHelper extends CustomizableClass
{
    use BaseCustomFieldHelperAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Parse customFields from lot_item_cust_field.name, user_cust_field.name columns
     *
     * @param CsvRow $row
     * @param UserCustField[]|LotItemCustField[] $customFields
     * @return array CustomFields [name, value]
     */
    public function parseCustomFields(CsvRow $row, array $customFields): array
    {
        $customFieldValues = [];
        foreach ($customFields as $customField) {
            $value = $row->getCell($this->makeCustomFieldColumnName($customField));
            $key = $this->makeCustomFieldKey($customField->Name);
            $customFieldValues[$key] = $value;
        }
        return $customFieldValues;
    }

    /**
     * Detect which custom fields are presented in CSV row
     *
     * @param CsvRow $row
     * @param array $customFields All custom fields
     * @return array
     */
    public function detectPresentedCustomFields(CsvRow $row, array $customFields): array
    {
        $presentedCustomFields = [];
        foreach ($customFields as $customField) {
            $columnName = $this->makeCustomFieldColumnName($customField);
            if ($row->hasCell($columnName)) {
                $key = $this->makeCustomFieldKey($customField->Name);
                $presentedCustomFields[] = [
                    'key' => $key,
                    'columnName' => $columnName,
                ];
            }
        }
        return $presentedCustomFields;
    }

    /**
     * Convert a custom field name to a valid identifier that can be used in producers
     *
     * @param string $customFieldName
     * @return string
     */
    public function makeCustomFieldKey(string $customFieldName): string
    {
        return lcfirst($this->getBaseCustomFieldHelper()->makeSoapTagByName($customFieldName));
    }

    /**
     * Make CSV column name based on the custom field name and the required option
     *
     * @param LotItemCustField|UserCustField $customField
     * @return string
     */
    public function makeCustomFieldColumnName(LotItemCustField|UserCustField $customField): string
    {
        if ($customField instanceof LotItemCustField) {
            return $customField->Name;
        }
        return $customField->Name . ($customField->Required ? ' *' : '');
    }
}
