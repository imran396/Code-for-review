<?php
/**
 * SAM-7984: Adjustments for Settlement printable with responsive layout [dev only]
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           06-14, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Download\Response;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementCheckFileDownloaderResponse
 * @package Sam\Settlement\Check
 */
class SettlementCheckFileDownloaderResponse extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $fileRootPath
     * @param string $settlementCheckFileName
     */
    public function withSuccess(string $fileRootPath, string $settlementCheckFileName): never
    {
        header('Content-Description: Image file');
        $extension = substr($settlementCheckFileName, strrpos($settlementCheckFileName, '.') + 1);
        header('Content-Type: image/' . $extension);
        header('Content-Length: ' . filesize($fileRootPath));
        readfile($fileRootPath);

        exit(Constants\Cli::EXIT_SUCCESS);
    }

    /**
     * @param string $errorMessage
     * @param bool $isAccessDenied
     */
    public function withError(string $errorMessage, bool $isAccessDenied): never
    {
        $header = $isAccessDenied
            ? 'HTTP/1.1 403 Forbidden'
            : 'HTTP/1.1 500 Internal Server Error';

        $pageTitle = $isAccessDenied
            ? 'HTTP/1.1 403 Forbidden'
            : 'HTTP/1.1 500 Internal Server Error';

        header($header);

        echo <<<HTML
<html lang="en">
    <head>
        <title>{$header}</title>
    </head>
    <body>
        <h1>{$pageTitle}</h1>
        <h4>{$errorMessage}</h4>
    </body>
</html>
HTML;

        exit(Constants\Cli::EXIT_INCORRECT_USAGE);
    }
}
