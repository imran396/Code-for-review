<?php
/**
 * SAM-4629: Refactor custom lot report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Base\PrintTable;

use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;
use Sam\Report\Base\ReporterInterface;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * This class contains common methods for generating reports in HTML format
 *
 * Class ReporterBase
 * @package Sam\Report\Base\PrintTable
 */
abstract class ReporterBase extends CustomizableClass implements ReporterInterface
{
    use OutputBufferCreateTrait;
    use SystemAccountAwareTrait;
    use UserLoaderAwareTrait;

    /** @var string|null */
    protected ?string $errorMessage = null;
    /** @var int|null */
    protected ?int $systemAccountId = null;
    /** @var bool */
    private bool $isEcho = false;

    /**
     * Suggested functionality for report output building
     * @return string
     */
    public function output(): string
    {
        $startTs = microtime(true);
        if ($this->validate()) {
            $output = $this->outputTableStart() . $this->outputTitles() . $this->outputBody() . $this->outputTableEnd();
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
    abstract protected function outputTableStart(): string;

    /**
     * @return string
     */
    abstract protected function outputTableEnd(): string;

    /**
     * @return string
     */
    abstract protected function outputBody(): string;

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
     * @return bool
     */
    protected function validate(): bool
    {
        return true;
    }
}
