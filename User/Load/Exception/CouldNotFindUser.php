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

namespace Sam\User\Load\Exception;

use RuntimeException;

class CouldNotFindUser extends RuntimeException
{
    /**
     * @param int|null $userId
     * @return self
     */
    public static function withId(?int $userId): self
    {
        $message = "Could not find User by id \"{$userId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }

    /**
     * @param string $username
     * @return self
     */
    public static function withUsername(string $username): self
    {
        $message = "Could not find User by username \"{$username}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
