<?php
/**
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/22/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format
 */

namespace Sam\Report\Base\Csv;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;
use Sam\Report\Base\ReporterInterface;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class ReporterBase
 * @package Sam\Report\Base\Csv
 */
abstract class ReporterBase extends CustomizableClass implements ReporterInterface
{
    use OutputBufferCreateTrait;
    use ReportToolAwareTrait;
    use SettingsManagerAwareTrait;
    use SystemAccountAwareTrait;
    use UserLoaderAwareTrait;

    private ?string $encoding = null;
    protected ?string $errorMessage = null;
    private bool $isEcho = true;
    private bool $isHttpHeader = true;
    protected ?string $outputFileName = null;
    protected ?int $systemAccountId = null;
    /** @var string[] */
    protected array $httpHeaders = [
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Content-Type" => "text/csv",
        "Expires" => "0",
        "Pragma" => "public",
    ];

    /**
     * Suggested functionality for report output building
     * @return string
     */
    public function output(): string
    {
        $startTs = microtime(true);
        if ($this->isHttpHeader()) {
            $this->sendHttpHeader();
        }
        if ($this->validate()) {
            $output = $this->outputTitles() . $this->outputBody();
        } else {
            $output = $this->outputError();
        }
        $elapsedTs = round(microtime(true) - $startTs, 2);
        log_debug("Elapsed time {$elapsedTs} seconds");
        return $output;
    }

    /**
     * @return bool
     */
    public function isEcho(): bool
    {
        return $this->isEcho;
    }

    /**
     * False for disable echo output and get result as string, true by default
     * @param bool $isEcho
     * @return static
     */
    public function enableEcho(bool $isEcho): static
    {
        $this->isEcho = $isEcho;
        return $this;
    }

    /**
     * @return string
     */
    public function getEncoding(): string
    {
        if ($this->encoding === null) {
            $this->encoding = $this->getSettingsManager()
                ->get(Constants\Setting::DEFAULT_EXPORT_ENCODING, $this->getSystemAccountId());
        }
        return $this->encoding;
    }

    /**
     * @param string $encoding
     * @return static
     */
    public function setEncoding(string $encoding): static
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHttpHeader(): bool
    {
        return $this->isHttpHeader;
    }

    /**
     * False if we don't want to send http headers, true by default
     * @param bool $isHttpHeader
     * @return static
     * @noinspection PhpUnused
     */
    public function enableHttpHeader(bool $isHttpHeader): static
    {
        $this->isHttpHeader = $isHttpHeader;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputFileName(): string
    {
        if ($this->outputFileName === null) {
            $this->outputFileName = 'report.csv';
        }
        return $this->outputFileName;
    }

    /**
     * @param string $outputFileName
     * @return static
     */
    public function setOutputFileName(string $outputFileName): static
    {
        $this->outputFileName = trim($outputFileName);
        return $this;
    }

    /**
     * Encode and csv-escape values, make and return one line of csv output with EOL
     * @param array $values
     * @return string
     */
    protected function makeLine(array $values): string
    {
        $line = $this->getReportTool()->makeLine($values, $this->getEncoding());
        return $line;
    }

    /**
     * @return string
     */
    protected function outputBody(): string
    {
        return '';
    }

    /**
     * @return string
     */
    protected function outputTitles(): string
    {
        return '';
    }

    /**
     * @return string
     */
    protected function outputError(): string
    {
        return $this->processOutput((string)$this->errorMessage);
    }

    /**
     * Convert UTF-8 encoding to the set encoding for export in settings
     * @param string|int|float|null $value
     * @return string
     * @noinspection PhpUnused
     */
    protected function prepareValue(string|int|float|null $value): string
    {
        $value = $this->getReportTool()->prepareValue($value, $this->getEncoding());
        return $value;
    }

    /**
     * Echo or return $output string
     * @param string $output
     * @return string
     */
    protected function processOutput(string $output): string
    {
        if ($this->isEcho()) {
            echo $output;
            $this->createOutputBuffer()->completeEndFlush();
            $output = '';
        }
        return $output;
    }

    /**
     * Convert array of csv values to escaped list of values as string
     * @param array $row
     * @return string
     */
    protected function rowToLine(array $row): string
    {
        return $this->getReportTool()->rowToLine($row);
    }

    /**
     * @return void
     */
    protected function sendHttpHeader(): void
    {
        $fileName = $this->getOutputFileName();
        $this->httpHeaders["Content-disposition"] = "attachment; filename={$fileName}";
        foreach ($this->httpHeaders as $header => $value) {
            header("{$header}: {$value}");
        }
    }

    /**
     * @return bool
     */
    protected function validate(): bool
    {
        return true;
    }
}
