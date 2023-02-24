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

namespace Sam\Settlement\Check\Action\MarkVoided\Single\Update\Exception;

use RuntimeException;

class CouldNotMarkVoidedCheck extends RuntimeException
{
    /**
     * @param int $settlementCheckId
     * @return self
     */
    public static function becauseAlreadyVoided(int $settlementCheckId): self
    {
        $message = "Could not mark voided the settlement check \"{$settlementCheckId}\", because it is already voided";
        log_errorBackTrace($message);
        return new self($message);
    }
}
