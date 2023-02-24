<?php
/**
 *
 * SAM-4628: Refactor audit trail report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-03
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\AuditTrail;

use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Report\Base\Csv\ReporterBase;

/**
 * Class AuditTrailReporter
 * @package Sam\Report\AuditTrail
 */
class AuditTrailReporter extends ReporterBase
{
    use FilePathHelperAwareTrait;
    use FilterAccountAwareTrait;
    use FilterDatePeriodAwareTrait;

    protected ?DataLoader $dataLoader = null;

    /**
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
                ->filterAccountId($this->getFilterAccountId())
                ->filterEndDateUtc($this->getFilterEndDateUtc())
                ->filterStartDateUtc($this->getFilterStartDateUtc())
                ->setSystemAccountId($this->getSystemAccountId());
        }
        return $this->dataLoader;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $filename = 'AT-ReportFrom' . $this->getFilterStartDateUtcIso() . 'to' . $this->getFilterEndDateUtcIso() . '.csv';
            $filename = $this->getFilePathHelper()->toFilename($filename);
            $this->setOutputFileName($filename);
        }
        return $this->outputFileName;
    }

    /**
     * Output titles for csv header (string or echo)
     * @return string
     */
    protected function outputTitles(): string
    {
        $headerTitles = [
            "Timestamp",
            "Ms",
            "Ip",
            "Port",
            "Section",
            "Username",
            "UserId",
            "ProxyUserName",
            "ProxyUserId",
            "AccountId",
            "Event"
        ];

        $headerLine = $this->makeLine($headerTitles);

        return $this->processOutput($headerLine);
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        $rows = $this->getDataLoader()->yieldData();
        $output = '';
        foreach ($rows as $row) {
            $bodyLine = $this->buildBodyLine($row);
            $output .= $this->processOutput($bodyLine);
        }
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    protected function buildBodyLine(array $row): string
    {
        $ip = (int)$row['ip'] ? long2ip((int)$row['ip']) : '';
        $bodyRow = [
            $row['timestamp'],
            $row['ms'],
            $ip,
            $row['port'],
            $row['section'],
            $row['username'],
            $row['user_id'],
            $row['puname'],
            $row['proxy_user_id'],
            $row['account_id'],
            $row['event'],
        ];

        $bodyLine = $this->makeLine($bodyRow);

        return $bodyLine;
    }
}
