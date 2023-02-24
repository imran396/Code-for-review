<?php
/**
 * SAM-3796: Bidder upload into auction
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Validate\UserMakerValidatorCreateTrait;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidationResultTranslatorCreateTrait;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidatorCreateTrait;
use Sam\Import\Csv\Bidder\Internal\Dto\RowInput;
use Sam\Import\Csv\Bidder\Internal\Validate\Internal\BidderValidatorCreateTrait;
use Sam\Import\Csv\Bidder\Internal\Validate\Internal\Translate\BidderValidationResultTranslatorCreateTrait;

/**
 * Class Validator
 * @package Sam\Import\Csv\Bidder\Internal\Validate
 * @internal
 */
class Validator extends CustomizableClass
{
    use BidderValidationResultTranslatorCreateTrait;
    use BidderValidatorCreateTrait;
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

    public function validateHeader(array $csvHeader, array $columnNames): ImportCsvValidationResult
    {
        $result = ImportCsvValidationResult::new();
        $headerValidationResult = $this->createImportCsvHeaderValidator()->validate($csvHeader, $columnNames);
        if ($headerValidationResult->hasError()) {
            $translator = $this->createImportCsvHeaderValidationResultTranslator();
            $errorMessages = array_map([$translator, 'trans'], $headerValidationResult->getErrorStatuses());
            $result->addGeneralErrors($errorMessages);
        }
        return $result;
    }

    /**
     * Validate CSV row
     *
     * @param RowInput $rowInput
     * @param int $rowIndex
     * @param array $columnNames
     * @param int $auctionId
     * @param int $syncMode
     * @param string $encoding
     * @return ImportCsvValidationResult
     */
    public function validateRow(
        RowInput $rowInput,
        int $rowIndex,
        array $columnNames,
        int $auctionId,
        int $syncMode,
        string $encoding
    ): ImportCsvValidationResult {
        $result = ImportCsvValidationResult::new();

        $userMakerValidator = $this->createUserMakerValidator()
            ->enableCustomFieldValidation(false)
            ->construct($rowInput->userInputDto, $rowInput->userConfigDto);
        $isUserValid = $userMakerValidator->validate();
        $userMakerValidator->resetCustomFieldsErrors();
        if (
            !$isUserValid
            && $userMakerValidator->getErrors()
        ) {
            $result->addRowErrors($rowIndex, $userMakerValidator->getErrorMessages());
        }

        $bidderValidationResult = $this->createBidderValidator()->validate($rowInput, $auctionId, $syncMode, $encoding);
        if ($bidderValidationResult->hasError()) {
            $bidderValidationResultTranslator = $this->createBidderValidationResultTranslator()->construct($columnNames);
            $errorMessages = array_map(
                [$bidderValidationResultTranslator, 'trans'],
                $bidderValidationResult->getErrorStatuses()
            );
            $result->addRowErrors($rowIndex, $errorMessages);
        }

        return $result;
    }
}
