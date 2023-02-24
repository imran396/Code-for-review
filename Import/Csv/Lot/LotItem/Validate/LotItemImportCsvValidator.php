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

namespace Sam\Import\Csv\Lot\LotItem\Validate;

use LotItemCustField;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Csv\CustomFieldCsvHelperCreateTrait;
use Sam\EntityMaker\LotItem\Lock\LotItemMakerLocker;
use Sam\EntityMaker\LotItem\Validate\LotItemMakerValidator;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidationResultTranslatorCreateTrait;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidatorCreateTrait;
use Sam\Import\Csv\Lot\Internal\Validate\Header\LotItemImportCsvHeaderValidationHelperCreateTrait;
use Sam\Import\Csv\Lot\Internal\Validate\LotOverwriting\LotOverwritingValidatorCreateTrait;
use Sam\Import\Csv\Lot\LotItem\Internal\Dto\Row;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class LotItemImportCsvValidator
 * @package Sam\Import\Csv\Lot\LotItem\Validate
 */
class LotItemImportCsvValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CustomFieldCsvHelperCreateTrait;
    use ImportCsvHeaderValidationResultTranslatorCreateTrait;
    use ImportCsvHeaderValidatorCreateTrait;
    use LotItemImportCsvHeaderValidationHelperCreateTrait;
    use LotOverwritingValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate CSV file column names
     *
     * @param array $csvHeader
     * @param array $columnHeaders
     * @param LotItemCustField[] $lotCustomFields
     * @return ImportCsvValidationResult
     */
    public function validateHeader(
        array $csvHeader,
        array $columnHeaders,
        array $lotCustomFields
    ): ImportCsvValidationResult {
        $result = ImportCsvValidationResult::new();
        $headerValidationHelper = $this->createLotItemImportCsvHeaderValidationHelper();
        $availableColumns = $headerValidationHelper->detectAvailableColumns($lotCustomFields, $columnHeaders);
        $requiredColumns = $headerValidationHelper->detectRequiredColumns($columnHeaders);
        $headerValidationResult = $this->createImportCsvHeaderValidator()->validate($csvHeader, $availableColumns, $requiredColumns);
        if ($headerValidationResult->hasError()) {
            $translator = $this->createImportCsvHeaderValidationResultTranslator();
            $errorMessages = array_map([$translator, 'trans'], $headerValidationResult->getErrorStatuses());
            $result->addGeneralErrors($errorMessages);
            return $result;
        }
        return $result;
    }

    /**
     * Validate all CSV rows
     *
     * @param Row[] $rows
     * @param bool $isOverwriting
     * @return ImportCsvValidationResult
     */
    public function validateRows(array $rows, bool $isOverwriting): ImportCsvValidationResult
    {
        $result = ImportCsvValidationResult::new();
        foreach ($rows as $rowIndex => $row) {
            $lockingResult = LotItemMakerLocker::new()->lock($row->lotItemInputDto, $row->lotItemConfigDto); // #li-lock-4
            if (!$lockingResult->isSuccess()) {
                $result->addRowErrors($rowIndex, [$lockingResult->message()]);
                continue;
            }

            if (!$isOverwriting) {
                $lotOverwritingValidationResult = $this->createLotOverwritingValidator()
                    ->validateLotItem($row->lotItemIdDetectionResult, $row->lotItemInputDto);
                if ($lotOverwritingValidationResult->hasError()) {
                    $result->addRowErrors($rowIndex, $lotOverwritingValidationResult->getErrorMessages());
                }
            }
            $lotItemValidator = LotItemMakerValidator::new()
                ->construct($row->lotItemInputDto, $row->lotItemConfigDto);
            if (!$lotItemValidator->validate()) {
                LotItemMakerLocker::new()->unlock($row->lotItemConfigDto); // #li-lock-4, unlock after failed validation
                $result->addRowErrors($rowIndex, $lotItemValidator->getErrorMessages());
            }
        }
        return $result;
    }
}
