<?php
/**
 * SAM-4687: Refactor "Unsold Lots" report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format
 */

namespace Sam\Report\Lot\Unsold\Csv;

use Sam\Date\CurrentDateTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Report\Lot\Unsold\Base\DataLoaderAwareTrait;
use Sam\Report\Lot\Unsold\Base\ResultFieldsAwareTrait;

/**
 * Class UnsoldLotCsvReporter
 * @package Sam\Report\Lot\Unsold
 */
class Reporter extends \Sam\Report\Base\Csv\ReporterBase
{
    use CurrentDateTrait;
    use DataLoaderAwareTrait;
    use FilterAuctionAwareTrait;
    use ResultFieldsAwareTrait;

    /** @var RowBuilder|null */
    protected ?RowBuilder $rowBuilder = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
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
     * @param RowBuilder $rowBuilder
     * @return static
     * @noinspection PhpUnused
     */
    public function setRowBuilder(RowBuilder $rowBuilder): static
    {
        $this->rowBuilder = $rowBuilder;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $dateIso = $this->getCurrentDateUtc()->format('m-d-Y');
            $this->outputFileName = "unsold-lots-{$dateIso}.csv";
        }
        return $this->outputFileName;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $output = '';
        $rows = $this->getDataLoader()->load();

        if (!count($rows)) {
            echo "No unsold lots found!";
            return '';
        }

        foreach ($rows as $row) {
            $bodyRow = $this->getRowBuilder()->buildBodyRow($row, $this->getResultFields());
            $rowOutput = $this->rowToLine($bodyRow);
            $output .= $this->processOutput($rowOutput);
        }
        return $output;
    }

    /**
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerLine = $this->getRowBuilder()->buildHeaderLine($this->getResultFields());
        return $this->processOutput($headerLine);
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        $errorMessages = [];
        if (!$this->getFilterAuctionId()) {
            $errorMessages[] = "Auction Id not defined";
        }
        if (
            !$this->getResultFields()
            || !is_array($this->getResultFields())
        ) {
            $errorMessages[] = "Fields not defined";
        }
        $this->errorMessage = implode('; ', $errorMessages);
        $success = count($errorMessages) === 0;
        return $success;
    }
}
