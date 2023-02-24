<?php
/**
 * SAM-4631 : Refactor internal notes report
 * https://bidpath.atlassian.net/browse/SAM-4631
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/24/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\InternalNote\Csv;

use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;

/**
 * Class Reporter
 * @package Sam\Report\InternalNote\Csv
 */
class Reporter extends ReporterBase
{
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use FilePathHelperAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterDatePeriodAwareTrait;
    use SortInfoAwareTrait;

    /** @var int */
    private const CHUNK_SIZE = 200;

    /** @var DataLoader|null */
    protected ?DataLoader $dataLoader = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param DataLoader $dataLoader
     * @return static
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if ($this->dataLoader === null) {
            $this->dataLoader = DataLoader::new()
                ->enableFilterDatePeriod($this->isFilterDatePeriod())
                ->filterStartDateSysIso($this->getFilterStartDateSysIso())
                ->filterEndDateSysIso($this->getFilterEndDateSysIso())
                ->filterAuctionId($this->getFilterAuctionId())
                ->setSortColumn($this->getSortColumn())
                ->enableAscendingOrder($this->isAscendingOrder())
                ->setChunkSize(self::CHUNK_SIZE);
        }
        return $this->dataLoader;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y-His');
            $filename = "internal-note-report{$dateIso}.csv";
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->outputFileName = $filename;
        }
        return $this->outputFileName;
    }

    /**
     * @param array $row
     * @return string
     */
    protected function buildBodyLine(array $row): string
    {
        $invoiceNumber = $row['invoice_number'];
        $username = $row['username'];
        $customerNo = $row['customer_no'];
        $internalNote = $row['internal_note'];
        $modifiedOnIso = $this->getDateHelper()->formattedDateByDateIso($row['modified_on']);
        $bodyRow = [
            $invoiceNumber,
            $username,
            $customerNo,
            $internalNote,
            $modifiedOnIso,
        ];

        $bodyLine = $this->makeLine($bodyRow);

        return $bodyLine;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        while ($rows = $this->getDataLoader()->loadNextChunk()) {
            foreach ($rows as $row) {
                $bodyLine = $this->buildBodyLine($row);
                $output .= $this->processOutput($bodyLine);
            }
        }
        return $output;
    }

    /**
     * Output CSV header Titles
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = [
            "Invoice#",
            "User",
            "Customer#",
            "Internal Note",
            "Date"
        ];

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }
}
