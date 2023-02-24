<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Base\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidationResult as Result;

/**
 * Class ImportCsvHeaderValidator
 * @package Sam\Import\Csv\Base\Validate
 */
class ImportCsvHeaderValidator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate CSV columns headers.
     * All columns should be unique, have a mapping and all required columns should exist.
     *
     * @param array $csvHeader
     * @param array $knownColumnNames
     * @param array $requiredColumns
     * @return ImportCsvHeaderValidationResult
     */
    public function validate(array $csvHeader, array $knownColumnNames, array $requiredColumns = []): Result
    {
        $result = Result::new()->construct();
        $result = $this->validateUniqueness($csvHeader, $result);
        $result = $this->validateMapping($csvHeader, $knownColumnNames, $result);
        $result = $this->validateRequiredColumns($csvHeader, $requiredColumns, $result);
        return $result;
    }

    protected function validateUniqueness(array $csvHeader, Result $result): Result
    {
        $duplicates = array_keys(
            array_filter(
                array_count_values($csvHeader),
                static function (int $value): bool {
                    return $value > 1;
                }
            )
        );
        if ($duplicates) {
            $result->addError(Result::ERR_COLUMN_NOT_UNIQUE, implode(', ', $duplicates));
        }
        return $result;
    }

    protected function validateMapping(array $csvHeader, array $knownColumnNames, Result $result): Result
    {
        if (!$csvHeader) {
            $result->addError(Result::ERR_COLUMN_CAN_NOT_BE_MAPPED, '');
            return $result;
        }

        foreach ($csvHeader as $columnName) {
            if (!in_array($columnName, $knownColumnNames, true)) {
                $result->addError(Result::ERR_COLUMN_CAN_NOT_BE_MAPPED, $columnName);
            }
        }
        return $result;
    }

    protected function validateRequiredColumns(array $csvHeader, array $requiredColumns, Result $result): Result
    {
        foreach ($requiredColumns as $requiredColumnName) {
            if (!in_array($requiredColumnName, $csvHeader, true)) {
                $result->addError(Result::ERR_ABSENT_REQUIRED_COLUMN, $requiredColumnName);
            }
        }
        return $result;
    }
}
