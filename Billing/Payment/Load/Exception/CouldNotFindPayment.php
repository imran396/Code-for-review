<?php
/**
 * SAM-11000: Stacked Tax. New Invoice Edit page: Payments section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Payment\Load\Exception;

use RuntimeException;

/**
 * Class CouldNotFindPayment
 * @package Sam\Billing\Payment\Load\Exception
 */
class CouldNotFindPayment extends RuntimeException
{

    public static function withId(?int $paymentId): self
    {
        $message = "Could not find Payment by id \"{$paymentId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
