<?php
/**
 * SAM-4630: Refactor document view report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-05-07
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\DocumentView\Csv;

use Sam\Date\CurrentDateTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Entity\FilterUserAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;
use Sam\Core\Filter\Common\SortInfoAwareTrait;

/**
 * Class Reporter
 */
class Reporter extends ReporterBase
{
    use CurrentDateTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterUserAwareTrait;
    use FilterDatePeriodAwareTrait;
    use SortInfoAwareTrait;

    /** @var int */
    private const CHUNK_SIZE = 200;

    /**
     * @var DataLoader|null
     */
    protected ?DataLoader $dataLoader = null;
    /**
     * @var RowBuilder|null
     */
    protected ?RowBuilder $rowBuilder = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param RowBuilder $renderer
     * @return static
     */
    public function setRenderer(RowBuilder $renderer): static
    {
        $this->rowBuilder = $renderer;
        return $this;
    }

    /**
     * Get Output file name
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $currentDateUtc = $this->getCurrentDateUtc();
            $this->outputFileName = 'docviews-report-' . $currentDateUtc->format('m-d-Y-His') . ".csv";
        }
        return $this->outputFileName;
    }

    /**
     * Get DataLoader
     * @return DataLoader
     */
    protected function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->filterAccountId($this->getFilterAccountId())
                ->filterAuctionId($this->getFilterAuctionId())
                ->filterUserId($this->getFilterUserId())
                ->setSortColumn($this->getSortColumn())
                ->enableAscendingOrder($this->isAscendingOrder())
                ->enableFilterDatePeriod($this->isFilterDatePeriod())
                ->setChunkSize(self::CHUNK_SIZE);
        }
        return $this->dataLoader;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        while ($rows = $this->getDataLoader()->loadNextChunk()) {
            foreach ($rows as $row) {
                $bodyRow = $this->getRowBuilder()->buildBodyRow($row);
                $rowOutput = $this->rowToLine($bodyRow);
                $output .= $this->processOutput($rowOutput);
            }
        }
        return $output;
    }

    /**
     * @return RowBuilder
     */
    protected function getRowBuilder(): RowBuilder
    {
        if ($this->rowBuilder === null) {
            $this->rowBuilder = RowBuilder::new()
                ->setEncoding($this->getEncoding());
        }
        return $this->rowBuilder;
    }

    /**
     * Output CSV header Titles
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = $this->getRowBuilder()->buildHeaderLine();
        return $this->processOutput($headerTitles);
    }
}
