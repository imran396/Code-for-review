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

namespace Sam\Import\Csv\Lot\AuctionLot;

use Auction;
use Exception;
use Iterator;
use LotItemCustField;
use Sam\Auction\Cache\LotCount\AuctionLotCountCacher;
use Sam\AuctionLot\Order\Reorder\Auction\AuctionLotMultipleAuctionReordererCreateTrait;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\EntityMaker\AuctionLot\Lock\AuctionLotMakerLocker;
use Sam\EntityMaker\AuctionLot\Save\AuctionLotMakerProducer;
use Sam\EntityMaker\Base\Dto\ValidationStatus;
use Sam\EntityMaker\LotItem\Lock\LotItemMakerLocker;
use Sam\EntityMaker\LotItem\Save\LotItemMakerProducer;
use Sam\Import\Csv\Base\ImportCsvFileProcessorInterface;
use Sam\Import\Csv\Base\ImportCsvProcessResult;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Lot\AuctionLot\Internal\Dto\DtoFactoryCreateTrait;
use Sam\Import\Csv\Lot\AuctionLot\Internal\Dto\Row;
use Sam\Import\Csv\Lot\AuctionLot\Validate\AuctionLotImportCsvValidatorCreateTrait;
use Sam\Import\Csv\Lot\Internal\UpdatingEntity\DetectByAuctionLot\AuctionContextLotItemIdDetectorCreateTrait;
use Sam\Import\Csv\Read\CsvFileReaderAwareTrait;
use Sam\Import\PartialUpload\PartialUploadManager;
use Sam\Import\PartialUpload\PartialUploadManagerAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Lock\DbLockerCreateTrait;
use Sam\Translation\AdminTranslatorAwareTrait;


/**
 * This class is responsible for handling uploaded CSV file with auction lots.
 *
 * Class AuctionLotImportCsvFileProcessor
 * @package Sam\Import\Csv\Lot\AuctionLot
 */
class AuctionLotImportCsvFileProcessor extends CustomizableClass implements ImportCsvFileProcessorInterface
{
    use AdminTranslatorAwareTrait;
    use AuctionContextLotItemIdDetectorCreateTrait;
    use AuctionLotImportCsvValidatorCreateTrait;
    use AuctionLotMultipleAuctionReordererCreateTrait;
    use ConfigRepositoryAwareTrait;
    use CsvFileReaderAwareTrait;
    use DbConnectionTrait;
    use DbLockerCreateTrait;
    use DtoFactoryCreateTrait;
    use EditorUserAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use PartialUploadManagerAwareTrait;
    use SystemAccountAwareTrait;

    protected Auction $auction;
    protected int $editorUserId;
    protected bool $lotItemOverwriting;
    protected bool $auctionLotOverwriting;
    protected bool $shouldReplaceBreaksWithHtml;
    protected bool $clearEmptyFields;
    protected bool $validateAsYouGo;
    /**
     * @var LotItemCustField[]
     */
    protected ?array $customFields = null;

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
     * @param Auction $auction
     * @param string $encoding
     * @param bool $lotItemOverwriting
     * @param bool $auctionLotOverwriting
     * @param bool $shouldReplaceBreaksWithHtml
     * @param bool $clearEmptyFields
     * @param bool $validateAsYouGo
     * @return static
     */
    public function construct(
        int $editorUserId,
        string $sourceFilePath,
        Auction $auction,
        string $encoding = 'UTF-8',
        bool $lotItemOverwriting = false,
        bool $auctionLotOverwriting = false,
        bool $shouldReplaceBreaksWithHtml = false,
        bool $clearEmptyFields = false,
        bool $validateAsYouGo = true
    ): static {
        $this->auction = $auction;
        $this->editorUserId = $editorUserId;
        $this->lotItemOverwriting = $lotItemOverwriting;
        $this->auctionLotOverwriting = $auctionLotOverwriting;
        $this->shouldReplaceBreaksWithHtml = $shouldReplaceBreaksWithHtml;
        $this->clearEmptyFields = $clearEmptyFields;
        $this->validateAsYouGo = $validateAsYouGo;
        $this->getPartialUploadManager()->construct(PartialUploadManager::TYPE_AUCTION_LOTS, $auction->Id);
        $this->getCsvFileReader()->construct(
            $sourceFilePath,
            $encoding,
            array_flip($this->detectColumnHeaders())
        );
        $this->logConfiguration($sourceFilePath, $encoding);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function process(int $offset): ImportCsvProcessResult
    {
        // Disable individual lot counting in observers. We will do that at once when importing will be finished.
        AuctionLotCountCacher::getInstance()->enable(false);

        $validationStatus = $this->validateAsYouGo ? ValidationStatus::NONE : ValidationStatus::VALID;
        $rows = $this->readRows($offset, $validationStatus);

        $statistic = AuctionLotImportCsvProcessStatistic::new();

        foreach ($rows as $rowIndex => $row) {
            if ($this->validateAsYouGo) {
                $validationResult = $this->createAuctionLotImportCsvValidator()->validateRows(
                    [$rowIndex => $row],
                    $this->lotItemOverwriting,
                    $this->auctionLotOverwriting
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

        $this->createAuctionLotMultipleAuctionReorderer()->reorderQueued($this->editorUserId);

        return ImportCsvProcessResult::new()->success($statistic);
    }

    /**
     * @inheritdoc
     */
    public function validate(): ImportCsvValidationResult
    {
        $auctionLotImportCsvValidator = $this->createAuctionLotImportCsvValidator();

        $headerValidationResult = $auctionLotImportCsvValidator->validateHeader(
            $this->getCsvFileReader()->readHeader(),
            $this->loadCustomFields(),
            $this->detectColumnHeaders(),
            $this->auction
        );

        if (
            $this->validateAsYouGo
            || $headerValidationResult->hasError()
        ) {
            log_debug(
                'Auction lot CSV import validation failed on header checking stage'
                . composeSuffix(['errors' => $headerValidationResult->getErrorMessages()])
            );
            return $headerValidationResult;
        }

        $rows = $this->readRows();
        $rows = iterator_to_array($rows);
        $uniquenessValidationResult = $auctionLotImportCsvValidator->validateItemNoLotNoUniqueness($rows, $this->auction->Id);

        if ($uniquenessValidationResult->hasError()) {
            log_debug(
                'Auction lot CSV import validation failed because of duplicated item# or lot#'
                . composeSuffix(['errors' => $uniquenessValidationResult->getErrorMessages()])
            );
            return $uniquenessValidationResult;
        }

        $auctionLotDtosValidationResult = $auctionLotImportCsvValidator->validateRows(
            $rows,
            $this->lotItemOverwriting,
            $this->auctionLotOverwriting
        );

        if ($auctionLotDtosValidationResult->hasError()) {
            log_debug(
                'Auction lot CSV import validation failed on input data checking stage'
                . composeSuffix(['errors' => $auctionLotDtosValidationResult->getErrorMessages()])
            );
        }

        return $auctionLotDtosValidationResult;
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return $this->getCsvFileReader()->countRows();
    }

    /**
     * Create lotItem, auctionLot Dto objects for each table row
     * @param int|null $offset
     * @param ValidationStatus $validationStatus
     * @return Iterator<int, Row>
     */
    protected function readRows(int $offset = null, ValidationStatus $validationStatus = ValidationStatus::NONE): Iterator
    {
        $systemAccountId = $this->getSystemAccountId();
        $csvFileReader = $this->getCsvFileReader();
        $auctionLotDtoFactory = $this->createDtoFactory()->construct(
            $this->auction,
            $this->getEditorUserId(),
            $this->auction->AccountId,
            $systemAccountId,
            $this->loadCustomFields(),
            $csvFileReader->getEncoding(),
            $this->clearEmptyFields,
            $this->shouldReplaceBreaksWithHtml
        );

        foreach ($csvFileReader->readRows($offset) as $rowIndex => $row) {
            yield $rowIndex => $auctionLotDtoFactory->create($row, $validationStatus);
        }
    }

    /**
     * @param Row $row
     * @param int $rowIndex
     * @param AuctionLotImportCsvProcessStatistic $statistic
     * @return AuctionLotImportCsvProcessStatistic
     * @throws Exception
     */
    protected function processRow(
        Row $row,
        int $rowIndex,
        AuctionLotImportCsvProcessStatistic $statistic
    ): AuctionLotImportCsvProcessStatistic {
        $this->getDb()->TransactionBegin();
        try {
            $isNew = !$row->lotItemInputDto->id;
            if ($isNew) {
                $statistic->addedLotsQuantity++;
                log_info('Start processing line ' . $rowIndex . ' for adding new lot item');
            } else {
                $statistic->updatedLotsQuantity++;
                log_info(
                    'Start processing line ' . $rowIndex . ' for updating existing lot item'
                    . composeSuffix(['li' => $row->lotItemInputDto->id])
                );
            }
            $lotItemProducer = LotItemMakerProducer::new()
                ->construct($row->lotItemInputDto, $row->lotItemConfigDto);
            $lotItemProducer->produce();

            foreach ($lotItemProducer->getAddedCustomFieldFiles() as $id => $fileNames) {
                $statistic->customFieldFiles[$id] = $fileNames;
            }

            $statistic->addedImagesQuantity += $lotItemProducer->getAddedImageCount();
            $statistic->rejectedImagesQuantity += $lotItemProducer->getRejectedImageCount();

            $lotItem = $lotItemProducer->getLotItem();
            $row->auctionLotInputDto->lotItemId = $lotItem->Id;

            // Auction can be created with assigned CSV lots file,
            // in this case auction id exists only on the saving stage
            if (!$row->auctionLotInputDto->auctionId) {
                $row->auctionLotInputDto->auctionId = $this->auction->Id;
            }

            $auctionLotProducer = AuctionLotMakerProducer::new()
                ->construct($row->auctionLotInputDto, $row->auctionLotConfigDto);
            $auctionLotProducer->produce();

            if (!$row->auctionLotInputDto->id) {
                $statistic->addedAuctionItemIds[] = $lotItem->Id;
            }

            $this->getDb()->TransactionCommit();
        } catch (Exception $e) {
            $this->getDb()->TransactionRollback();
            throw $e;
        } finally {
            LotItemMakerLocker::new()->unlock($row->lotItemConfigDto); // #li-lock-5, unlock after transaction is committed or roll-backed
            AuctionLotMakerLocker::new()->unlock($row->auctionLotConfigDto); // #ali-lock-5, unlock after transaction is committed or roll-backed
        }
        return $statistic;
    }

    /**
     * @return LotItemCustField[]
     */
    protected function loadCustomFields(): array
    {
        if ($this->customFields === null) {
            $this->customFields = $this->createLotCustomFieldLoader()->loadAll();
        }
        return $this->customFields;
    }

    protected function logConfiguration(string $sourceFilePath, string $encoding): void
    {
        $logData = [
            'a' => $this->auction->Id,
            'a. type' => $this->auction->AuctionType,
            'editor u' => $this->editorUserId,
            'overwriting' => $this->lotItemOverwriting,
            'replace break with html' => $this->shouldReplaceBreaksWithHtml,
            'clear empty fields' => $this->clearEmptyFields,
            'source file' => $sourceFilePath,
            'encoding' => $encoding,
        ];
        log_debug("Auction lot CSV import started with the following configuration" . composeSuffix($logData));
    }

    protected function detectColumnHeaders(): array
    {
        if ($this->auction->isTimed()) {
            return $this->cfg()->get('csv->admin->timed')->toArray();
        }
        return $this->cfg()->get('csv->admin->live')->toArray();
    }
}
