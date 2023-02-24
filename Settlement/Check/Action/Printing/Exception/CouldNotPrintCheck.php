<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Printing\Exception;

use RuntimeException;

class CouldNotPrintCheck extends RuntimeException
{
    /**
     * @param int $settlementCheckId
     * @return self
     */
    public static function becauseAlreadyPrinted(int $settlementCheckId): self
    {
        $message = "Could not print the settlement check \"{$settlementCheckId}\", because it is already printed";
        log_errorBackTrace($message);
        return new self($message);
    }

    public static function becauseCheckNoIsFilled(int $settlementCheckId): self
    {
        $message = "Could not print the settlement check \"{$settlementCheckId}\", because check # is already filled";
        log_errorBackTrace($message);
        return new self($message);
    }
}
