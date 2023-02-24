<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\Internal\Validate\Header;

use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Csv\CustomFieldCsvHelperCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class LotItemImportCsvHeaderValidationHelper
 * @package Sam\Import\Csv\Lot\LotItem\Validate
 */
class LotItemImportCsvHeaderValidationHelper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
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
     * Lot name is always required
     *
     * @param array $columnHeaders
     * @return array
     */
    public function detectRequiredColumns(array $columnHeaders): array
    {
        return [$columnHeaders[Constants\Csv\Lot::LOT_NAME]];
    }

    /**
     * The available columns may vary depending on the item number format config option
     * as well as the list of custom lot fields
     *
     * @param LotItemCustField[] $lotCustomFields
     * @param array $columnHeaders
     * @return array
     */
    public function detectAvailableColumns(array $lotCustomFields, array $columnHeaders): array
    {
        $excludedColumns = [];
        if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
            $excludedColumns[Constants\Csv\Lot::ITEM_NUM] = null;
            $excludedColumns[Constants\Csv\Lot::ITEM_NUM_EXT] = null;
        } else {
            $excludedColumns[Constants\Csv\Lot::ITEM_FULL_NUMBER] = null;
        }
        $customFieldCsvHelper = $this->createCustomFieldCsvHelper();
        foreach ($lotCustomFields as $lotCustomField) {
            $columnHeaders[] = $customFieldCsvHelper->makeCustomFieldColumnName($lotCustomField);
        }
        return array_diff_key($columnHeaders, $excludedColumns);
    }
}
