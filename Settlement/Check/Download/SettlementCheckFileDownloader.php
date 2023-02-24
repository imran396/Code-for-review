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

namespace Sam\Settlement\Check\Download;

use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Access\SettlementCheckAccessChecker;
use Sam\Settlement\Check\Download\Response\SettlementCheckFileDownloaderResponse;

/**
 * Class SettlementCheckFileDownloader
 * @package Sam\Settlement\Check
 */
class SettlementCheckFileDownloader extends CustomizableClass
{
    use EditorUserAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $settlementAccountId null if not defined with 'aid' GET request param.
     */
    public function run(?int $settlementAccountId): void
    {
        $response = SettlementCheckFileDownloaderResponse::new();
        $result = SettlementCheckAccessChecker::new()
            ->checkAccessForView($settlementAccountId, $this->getEditorUserId(), true);
        if ($result->hasError()) {
            log_error('Settlement check load failed' . composeSuffix($result->logData()));
            $isAccessDenied = $result->hasAccessDeniedError();
            $response->withError($result->errorMessage(), $isAccessDenied);
        }
        $response->withSuccess($result->filePath, $result->fileName);
    }
}
