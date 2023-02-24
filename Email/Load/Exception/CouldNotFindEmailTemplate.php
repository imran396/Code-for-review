<?php
/**
 * SAM-10513: Throw exception when email template is not found and warn in logs
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Load\Exception;

use RuntimeException;

class CouldNotFindEmailTemplate extends RuntimeException
{
    /**
     * @param string $emailKey
     * @param int $accountId
     * @return self
     */
    public static function withKeyAndAccount(string $emailKey, int $accountId): self
    {
        $message = sprintf("Could not find EmailTemplate by key (%s) and account (%s)", $emailKey, $accountId);
        log_errorBackTrace($message);
        return new self($message);
    }
}
