<?php
/**
 * SAM-4666: User customer no adviser
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Save\Exception;


use RuntimeException;
use Throwable;

/**
 * Class UserCustomerNoExceededException
 * @package Sam\User\Save
 */
class UserCustomerNoExceededException extends RuntimeException
{
    /**
     * @inheritDoc
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'Maximum possible customer number reached. Please contact sys admin';
        parent::__construct($message, $code, $previous);
    }
}
