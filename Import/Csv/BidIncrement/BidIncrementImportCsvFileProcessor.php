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

namespace Sam\Import\Csv\BidIncrement;

use Exception;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Import\Csv\Base\ImportCsvFileProcessorInterface;
use Sam\Import\Csv\Base\ImportCsvProcessResult;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\BidIncrement\Internal\Dto\RowInput;
use Sam\Import\Csv\BidIncrement\Internal\Process\RowProcessorCreateTrait;
use Sam\Import\Csv\BidIncrement\Internal\Validate\ValidatorCreateTrait;
use Sam\Import\Csv\BidIncrement\Statistic\BidIncrementImportCsvProcessStatistic;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Import\Csv\Read\CsvFileReaderAwareTrait;

/**
 * This class is responsible for handling uploaded CSV file with auction bid increments.
 *
 * Class BidIncrementImportCsvFileProcessor
 * @package Sam\Import\Csv\BidIncrement
 */
class BidIncrementImportCsvFileProcessor extends CustomizableClass implements ImportCsvFileProcessorInterface
{
    use ConfigRepositoryAwareTrait;
    use CsvFileReaderAwareTrait;
    use RowProcessorCreateTrait;
    use ValidatorCreateTrait;

    protected int $accountId;
    protected int $editorUserId;
    protected string $auctionType;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $editorUserId,
        string $sourceFilePath,
        int $accountId,
        string $auctionType,
        string $encoding = 'UTF-8'
    ): static {
        $this->accountId = $accountId;
        $this->editorUserId = $editorUserId;
        $this->auctionType = $auctionType;
        $this->getCsvFileReader()->construct(
            $sourceFilePath,
            $encoding,
            array_flip($this->getColumnNames())
        );
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function process(int $offset): ImportCsvProcessResult
    {
        $statistic = BidIncrementImportCsvProcessStatistic::new();

        $rowProcessor = $this->createRowProcessor();
        $rows = $this->readRows($offset);
        foreach ($rows as $rowIndex => $row) {
            try {
                $processingResult = $rowProcessor->process($row, $this->auctionType, $this->editorUserId, $this->accountId);
                if ($processingResult->isUpdated) {
                    $statistic->updatedItemsQuantity++;
                } else {
                    $statistic->createdItemsQuantity++;
                }
            } catch (Exception $e) {
                log_warning(sprintf('Error occurred on row %d ', $rowIndex) . $e->getMessage());
                return ImportCsvProcessResult::new()->error([$e->getMessage()], $statistic);
            }
        }
        return ImportCsvProcessResult::new()->success($statistic);
    }

    /**
     * @inheritdoc
     */
    public function validate(): ImportCsvValidationResult
    {
        $rows = $this->readRows();
        $header = $this->readHeader();
        $result = $this->createValidator()->validate(
            $rows,
            $header,
            $this->getColumnNames(),
            $this->auctionType,
            $this->accountId
        );

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return $this->getCsvFileReader()->countRows();
    }

    /**
     * @param int|null $offset
     * @return RowInput[]
     */
    protected function readRows(?int $offset = null): array
    {
        $dtos = [];
        $csvRows = $this->getCsvFileReader()->readRows($offset);
        foreach ($csvRows as $rowIndex => $row) {
            if ($row->isEmpty()) {
                continue;
            }
            log_info('populate dto from line ' . $rowIndex);
            $dtos[$rowIndex] = RowInput::new()->construct(
                $row->getCell(Constants\Csv\BidIncrement::AMOUNT),
                $row->getCell(Constants\Csv\BidIncrement::INCREMENT)
            );
        }
        return $dtos;
    }

    protected function readHeader(): array
    {
        return $this->getCsvFileReader()->readHeader();
    }

    protected function getColumnNames(): array
    {
        return $this->cfg()->get('csv->admin->bidIncrement')->toArray();
    }
}
