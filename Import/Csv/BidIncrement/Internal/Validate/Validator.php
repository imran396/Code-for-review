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

namespace Sam\Import\Csv\BidIncrement\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidationResultTranslatorCreateTrait;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidatorCreateTrait;
use Sam\Import\Csv\BidIncrement\Internal\Dto\RowInput;
use Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\RangesValidatorCreateTrait;
use Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\RowValidatorCreateTrait;
use Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\Translate\RangesValidationResultTranslatorCreateTrait;
use Sam\Import\Csv\BidIncrement\Internal\Validate\Internal\Translate\RowValidationResultTranslatorCreateTrait;

/**
 * Class BidIncrementImportCsvValidator
 * @package Sam\Import\Csv\BidIncrement\Internal\Validate
 */
class Validator extends CustomizableClass
{
    use ImportCsvHeaderValidationResultTranslatorCreateTrait;
    use ImportCsvHeaderValidatorCreateTrait;
    use RangesValidationResultTranslatorCreateTrait;
    use RangesValidatorCreateTrait;
    use RowValidationResultTranslatorCreateTrait;
    use RowValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate a CSV column names and all rows with bid increments
     *
     * @param RowInput[] $dtos
     * @param array $csvHeader
     * @param array $columnHeaders
     * @param string $auctionType
     * @param int $systemAccountId
     * @return ImportCsvValidationResult
     */
    public function validate(
        array $dtos,
        array $csvHeader,
        array $columnHeaders,
        string $auctionType,
        int $systemAccountId
    ): ImportCsvValidationResult {
        $result = ImportCsvValidationResult::new();
        $headerValidationResult = $this->createImportCsvHeaderValidator()->validate($csvHeader, $columnHeaders, $columnHeaders);
        if ($headerValidationResult->hasError()) {
            $translator = $this->createImportCsvHeaderValidationResultTranslator();
            $errorMessages = array_map([$translator, 'trans'], $headerValidationResult->getErrorStatuses());
            $result->addGeneralErrors($errorMessages);
            return $result;
        }
        $rangesValidationResult = $this->createRangesValidator()->validate($dtos, $systemAccountId);
        if ($rangesValidationResult->hasError()) {
            $translator = $this->createRangesValidationResultTranslator();
            $errorMessages = array_map([$translator, 'trans'], $rangesValidationResult->getErrorStatuses());
            $result->addGeneralErrors($errorMessages);
        }
        $rowValidator = $this->createRowValidator();
        $translator = $this->createRowValidationResultTranslator()->construct($columnHeaders);
        foreach ($dtos as $rowIndex => $dto) {
            $rowValidationResult = $rowValidator->validate($dto, $auctionType, $systemAccountId);
            if ($rowValidationResult->hasError()) {
                $errorMessages = array_map([$translator, 'trans'], $rowValidationResult->getErrorStatuses());
                $result->addRowErrors($rowIndex, $errorMessages);
            }
        }
        return $result;
    }
}
