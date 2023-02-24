<?php
/**
 * SAM-4644: Refactor "Custom CSV Export" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\AuctionLotCustomList\Media\Csv;

use CustomCsvExportConfig;
use CustomCsvExportData;
use RuntimeException;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Calculate\CustomCsvExportFieldDetectorCreateTrait;
use Sam\Report\Lot\AuctionLotCustomList\Media\Csv\ReportField\AuctionLotCustomListCsvReportFieldCollection as ReportFieldCollection;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;

/**
 * This class contains methods for generating auction lot custom list report in Csv format
 *
 * Class AuctionLotCustomListCsvReporter
 * @package Sam\Report\Lot\AuctionLotCustomList\Media\Csv
 */
class AuctionLotCustomListCsvReporter extends ReporterBase
{
    use AuctionAwareTrait;
    use CurrentDateTrait;
    use CustomCsvExportFieldDetectorCreateTrait;
    use EntityFactoryCreateTrait;

    protected CustomCsvExportConfig $config;
    protected ?ReportFieldCollection $reportFields = null;
    protected ?AuctionLotCustomListCsvRowBuilder $rowBuilder = null;
    protected ?AuctionLotCustomListCsvDataLoader $dataLoader = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param CustomCsvExportConfig $config
     * @param int $auctionId
     * @return static
     */
    public static function construct(CustomCsvExportConfig $config, int $auctionId): static
    {
        $reporter = static::new();
        $reporter
            ->setConfig($config)
            ->setAuctionId($auctionId);
        return $reporter;
    }

    /**
     * @param CustomCsvExportConfig $config
     * @return static
     */
    public function setConfig(CustomCsvExportConfig $config): static
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        return sprintf('auction_lots_%s.custom.csv', $this->getCurrentDateUtc()->format('m-d-Y-His'));
    }

    /**
     * @inheritDoc
     */
    protected function outputTitles(): string
    {
        $fieldsConfig = $this->getReportFields();
        $row = $this->getRowBuilder()->buildTitlesRow($fieldsConfig);
        $output = $this->rowToLine($row);
        return $this->processOutput($output);
    }

    /**
     * @inheritDoc
     */
    protected function outputBody(): string
    {
        $reportFields = $this->getReportFields();
        $reportDataCollection = $this->getDataLoader()->loadReportData($this->getAuctionId(), $reportFields);

        $output = '';
        foreach ($reportDataCollection as $reportDto) {
            $row = $this->getRowBuilder()->buildRow($reportFields, $reportDto);
            $rowOutput = $this->rowToLine($row);
            $output .= $this->processOutput($rowOutput);
        }

        return $output;
    }

    /**
     * @return ReportFieldCollection
     */
    private function getReportFields(): ReportFieldCollection
    {
        if ($this->reportFields === null) {
            $fieldsConfig = $this->config->Id
                ? $this->getDataLoader()->loadFieldsConfig($this->config->Id)
                : $this->buildDefaultFieldsConfig();
            $this->reportFields = ReportFieldCollection::new()->fromFieldsConfig($fieldsConfig);
        }
        return $this->reportFields;
    }

    /**
     * @return CustomCsvExportData[]
     */
    private function buildDefaultFieldsConfig(): array
    {
        $auctionType = $this->getAuction()->AuctionType;
        $fields = $this->createCustomCsvExportFieldDetector()->detectCheckedByDefault($auctionType);
        $order = 1;
        $configs = [];
        foreach ($fields as $index => $field) {
            $config = $this->createEntityFactory()->customCsvExportData();
            $config->FieldIndex = $index;
            $config->FieldName = $field;
            $config->FieldOrder = $order++;
            $configs[] = $config;
        }
        return $configs;
    }

    /**
     * @return AuctionLotCustomListCsvRowBuilder
     */
    private function getRowBuilder(): AuctionLotCustomListCsvRowBuilder
    {
        if ($this->rowBuilder === null) {
            $auction = $this->getAuction();
            if (!$auction) {
                throw new RuntimeException("Available auction with ID '{$this->getAuctionId()}' not found");
            }
            $this->rowBuilder = AuctionLotCustomListCsvRowBuilder::new()
                ->construct($auction, $this->config);
        }

        return $this->rowBuilder;
    }

    private function getDataLoader(): AuctionLotCustomListCsvDataLoader
    {
        if (!$this->dataLoader) {
            $this->dataLoader = AuctionLotCustomListCsvDataLoader::new();
        }
        return $this->dataLoader;
    }
}
