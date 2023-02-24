<?php

/**
 * SAM-4824 : Encapsulate $_SERVER access
 * https://bidpath.atlassian.net/browse/SAM-4824
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           5/26/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Process;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ApplicationProcessGuidManager
 * @package Sam\Application\Process
 */
class ApplicationProcessGuidManager extends CustomizableClass
{
    use ServerRequestReaderAwareTrait;

    private const PROCESS_GUID_NAME = 'SAM_PROCESS_GUID';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * We store Process Guid in $_SERVER global array.
     * @return string
     */
    public function getProcessGuid(): string
    {
        $processName = $this->getProcessGuidName();
        if (!isset($_SERVER[$processName])) {
            $_SERVER[$processName] = $this->generateProcessGuid();
        }
        return $_SERVER[$processName];
    }

    /**
     * Generate a random enough string to follow log entries even if there are multiple requests scripts
     * running at once truncated (don't need the full sha256)
     * replaced + and / for more convenient "grep" calls
     * @return string
     */
    public function generateProcessGuid(): string
    {
        $processGuid = str_replace(
            ["+", "/"],
            ["-", "_"],
            base64_encode(substr(hash("sha256", microtime() . getmypid(), true), 0, 12))
        );
        return $processGuid;
    }

    /**
     * @return string
     */
    public function getProcessGuidName(): string
    {
        return self::PROCESS_GUID_NAME;
    }
}
