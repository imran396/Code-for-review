<?php
/**
 * SAM-11110: Stacked Tax. New Invoice Edit page: Service Fee Edit page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Load\Exception;

use RuntimeException;

class CouldNotFindInvoiceAdditional extends RuntimeException
{
    public static function withId(?int $invoiceAdditionalId): self
    {
        $message = "Could not find Invoice Additional with id \"{$invoiceAdditionalId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
