<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Validate\UserMakerValidatorCreateTrait;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidationResultTranslatorCreateTrait;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidatorCreateTrait;
use Sam\Import\Csv\PostAuction\Internal\Dto\RowInput;
use Sam\Import\Csv\PostAuction\Internal\Validate\Internal\Translate\RowValidationResultTranslatorCreateTrait;
use Sam\Import\Csv\PostAuction\Internal\Validate\Internal\ValidateRow\RowValidatorCreateTrait;

/**
 * Class ContentValidator
 */
class ContentValidator extends CustomizableClass
{
    use ImportCsvHeaderValidationResultTranslatorCreateTrait;
    use ImportCsvHeaderValidatorCreateTrait;
    use RowValidationResultTranslatorCreateTrait;
    use RowValidatorCreateTrait;
    use UserMakerValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate all rows content
     *
     * @param RowInput[] $dtos
     * @param array $csvHeader
     * @param array $columnHeaders
     * @return ImportCsvValidationResult
     */
    public function validateHeaderAndRows(iterable $dtos, array $csvHeader, array $columnHeaders): ImportCsvValidationResult
    {
        $result = ImportCsvValidationResult::new();
        $headerValidationResult = $this->createImportCsvHeaderValidator()->validate($csvHeader, $columnHeaders);
        if ($headerValidationResult->hasError()) {
            $translator = $this->createImportCsvHeaderValidationResultTranslator();
            $errorMessages = array_map([$translator, 'trans'], $headerValidationResult->getErrorStatuses());
            $result->addGeneralErrors($errorMessages);
            return $result;
        }

        $rowValidator = $this->createRowValidator();
        $rowValidationResultTranslator = $this->createRowValidationResultTranslator();
        foreach ($dtos as $rowIndex => $dto) {
            $rowValidationResult = $rowValidator->validate($dto);
            if ($rowValidationResult->hasError()) {
                $errorMessages = array_map([$rowValidationResultTranslator, 'trans'], $rowValidationResult->getErrorStatuses());
                $result->addRowErrors($rowIndex, $errorMessages);
            }
        }
        return $result;
    }

    public function validateUser(RowInput $dto, int $rowIndex): ImportCsvValidationResult
    {
        $result = ImportCsvValidationResult::new();
        /**
         * Validate user input only when identifier (email) presents in csv input,
         * otherwise user is not created and not updated.
         */
        if ($dto->userInputDto->email) {
            $userValidator = $this->createUserMakerValidator()
                ->construct($dto->userInputDto, $dto->userConfigDto)
                ->enableCustomFieldValidation(false);
            if (!$userValidator->validate()) {
                $result->addRowErrors($rowIndex, $userValidator->getErrorMessages());
            }
        }
        return $result;
    }
}
