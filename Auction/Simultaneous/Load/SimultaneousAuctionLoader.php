<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Simultaneous\Load;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\SaleGroup\SaleGroupManagerAwareTrait;

/**
 * Class SimultaneousAuctionLoader
 * @package
 */
class SimultaneousAuctionLoader extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use SaleGroupManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Auction $auction
     * @param bool $isReadOnlyDb
     * @return int|null
     */
    public function findSimultaneousAuctionId(Auction $auction, bool $isReadOnlyDb = false): ?int
    {
        $auctionId = null;
        if ($auction->Simultaneous) {
            $auctionRows = $this->getSaleGroupManager()->loadAuctionRows(
                $auction->SaleGroup,
                $auction->AccountId,
                false,
                [$auction->Id],
                $isReadOnlyDb
            );
            if (!empty($auctionRows)) {
                $auctionId = (int)$auctionRows[0]['id'];
            }
        }
        return $auctionId;
    }

    /**
     * @param Auction $auction
     * @param bool $isReadOnlyDb
     * @return Auction|null
     */
    public function findSimultaneousAuction(Auction $auction, bool $isReadOnlyDb = false): ?Auction
    {
        $simultaneousAuctionId = $this->findSimultaneousAuctionId($auction, $isReadOnlyDb);
        $simultaneousAuction = $this->getAuctionLoader()->load($simultaneousAuctionId, $isReadOnlyDb);
        return $simultaneousAuction;
    }
}
