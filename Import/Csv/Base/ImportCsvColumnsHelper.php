<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Base;

use LotItemCustField;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Csv\CustomFieldCsvHelperCreateTrait;
use Sam\Import\Csv\Read\CsvRow;
use UserCustField;

/**
 * Contains helper methods for working with CSV columns. Used in the data import process
 *
 * Class ImportCsvColumnsHelper
 * @package Sam\Import\Csv\Base
 */
class ImportCsvColumnsHelper extends CustomizableClass
{
    use CustomFieldCsvHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Finding the columns existed in the file. Used to clear empty fields.
     * If clearing empty fields is disabled, an empty array returned an empty array to avoid
     * unnecessary calculations that will not be used in the future.
     *
     * @param CsvRow $row
     * @param UserCustField[]|LotItemCustField[] $customFields
     * @param bool $clearEmptyFields
     * @return array
     */
    public function detectPresentedCsvColumns(CsvRow $row, array $customFields, bool $clearEmptyFields): array
    {
        if (!$clearEmptyFields) {
            return [];
        }

        $presentedColumns = $row->getColumnNames();
        $presentedCustomFields = $this->createCustomFieldCsvHelper()->detectPresentedCustomFields($row, $customFields);
        foreach ($presentedCustomFields as $presentedCustomField) {
            $index = array_search($presentedCustomField['columnName'], $presentedColumns, true);
            $presentedColumns[$index] = $presentedCustomField['key'];
        }
        return $presentedColumns;
    }
}
