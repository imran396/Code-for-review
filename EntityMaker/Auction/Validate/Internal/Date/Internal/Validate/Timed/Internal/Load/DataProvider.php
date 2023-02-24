<?php
/**
 * SAM-10450: Decouple auction date validation logic into internal services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed\Internal\Load;

use DateTime;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Timed\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionCacheLoaderAwareTrait;
    use AuctionLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadEventType(?int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        if (!$auctionId) {
            return null;
        }
        return $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb)?->EventType;
    }

    public function loadStartClosingDate(int $auctionId, bool $isReadOnlyDb = false): ?DateTime
    {
        return $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb)?->StartClosingDate;
    }

    public function loadStartBiddingDate(int $auctionId, bool $isReadOnlyDb = false): ?DateTime
    {
        return $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb)?->StartBiddingDate;
    }

    public function loadDateAssignmentStrategy(int $auctionId, bool $isReadOnlyDb = false): ?int
    {
        return $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb)?->DateAssignmentStrategy;
    }

    public function loadTotalLots(int $auctionId, bool $isReadOnlyDb = false): int
    {
        return $this->getAuctionCacheLoader()->load($auctionId, $isReadOnlyDb)?->TotalLots ?? 0;
    }
}
