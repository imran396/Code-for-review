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

namespace Sam\Import\Csv\User;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Dto\UserMakerConfigDto;
use Sam\EntityMaker\User\Dto\UserMakerInputDto;
use Sam\EntityMaker\User\Validate\UserMakerValidatorCreateTrait;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidationResultTranslatorCreateTrait;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidatorCreateTrait;
use Sam\Import\Csv\User\Internal\Validate\HeaderValidationHelperCreateTrait;

/**
 * Class UserImportCsvValidator
 * @package Sam\User\Import
 */
class UserImportCsvValidator extends CustomizableClass
{
    use HeaderValidationHelperCreateTrait;
    use ImportCsvHeaderValidationResultTranslatorCreateTrait;
    use ImportCsvHeaderValidatorCreateTrait;
    use UserMakerValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validateHeader(array $csvHeader, int $accountId, array $userCustomFields): ImportCsvValidationResult
    {
        $result = ImportCsvValidationResult::new();

        $headerValidationHelper = $this->createHeaderValidationHelper();
        $availableColumns = $headerValidationHelper->detectAvailableColumns($accountId, $userCustomFields);
        $requiredColumns = $headerValidationHelper->detectRequiredColumns();
        $headerValidationResult = $this->createImportCsvHeaderValidator()->validate($csvHeader, $availableColumns, $requiredColumns);
        if ($headerValidationResult->hasError()) {
            $translator = $this->createImportCsvHeaderValidationResultTranslator();
            $errorMessages = array_map([$translator, 'trans'], $headerValidationResult->getErrorStatuses());
            $result->addGeneralErrors($errorMessages);
        }
        return $result;
    }

    public function validateRow(UserMakerInputDto $userInputDto, UserMakerConfigDto $userConfigDto, int $rowIndex): ImportCsvValidationResult
    {
        $result = ImportCsvValidationResult::new();
        $validator = $this->createUserMakerValidator()->construct($userInputDto, $userConfigDto);
        if (!$validator->validate()) {
            $result->addRowErrors($rowIndex, $validator->getErrorMessages());
        }
        return $result;
    }
}
