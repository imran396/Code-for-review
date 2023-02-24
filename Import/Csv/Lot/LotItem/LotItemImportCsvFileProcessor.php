<?php
/**
 * SAM-9264: Refactor \Lot_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Lot\LotItem;

use Exception;
use Iterator;
use Sam\Auction\Cache\LotCount\AuctionLotCountCacher;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\EntityMaker\Base\Dto\ValidationStatus;
use Sam\EntityMaker\LotItem\Lock\LotItemMakerLocker;
use Sam\EntityMaker\LotItem\Save\LotItemMakerProducer;
use Sam\Import\Csv\Base\ImportCsvFileProcessorInterface;
use Sam\Import\Csv\Base\ImportCsvProcessResult;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByLotItem\InventoryContextLotItemIdDetectorCreateTrait;
use Sam\Import\Csv\Lot\LotItem\Internal\Dto\DtoFactoryCreateTrait;
use Sam\Import\Csv\Lot\LotItem\Internal\Dto\Row;
use Sam\Import\Csv\Lot\LotItem\Validate\LotItemImportCsvValidatorCreateTrait;
use Sam\Import\Csv\Lot\Statistic\LotImportCsvProcessStatistic;
use Sam\Import\Csv\Read\CsvFileReaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Lock\DbLockerCreateTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * This class is responsible for handling uploaded CSV file with lot items
 *
 * Class LotItemImportCsvFileProcessor
 * @package Sam\Import\Csv\Lot\LotItem
 */
class LotItemImportCsvFileProcessor extends CustomizableClass implements ImportCsvFileProcessorInterface
{
    use AdminTranslatorAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CsvFileReaderAwareTrait;
    use DbConnectionTrait;
    use DbLockerCreateTrait;
    use DtoFactoryCreateTrait;
    use EditorUserAwareTrait;
    use InventoryContextLotItemIdDetectorCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotItemImportCsvValidatorCreateTrait;
    use SystemAccountAwareTrait;

    protected int $editorUserId;
    private bool $overwriting;
    protected bool $shouldReplaceBreaksWithHtml;
    protected bool $clearEmptyFields;
    protected bool $validateAsYouGo;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @param string $sourceFilePath
     * @param string $encoding
     * @param bool $overwriting
     * @param bool $shouldReplaceBreaksWithHtml
     * @param bool $clearEmptyFields
     * @param bool $validateAsYouGo
     * @return static
     */
    public function construct(
        int $editorUserId,
        string $sourceFilePath,
        string $encoding = 'UTF-8',
        bool $overwriting = false,
        bool $shouldReplaceBreaksWithHtml = false,
        bool $clearEmptyFields = false,
        bool $validateAsYouGo = true
    ): static {
        $this->editorUserId = $editorUserId;
        $this->overwriting = $overwriting;
        $this->shouldReplaceBreaksWithHtml = $shouldReplaceBreaksWithHtml;
        $this->clearEmptyFields = $clearEmptyFields;
        $this->validateAsYouGo = $validateAsYouGo;
        $columnHeaders = $this->cfg()->get('csv->admin->inventory')->toArray();
        $this->getCsvFileReader()->construct(
            $sourceFilePath,
            $encoding,
            array_flip($columnHeaders)
        );
        $this->logConfiguration($sourceFilePath, $encoding);
        return $this;
    }

    public function count(): int
    {
        return $this->getCsvFileReader()->countRows();
    }

    public function process(int $offset): ImportCsvProcessResult
    {
        // Disable individual lot counting in observers. We will do that at once when importing will be finished.
        AuctionLotCountCacher::getInstance()->enable(false);
        $statistic = LotImportCsvProcessStatistic::new();

        $validationStatus = $this->validateAsYouGo ? ValidationStatus::NONE : ValidationStatus::VALID;
        $rows = $this->readRows($offset, $validationStatus);

        foreach ($rows as $rowIndex => $row) {
            if ($this->validateAsYouGo) {
                $validationResult = $this->createLotItemImportCsvValidator()->validateRows(
                    [$rowIndex => $row],
                    $this->overwriting
                );
                if ($validationResult->hasError()) {
                    return ImportCsvProcessResult::new()->errorOnRow($validationResult->getErrorMessages(), $rowIndex);
                }
            }

            try {
                $statistic = $this->processRow($row, $rowIndex, $statistic);
            } catch (Exception $e) {
                $message = $this->getAdminTranslator()->trans(
                    'import.csv.general.producing_error',
                    [
                        'rowIndex' => $rowIndex,
                        'errorMessage' => $e->getMessage()
                    ],
                    'admin_message'
                );
                log_warning($message);
                return ImportCsvProcessResult::new()->errorOnRow([$message], $rowIndex);
            }
        }

        return ImportCsvProcessResult::new()->success($statistic);
    }

    /**
     * @param Row $row
     * @param int $rowIndex
     * @param LotImportCsvProcessStatistic $statistic
     * @return LotImportCsvProcessStatistic
     * @throws Exception
     */
    protected function processRow(Row $row, int $rowIndex, LotImportCsvProcessStatistic $statistic): LotImportCsvProcessStatistic
    {
        $this->getDb()->TransactionBegin();
        try {
            $isNew = !$row->lotItemInputDto->id;
            if ($isNew) {
                $statistic->addedLotsQuantity++;
                log_info('Start processing line ' . $rowIndex . ' for adding new lot item');
            } else {
                $statistic->updatedLotsQuantity++;
                log_info('Start processing line ' . $rowIndex . ' for updating existing lot item' . composeSuffix(['li' => $row->lotItemInputDto->id]));
            }
            $lotItemProducer = LotItemMakerProducer::new()->construct($row->lotItemInputDto, $row->lotItemConfigDto);
            $lotItemProducer->produce();

            foreach ($lotItemProducer->getAddedCustomFieldFiles() as $id => $fileNames) {
                $statistic->customFieldFiles[$id] = $fileNames;
            }

            $statistic->addedImagesQuantity += $lotItemProducer->getAddedImageCount();
            $statistic->rejectedImagesQuantity += $lotItemProducer->getRejectedImageCount();
            $this->getDb()->TransactionCommit();
        } catch (Exception $e) {
            $this->getDb()->TransactionRollback();
            throw $e;
        } finally {
            LotItemMakerLocker::new()->unlock($row->lotItemConfigDto); // #li-lock-4, unlock after transaction is committed or roll-backed
        }
        return $statistic;
    }

    /**
     * @return ImportCsvValidationResult
     */
    public function validate(): ImportCsvValidationResult
    {
        $columnHeaders = $this->cfg()->get('csv->admin->inventory')->toArray();
        $lotItemImportCsvValidator = $this->createLotItemImportCsvValidator();
        $headerValidationResult = $lotItemImportCsvValidator->validateHeader(
            $this->getCsvFileReader()->readHeader(),
            $columnHeaders,
            $this->createLotCustomFieldLoader()->loadAll()
        );
        if (
            $this->validateAsYouGo
            || $headerValidationResult->hasError()
        ) {
            return $headerValidationResult;
        }
        $rows = $this->readRows();
        $rowsValidationResult = $lotItemImportCsvValidator->validateRows(
            iterator_to_array($rows),
            $this->overwriting
        );
        return $rowsValidationResult;
    }

    /**
     * Create lotItem, auctionLot Dto objects for each table row
     * @param int|null $offset
     * @param ValidationStatus $validationStatus
     * @return Iterator
     */
    protected function readRows(int $offset = null, ValidationStatus $validationStatus = ValidationStatus::NONE): Iterator
    {
        $csvFileReader = $this->getCsvFileReader();
        $dtoFactory = $this->createDtoFactory()->construct(
            $this->getEditorUserId(),
            $this->getSystemAccountId(),
            $this->getSystemAccountId(),
            $this->createLotCustomFieldLoader()->loadAll(),
            $csvFileReader->getEncoding(),
            $this->clearEmptyFields,
            $this->shouldReplaceBreaksWithHtml
        );
        foreach ($csvFileReader->readRows($offset) as $rowIndex => $row) {
            yield $rowIndex => $dtoFactory->create($row, $validationStatus);
        }
    }

    /**
     * @param string $sourceFilePath
     * @param string $encoding
     */
    protected function logConfiguration(string $sourceFilePath, string $encoding): void
    {
        $logData = [
            'editor u' => $this->editorUserId,
            'overwriting' => $this->overwriting,
            'replace break with html' => $this->shouldReplaceBreaksWithHtml,
            'clear empty fields' => $this->clearEmptyFields,
            'source file' => $sourceFilePath,
            'encoding' => $encoding,
        ];
        log_debug("Inventory CSV import started with the following configuration" . composeSuffix($logData));
    }
}
