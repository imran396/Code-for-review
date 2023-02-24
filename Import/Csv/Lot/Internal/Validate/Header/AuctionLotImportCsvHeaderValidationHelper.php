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

use Auction;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class AuctionLotImportCsvHeaderValidationHelper
 * @package Sam\Import\Csv\Lot\Internal\Validate\Header
 */
class AuctionLotImportCsvHeaderValidationHelper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LotItemImportCsvHeaderValidationHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Lot name is always required.
     * For timed auction lot, the starting bid column is required
     *
     * @param array $columnHeaders
     * @param Auction $auction
     * @return array
     */
    public function detectRequiredColumns(array $columnHeaders, Auction $auction): array
    {
        $requiredColumns = $this->createLotItemImportCsvHeaderValidationHelper()->detectRequiredColumns($columnHeaders);
        if ($auction->isTimed()) {
            $requiredColumns[] = $columnHeaders[Constants\Csv\Lot::STARTING_BID];
        }
        return $requiredColumns;
    }

    /**
     * The available columns may vary depending on the lot and item number format config option
     * as well as the list of custom lot fields
     *
     * @param LotItemCustField[] $lotCustomFields
     * @param array $columnHeaders
     * @return array
     */
    public function detectAvailableColumns(array $lotCustomFields, array $columnHeaders): array
    {
        $availableColumns = $this->createLotItemImportCsvHeaderValidationHelper()->detectAvailableColumns($lotCustomFields, $columnHeaders);

        $excludedColumns = [];
        if ($this->cfg()->get('core->lot->lotNo->concatenated')) {
            $excludedColumns[Constants\Csv\Lot::LOT_NUM] = null;
            $excludedColumns[Constants\Csv\Lot::LOT_NUM_EXT] = null;
            $excludedColumns[Constants\Csv\Lot::LOT_NUM_PREFIX] = null;
        } else {
            $excludedColumns[Constants\Csv\Lot::LOT_FULL_NUMBER] = null;
        }

        return array_diff_key($availableColumns, $excludedColumns);
    }
}
