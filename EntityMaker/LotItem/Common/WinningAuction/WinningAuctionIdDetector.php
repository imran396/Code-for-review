<?php
/**
 * SAM-10106: Supply lot winning info correspondence for winning auction and winning bidder fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Common\WinningAuction;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class WinningAuctionIdDetector
 * @package Sam\EntityMaker\LotItem
 */
class WinningAuctionIdDetector extends CustomizableClass
{
    use AuctionLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function detectFromInput(
        WinningAuctionInput $input,
        ?int $syncNamespaceId,
        int $accountId,
        bool $isReadOnlyDb = false
    ): ?int {
        if ($input->id) {
            return $input->id;
        }

        if (
            $input->syncKey
            && $syncNamespaceId
        ) {
            $auction = $this->getAuctionLoader()->loadBySyncKey(
                $input->syncKey,
                $syncNamespaceId,
                $accountId,
                $isReadOnlyDb
            );
            return $auction->Id ?? null;
        }

        if ($input->saleNo) {
            $auction = $this->getAuctionLoader()->loadBySaleNo(saleNum: $input->saleNo, isReadOnlyDb: $isReadOnlyDb);
            return $auction->Id ?? null;
        }

        return null;
    }
}
