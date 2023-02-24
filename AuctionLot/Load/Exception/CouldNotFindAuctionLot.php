<?php
/**
 * SAM-10802: Supply uniqueness of auction lot fields: lot#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 28, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Load\Exception;

use RuntimeException;

final class CouldNotFindAuctionLot extends RuntimeException
{
    /**
     * @param int|null $auctionLotId
     * @return self
     */
    public static function withId(?int $auctionLotId): self
    {
        $message = "Could not find AuctionLot by id \"{$auctionLotId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
