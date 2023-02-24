<?php
/**
 * SAM-9684: Reduce effects of stale cached data on system parameters
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Settings\Exception;

use RuntimeException;

/**
 * Class CouldNotFindSettings
 * @package Sam\Settings\Exception
 */
class CouldNotFindSettings extends RuntimeException
{
    /**
     * @param string $className
     * @param int|null $accountId
     * @return self
     */
    public static function forClassNameWithAccountId(string $className, ?int $accountId): self
    {
        $message = "Could not find {$className} by account id \"{$accountId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
