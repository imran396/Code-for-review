<?php
/**
 * SAM-10435: Add csv quick upload form to locations page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Location;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Location\Dto\LocationMakerConfigDto;
use Sam\EntityMaker\Location\Dto\LocationMakerInputDto;
use Sam\EntityMaker\Location\Validate\LocationMakerValidator;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidationResultTranslatorCreateTrait;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidatorCreateTrait;
use Sam\Import\Csv\Location\Internal\Validate\HeaderValidationHelperCreateTrait;

/**
 * Class LocationImportCsvValidator
 * @package Sam\Location\Import
 */
class LocationImportCsvValidator extends CustomizableClass
{
    use HeaderValidationHelperCreateTrait;
    use ImportCsvHeaderValidationResultTranslatorCreateTrait;
    use ImportCsvHeaderValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate CSV file column names and all rows
     *
     * @param array $csvHeader
     * @param LocationMakerInputDto[] $locationInputDtos
     * @param LocationMakerConfigDto[] $locationConfigDtos
     * @return ImportCsvValidationResult
     */
    public function validate(
        array $csvHeader,
        array $locationInputDtos,
        array $locationConfigDtos
    ): ImportCsvValidationResult {
        $result = ImportCsvValidationResult::new();

        $headerValidationHelper = $this->createHeaderValidationHelper();
        $availableColumns = $headerValidationHelper->detectAvailableColumns();
        $requiredColumns = $headerValidationHelper->detectRequiredColumns();
        $headerValidationResult = $this->createImportCsvHeaderValidator()->validate($csvHeader, $availableColumns, $requiredColumns);
        if ($headerValidationResult->hasError()) {
            $translator = $this->createImportCsvHeaderValidationResultTranslator();
            $errorMessages = array_map([$translator, 'trans'], $headerValidationResult->getErrorStatuses());
            $result->addGeneralErrors($errorMessages);
            return $result;
        }

        foreach ($locationInputDtos as $rowIndex => $locationInputDto) {
            $validator = LocationMakerValidator::new()->construct($locationInputDto, $locationConfigDtos[$rowIndex]);
            if (!$validator->validate()) {
                $result->addRowErrors($rowIndex, $validator->getErrorMessages());
            }
        }
        return $result;
    }
}
