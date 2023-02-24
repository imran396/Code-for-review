<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * Facade for csv library
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Read;

use Iterator;
use League\Csv\Exception as CsvReaderException;
use League\Csv\Reader;
use League\Csv\Statement;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Transform\Csv\CsvTransformer;
use Sam\Import\Csv\Read\Internal\CsvRowDataMapperCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * A class to parse and read records from a CSV document.
 * Supports partial file processing.
 *
 * Class CsvFileReader
 * @package Sam\Import\Csv\Read
 */
class CsvFileReader extends CustomizableClass
{
    use CsvRowDataMapperCreateTrait;
    use OptionalsTrait;

    public const OP_UPLOAD_STEP = 'uploadStep';

    protected string $sourceFilePath;
    protected string $encoding;
    protected ?array $header = null;
    protected array $columnsMapping;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $sourceFilePath
     * @param string $encoding
     * @param array $columnsMapping
     * @param array $optionals
     * @return static
     */
    public function construct(
        string $sourceFilePath,
        string $encoding = 'UTF-8',
        array $columnsMapping = [],
        array $optionals = []
    ): static {
        $this->sourceFilePath = $sourceFilePath;
        $this->encoding = $encoding;
        $this->columnsMapping = $columnsMapping;
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Fetch the first row that should contain the column names
     *
     * @return array
     */
    public function readHeader(): array
    {
        if ($this->header === null) {
            $reader = Reader::createFromPath($this->sourceFilePath);
            $this->header = array_map('trim', $reader->fetchOne());
        }
        return $this->header;
    }

    /**
     * Read rows from a CSV file starting at row offset.
     * If offset is null, read all rows in the document except the first
     *
     * @param int|null $offset
     * @return Iterator|CsvRow[]
     */
    public function readRows(?int $offset = null): Iterator
    {
        try {
            $mapper = $this->createCsvRowDataMapper()->construct($this->columnsMapping);
            $reader = Reader::createFromPath($this->sourceFilePath);
            $stmt = (new Statement())
                ->offset($offset ?: 1)
                ->limit($offset ? $this->fetchOptional(self::OP_UPLOAD_STEP) : -1);
            $records = $stmt->process($reader, $this->readHeader())->getRecords();
            foreach ($records as $index => $rowData) {
                $rowData = $this->utf8Encode($rowData);
                $mappedData = $mapper->map($rowData);
                yield $index => CsvRow::new()->construct($mappedData);
            }
        } catch (CsvReaderException $e) {
            log_error($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getSourceFilePath(): string
    {
        return $this->sourceFilePath;
    }

    /**
     * @return string
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * Count rows in CSV document other than header
     *
     * @return int
     */
    public function countRows(): int
    {
        $reader = Reader::createFromPath($this->sourceFilePath);
        $rowsQty = count($reader) - 1; //Exclude header
        return $rowsQty > 0 ? $rowsQty : 0;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function utf8Encode(array $data): array
    {
        if ($this->encoding === 'UTF-8') {
            return $data;
        }

        $csvTransformer = CsvTransformer::new();
        foreach ($data as $key => $column) {
            $data[$key] = $csvTransformer->encodeToUtf8($column, $this->encoding);
        }
        return $data;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_UPLOAD_STEP] = $optionals[self::OP_UPLOAD_STEP]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->csv->uploadStep');
            };
        $this->setOptionals($optionals);
    }
}
