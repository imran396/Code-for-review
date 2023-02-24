<?php
/**
 * SAM-9665: Assign system user reference to CreatedBy property of entities
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Load\Exception;

use RuntimeException;

class CouldNotFindAccount extends RuntimeException
{
    /**
     * @param int|null $accountId
     * @return self
     */
    public static function withId(?int $accountId): self
    {
        $message = "Could not find Account by id \"{$accountId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
