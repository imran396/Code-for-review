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

class CouldNotFindInvoiceAuction extends RuntimeException
{
    /**
     * @param int|null $invoiceId
     * @param int|null $auctionId
     * @return self
     */
    public static function withInvoiceIdAndAuctionId(?int $invoiceId, ?int $auctionId): self
    {
        $message = "Could not find Invoice Auction with invoice id \"{$invoiceId}\" and auction id \"{$auctionId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
