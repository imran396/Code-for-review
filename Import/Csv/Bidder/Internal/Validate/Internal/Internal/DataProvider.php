<?php
/**
 * SAM-3796: Bidder upload into auction
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Internal\Validate\Internal\Internal;

use Sam\Bidder\AuctionBidder\Validate\AuctionBidderChecker;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\Import\Csv\Bidder\Internal\Validate\Internal\Internal
 * @internal
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function existBidderNo(
        string $bidderNo,
        int $auctionId,
        ?int $skipUserId,
        bool $isReadOnlyDb = false
    ): bool {
        return AuctionBidderChecker::new()->existBidderNo(
            $bidderNo,
            $auctionId,
            (array)$skipUserId,
            [],
            $isReadOnlyDb
        );
    }
}
