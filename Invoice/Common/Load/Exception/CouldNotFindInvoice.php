<?php
/**
 * SAM-4337: Invoice Loader class
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

namespace Sam\Invoice\Common\Load\Exception;

use RuntimeException;

class CouldNotFindInvoice extends RuntimeException
{
    /**
     * @param int|null $invoiceId
     * @return self
     */
    public static function withId(?int $invoiceId): self
    {
        $message = "Could not find Invoice with id \"{$invoiceId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
