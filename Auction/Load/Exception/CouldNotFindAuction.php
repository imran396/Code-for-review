<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Load\Exception;

use RuntimeException;

class CouldNotFindAuction extends RuntimeException
{
    /**
     * @param int|null $auctionId
     * @return self
     */
    public static function withId(?int $auctionId): self
    {
        $message = "Could not find Auction by id \"{$auctionId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
