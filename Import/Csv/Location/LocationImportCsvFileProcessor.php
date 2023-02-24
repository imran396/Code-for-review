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

use Exception;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\EntityMaker\Location\Dto\LocationMakerConfigDto;
use Sam\EntityMaker\Location\Dto\LocationMakerInputDto;
use Sam\EntityMaker\Location\Save\LocationMakerProducer;
use Sam\Import\Csv\Base\ImportCsvFileProcessorInterface;
use Sam\Import\Csv\Base\ImportCsvProcessResult;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Location\Statistic\LocationImportCsvProcessStatistic;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Import\Csv\Read\CsvFileReaderAwareTrait;

/**
 * This class is responsible for handling uploaded CSV file.
 *
 * Class LocationCsvFileProcessor
 * @package Sam\Import\Csv\Location
 */
class LocationImportCsvFileProcessor extends CustomizableClass implements ImportCsvFileProcessorInterface
{
    use CsvFileReaderAwareTrait;
    use OptionalsTrait;
    use LocationImportCsvDtoFactoryCreateTrait;
    use LocationImportCsvValidatorCreateTrait;
    use SystemAccountAwareTrait;

    public const OP_COLUMN_HEADERS = 'columnHeaders';

    /**
     * @var int
     */
    protected int $editorUserId;
    /**
     * @var LocationMakerInputDto[]
     */
    protected array $locationInputDtos = [];
    /**
     * @var LocationMakerConfigDto[]
     */
    protected array $locationConfigDtos = [];

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
     * @param array $optionals
     * @return static
     */
    public function construct(
        int $editorUserId,
        string $sourceFilePath,
        string $encoding = 'UTF-8',
        array $optionals = []
    ): static {
        $this->editorUserId = $editorUserId;
        $this->initOptionals($optionals);
        $this->getCsvFileReader()->construct(
            $sourceFilePath,
            $encoding,
            array_flip($this->fetchOptional(self::OP_COLUMN_HEADERS))
        );
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function process(int $offset): ImportCsvProcessResult
    {
        $statistic = LocationImportCsvProcessStatistic::new();

        $rows = $this->readRows($offset);
        foreach ($rows as $rowIndex => $row) {
            [$locationInputDto, $locationConfigDto] = $row;

            $validationResult = $this->createLocationImportCsvValidator()->validate(
                $this->getCsvFileReader()->readHeader(),
                [$locationInputDto],
                [$locationConfigDto]
            );
            if ($validationResult->hasError()) {
                return ImportCsvProcessResult::new()->errorOnRow($validationResult->getErrorMessages(), $rowIndex);
            }

            try {
                if ($locationInputDto->id) {
                    $statistic->updatedLocationsQuantity++;
                } else {
                    $statistic->addedLocationsQuantity++;
                }
                $locationProducer = LocationMakerProducer::new()->construct($locationInputDto, $locationConfigDto);
                $locationProducer->produce();
            } catch (Exception $e) {
                log_warning(sprintf('Error occurred on row %d csv lot upload %s', $rowIndex, $this->getCsvFileReader()->getSourceFilePath()) . $e->getMessage());
                return ImportCsvProcessResult::new()->error([$e->getMessage()], $statistic);
            }
        }

        return ImportCsvProcessResult::new()->success($statistic);
    }

    /**
     * @param int|null $offset
     * @return array
     */
    protected function readRows(?int $offset = null): iterable
    {
        $dtoFactory = $this->createLocationImportCsvDtoFactory()->construct(
            $this->editorUserId,
            $this->getSystemAccountId(),
            $this->getSystemAccountId()
        );
        $csvRows = $this->getCsvFileReader()->readRows($offset);
        foreach ($csvRows as $rowIndex => $row) {
            log_info('populate dto from line ' . $rowIndex);
            yield $rowIndex => $dtoFactory->create($row);
        }
    }

    /**
     * @inheritdoc
     */
    public function validate(): ImportCsvValidationResult
    {
        $this->fillDtosForValidate();
        $result = $this->createLocationImportCsvValidator()->validate(
            $this->getCsvFileReader()->readHeader(),
            $this->locationInputDtos,
            $this->locationConfigDtos
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
     * Construct and fill data structures from CSV input.
     */
    protected function fillDtosForValidate(): void
    {
        $this->fillDtos();
    }

    /**
     * @param int|null $offset
     */
    protected function fillDtos(int $offset = null): void
    {
        $locationCsvDtoFactory = $this->createLocationImportCsvDtoFactory()->construct(
            $this->editorUserId,
            $this->getSystemAccountId(),
            $this->getSystemAccountId()
        );
        foreach ($this->getCsvFileReader()->readRows($offset) as $rowIndex => $row) {
            if ($row->isEmpty()) {
                continue;
            }

            [$this->locationInputDtos[$rowIndex], $this->locationConfigDtos[$rowIndex]] = $locationCsvDtoFactory->create($row);
        }
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_COLUMN_HEADERS] = $optionals[self::OP_COLUMN_HEADERS]
            ?? static function (): array {
                return ConfigRepository::getInstance()->get('csv->admin->location')->toArray();
            };
        $this->setOptionals($optionals);
    }
}
