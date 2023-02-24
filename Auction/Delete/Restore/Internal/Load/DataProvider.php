<?php
/**
 *
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Delete\Restore\Internal\Load;

use Auction;
use Sam\Auction\Load\AuctionLoader;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\Auction\Delete\Restore\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAuction(?int $auctionId, bool $isReadOnlyDb = false): ?Auction
    {
        return AuctionLoader::new()
            ->filterAuctionStatusId(Constants\Auction::$auctionStatuses)
            ->load($auctionId, $isReadOnlyDb);
    }

}
