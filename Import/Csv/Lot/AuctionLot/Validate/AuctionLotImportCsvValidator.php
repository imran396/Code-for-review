<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\AuctionLot\Validate;

use Auction;
use LotItemCustField;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Lock\AuctionLotMakerLocker;
use Sam\EntityMaker\AuctionLot\Validate\AuctionLotMakerValidator;
use Sam\EntityMaker\LotItem\Lock\LotItemMakerLocker;
use Sam\EntityMaker\LotItem\Validate\LotItemMakerValidator;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidationResultTranslatorCreateTrait;
use Sam\Import\Csv\Base\Validate\ImportCsvHeaderValidatorCreateTrait;
use Sam\Import\Csv\Lot\AuctionLot\Internal\Dto\Row;
use Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Translate\ItemNoLotNoUniquenessValidationResultTranslatorCreateTrait;
use Sam\Import\Csv\Lot\AuctionLot\Validate\Internal\Unique\ItemNoLotNoUniquenessValidatorCreateTrait;
use Sam\Import\Csv\Lot\Internal\Validate\Header\AuctionLotImportCsvHeaderValidationHelperCreateTrait;
use Sam\Import\Csv\Lot\Internal\Validate\LotOverwriting\LotOverwritingValidatorCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class AuctionLotImportCsvValidator
 * @package Sam\Import\Csv\Lot\AuctionLot
 */
class AuctionLotImportCsvValidator extends CustomizableClass
{
    use AuctionLotImportCsvHeaderValidationHelperCreateTrait;
    use ConfigRepositoryAwareTrait;
    use ImportCsvHeaderValidationResultTranslatorCreateTrait;
    use ImportCsvHeaderValidatorCreateTrait;
    use ItemNoLotNoUniquenessValidationResultTranslatorCreateTrait;
    use ItemNoLotNoUniquenessValidatorCreateTrait;
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
     * @param LotItemCustField[] $lotCustomFields
     * @param array $columnHeaders
     * @param Auction $auction
     * @return ImportCsvValidationResult
     */
    public function validateHeader(
        array $csvHeader,
        array $lotCustomFields,
        array $columnHeaders,
        Auction $auction
    ): ImportCsvValidationResult {
        $result = ImportCsvValidationResult::new();
        $headerValidationHelper = $this->createAuctionLotImportCsvHeaderValidationHelper();
        $availableColumns = $headerValidationHelper->detectAvailableColumns($lotCustomFields, $columnHeaders);
        $requiredColumns = $headerValidationHelper->detectRequiredColumns($columnHeaders, $auction);
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
     * Check if lot No and item No are not related to another lot
     *
     * @param Row[] $rows
     * @param int|null $auctionId null means we import csv when creating new auction
     * @return ImportCsvValidationResult
     */
    public function validateItemNoLotNoUniqueness(array $rows, ?int $auctionId): ImportCsvValidationResult
    {
        $result = ImportCsvValidationResult::new();
        $validationResult = $this->createItemNoLotNoUniquenessValidator()->validate($rows, $auctionId);
        if ($validationResult->hasError()) {
            $translator = $this->createItemNoLotNoUniquenessValidationResultTranslator();
            $errorMessages = array_map([$translator, 'trans'], $validationResult->getErrorStatuses());
            $result->addGeneralErrors($errorMessages);
        }
        return $result;
    }

    /**
     * Validate all CSV rows
     *
     * @param Row[] $rows
     * @param bool $isLotItemOverwriting
     * @param bool $isAuctionLotOverwriting
     * @return ImportCsvValidationResult
     */
    public function validateRows(array $rows, bool $isLotItemOverwriting, bool $isAuctionLotOverwriting): ImportCsvValidationResult
    {
        $result = ImportCsvValidationResult::new();

        $lotOverwritingValidator = $this->createLotOverwritingValidator();
        foreach ($rows as $rowIndex => $row) {
            $lockingResult = LotItemMakerLocker::new()->lock($row->lotItemInputDto, $row->lotItemConfigDto); // #li-lock-5
            if (!$lockingResult->isSuccess()) {
                $result->addRowErrors($rowIndex, [$lockingResult->message()]);
                continue;
            }
            $lockingResult = AuctionLotMakerLocker::new()->lock($row->auctionLotInputDto, $row->auctionLotConfigDto); // #ali-lock-5
            if (!$lockingResult->isSuccess()) {
                $result->addRowErrors($rowIndex, [$lockingResult->message()]);
                continue;
            }

            if (!$isLotItemOverwriting) {
                $lotItemOverwritingResult = $lotOverwritingValidator->validateLotItem(
                    $row->lotItemIdDetectionResult,
                    $row->lotItemInputDto
                );
                if ($lotItemOverwritingResult->hasError()) {
                    $result->addRowErrors($rowIndex, $lotItemOverwritingResult->getErrorMessages());
                }
            }
            if (!$isAuctionLotOverwriting) {
                $auctionLotOverwritingResult = $lotOverwritingValidator->validateAuctionLot(
                    $row->lotItemIdDetectionResult,
                    $row->auctionLotInputDto
                );
                if ($auctionLotOverwritingResult->hasError()) {
                    $result->addRowErrors($rowIndex, $auctionLotOverwritingResult->getErrorMessages());
                }
            }
            $lotItemValidator = LotItemMakerValidator::new()
                ->construct($row->lotItemInputDto, $row->lotItemConfigDto);
            if (!$lotItemValidator->validate()) {
                LotItemMakerLocker::new()->unlock($row->lotItemConfigDto); // #li-lock-5, unlock after failed validation
                $result->addRowErrors($rowIndex, $lotItemValidator->getErrorMessages());
            }

            $auctionLotValidator = AuctionLotMakerValidator::new()
                ->construct($row->auctionLotInputDto, $row->auctionLotConfigDto);
            if (!$auctionLotValidator->validate()) {
                AuctionLotMakerLocker::new()->unlock($row->auctionLotConfigDto); // #ali-lock-5, unlock after failed validation
                $result->addRowErrors($rowIndex, $auctionLotValidator->getErrorMessages());
            }
        }
        return $result;
    }
}
