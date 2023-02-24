<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\ErrorReport\Internal;

use Sam\Application\Controller\Responsive\ErrorReport\ReportData;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Qform\ErrorHandler\QformErrorHandlerHelper;

/**
 * Class EmailContentRenderer
 * @package Sam\Application\Controller\Responsive\ErrorReport\Internal
 */
class EmailContentRenderer extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(ReportData $reportData): string
    {
        $fileName = (string)$reportData->errorReportFileName;
        $isFilenameCorrect = QformErrorHandlerHelper::new()->isErrorReportFileNameCorrect($fileName);
        if (!$isFilenameCorrect) {
            log_errorBackTrace('Incorrect file name for error report' . composeSuffix(['filename' => $fileName]));
            $this->createApplicationRedirector()->badRequest();
        }

        $template = <<<HTML
<b>Message: %s</b><br/>
<br/><b>Browser and OS info</b><br/>
Browser: %s<br/>
Flash: Version %s<br/>
Javascript: %s<br/>
Version: %s<br/>
Operating System: %s<br/>
<br/><b>Contact info</b><br/>
Contact name: %s<br/>
Contact email: %s<br/>
Contact phone: %s<br/>
<br/><b>Installation</b><br/>
Domain: %s<br/>
<br/><b>Error dump: </b><br/>
File: %s<br/>
%s
HTML;

        $message = sprintf(
            $template,
            $reportData->shortInfo ?: 'N/A',
            $reportData->browser ?: 'N/A',
            $reportData->flash ?: 'N/A',
            $reportData->javascript === 1 ? 'Enabled' : 'N/A',
            $reportData->browserVersion ?: 'N/A',
            $reportData->os ?: 'N/A',
            $reportData->contactName ?: 'N/A',
            $reportData->contactEmail ?: 'N/A',
            $reportData->contactPhone ?: 'N/A',
            $this->cfg()->get('core->app->httpHost'),
            $this->makeErrorDumpFilePath($fileName),
            $this->loadErrorDump($fileName)
        );
        return $message;
    }

    protected function makeErrorDumpFilePath(string $fileName): string
    {
        return path()->logReport() . '/' . $fileName;
    }

    protected function loadErrorDump(string $fileName): string
    {
        if (!$fileName) {
            return '';
        }
        $errorDumpFilePath = $this->makeErrorDumpFilePath($fileName);
        $errorDump = @file_get_contents($errorDumpFilePath);
        $errorDump = preg_replace('/^.*<body[^>]*>(.*)<\/body>.*$/is', '\1', $errorDump);
        return $errorDump;
    }
}
