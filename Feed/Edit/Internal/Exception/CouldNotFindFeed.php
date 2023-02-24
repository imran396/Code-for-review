<?php
/**
 * This is private for Deleter module exception at the moment.
 *
 * This is logic exception, because we expect this situation and handle it by validation.
 * For instance, incorrect feed id could be passed accidentally by CLI option.
 *
 * SAM-4697: Feed entity editor
 * SAM-5885: Refactor feed list management at admin side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 6, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\Edit\Internal\Exception;

use RuntimeException;

/**
 * Class CouldNotFindFeed
 **/
final class CouldNotFindFeed extends RuntimeException
{
    /**
     * @param int|null $feedId
     * @return self
     */
    public static function withId(?int $feedId): self
    {
        $message = "Could not find Feed by id \"{$feedId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
