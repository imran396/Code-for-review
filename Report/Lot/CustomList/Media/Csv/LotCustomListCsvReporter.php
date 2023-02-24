<?php
/**
 * SAM-4629: Refactor custom lot report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 09, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Media\Csv;

use CustomLotsTemplateConfig;
use Generator;
use LotItemCustField;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Report\Lot\CustomList\Load\LotCustomListDataLoader;
use Sam\Report\Lot\CustomList\Media\Base\LotCustomListReporterInterface;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;

/**
 * This class contains methods for generating lot custom list report in Csv format
 *
 * Class LotCustomListCsvReporter
 * @package Sam\Report\Lot\CustomList\Output\Csv
 */
class LotCustomListCsvReporter extends ReporterBase implements LotCustomListReporterInterface
{
    use AccountAwareTrait;
    use LotCustomFieldLoaderCreateTrait;

    /**
     * @var LotCustomListDataLoader|null
     */
    private ?LotCustomListDataLoader $customListDataLoader = null;
    /**
     * @var LotCustomListCsvRowBuilder|null
     */
    private ?LotCustomListCsvRowBuilder $rowBuilder = null;
    /**
     * @var array
     */
    private array $fieldsTitles = [];
    /**
     * @var CustomLotsTemplateConfig|null
     */
    private ?CustomLotsTemplateConfig $customLotsTemplateConfig = null;
    /**
     * @var LotItemCustField[]|null
     */
    private ?array $lotCustomFields = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function init(
        array $fieldsTitles,
        LotCustomListDataLoader $customListDataLoader,
        CustomLotsTemplateConfig $customLotsTemplateConfig = null,
        array $lotCustomFields = null
    ): static {
        $this->fieldsTitles = $fieldsTitles;
        $this->customListDataLoader = $customListDataLoader;
        $this->customLotsTemplateConfig = $customLotsTemplateConfig;
        $this->lotCustomFields = $lotCustomFields;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $this->outputFileName = sprintf('custom-lots-report%s.csv', date('m-d-Y-H:i:s'));
        }
        return $this->outputFileName;
    }

    /**
     * @inheritDoc
     */
    protected function outputTitles(): string
    {
        $fieldTitles = $this->getFieldsTitles();
        $headerTitles = $this->getRowBuilder()->buildHeaderRow($fieldTitles);
        $output = $this->makeLine($headerTitles);
        return $this->processOutput($output);
    }

    /**
     * @inheritDoc
     */
    protected function outputBody(): string
    {
        $output = '';
        $fields = $this->getFields();
        foreach ($this->yieldReportData($fields) as $rowData) {
            $row = $this->getRowBuilder()->buildRow($rowData, $fields);
            $rowOutput = $this->rowToLine($row);
            $output .= $this->processOutput($rowOutput);
        }
        return $output;
    }

    /**
     * @param array $fields
     * @return Generator
     */
    private function yieldReportData(array $fields): Generator
    {
        return $this->customListDataLoader->yieldRows($fields);
    }

    /**
     * @return LotCustomListCsvRowBuilder
     */
    private function getRowBuilder(): LotCustomListCsvRowBuilder
    {
        if ($this->rowBuilder === null) {
            $this->rowBuilder = LotCustomListCsvRowBuilder::new()
                ->setAccountId($this->getAccountId())
                ->setEncoding($this->getEncoding())
                ->setLotCustomFields($this->getLotCustomFields());

            $config = $this->customLotsTemplateConfig;
            if ($config) {
                $this->rowBuilder->enableImageWebLinks($config->ImageWebLinks);
            }
        }
        return $this->rowBuilder;
    }

    /**
     * @return array
     */
    private function getFieldsTitles(): array
    {
        return $this->fieldsTitles;
    }

    /**
     * @return array
     */
    private function getFields(): array
    {
        return array_keys($this->getFieldsTitles());
    }

    /**
     * @return LotItemCustField[]
     */
    private function getLotCustomFields(): array
    {
        if ($this->lotCustomFields === null) {
            $this->lotCustomFields = $this->createLotCustomFieldLoader()->loadAll();
        }
        return $this->lotCustomFields;
    }
}
