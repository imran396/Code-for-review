<?php
/**
 * SAM-4637: Refactor print catalog report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 02, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Base\Pdf;

use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;

/**
 * This class contains interface and common methods for sending the client reports in pdf format via php.
 *
 * Class PdfReportDownloaderBase
 * @package Sam\Report\Base\Media
 */
abstract class PdfReportDownloaderBase extends CustomizableClass
{
    use OutputBufferCreateTrait;

    public function send(): void
    {
        $content = $this->getFileContent();
        $this->setHeaders(strlen($content));
        echo $content;
    }

    /**
     * Validate configuration
     * @return bool
     */
    public function validate(): bool
    {
        $fileRootPath = $this->detectFileRootPath();
        if (file_exists($fileRootPath) === false) {
            log_warning("Failed trying to export {$fileRootPath} does not exist");
            return false;
        }
        return true;
    }

    /**
     * @param int $contentLength
     */
    protected function setHeaders(int $contentLength): void
    {
        if ($this->createOutputBuffer()->getLength()) {
            log_debug('Some data has already been output, can\'t send PDF file');
        }
        header('Content-Type: application/pdf');
        if (headers_sent()) {
            log_debug('Some data has already been output, can\'t send PDF file');
        }
        header('Content-Length: ' . $contentLength);
        header('Content-Disposition: inline; filename="' . $this->makeOutputFileName() . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        ini_set('zlib.output_compression', '0');
    }

    /**
     * Path to generated report in pdf format
     * @return string
     */
    abstract protected function detectFileRootPath(): string;

    /**
     * Filename of downloaded report
     * @return string
     */
    abstract protected function makeOutputFileName(): string;

    /**
     * @return string
     */
    private function getFileContent(): string
    {
        return file_get_contents($this->detectFileRootPath());
    }

}
