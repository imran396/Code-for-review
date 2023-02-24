<?php
/**
 * Data loader for running auctions finding
 * SAM-3775: Rtbd improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 24, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Hybrid\Run\RunningAuction;

use Auction;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Rtb\Load\RtbLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCache\AuctionCacheReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Rtb\Hybrid\Run\RunningAuction
 */
class DataLoader extends CustomizableClass
{
    use AuctionCacheReadRepositoryCreateTrait;
    use AuctionReadRepositoryCreateTrait;
    use CurrentDateTrait;
    use RtbLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load auctions currently running. Don't cache in memory, because we want actual values (eg. auction_status_id)
     * @param string $rtbdName we expect empty string '' when rtb pooling is disabled
     * @return Auction[]   auction in Started status
     */
    public function loadRunningAuctions(string $rtbdName = ''): array
    {
        $auctionRepository = $this->createAuctionReadRepository()
            ->filterAuctionType(Constants\Auction::HYBRID)
            ->filterAuctionStatusId(Constants\Auction::AS_STARTED)
            ->filterExtendTimeGreater(0)
            // Don't filter by rtbd.lot_active, empty auctions are not closed automatically
            // ->joinRtbCurrentFilterLotActive(true)
            ->joinAccountFilterActive(true);
        if ($rtbdName) {
            // Filter auctions by their reference to running rtbd instance
            $auctionRepository->joinAuctionRtbdFilterRtbdName($rtbdName);
        }
        $auctions = $auctionRepository->loadEntities();
        return $auctions;
    }

    /**
     * @param Auction[] $auctions
     * @return array
     */
    public function loadAuctionCacheData(array $auctions): array
    {
        $auctionIds = [];
        foreach ($auctions as $auction) {
            $auctionIds[] = $auction->Id;
        }
        if (!$auctionIds) {
            return [];
        }
        // TODO: it may be stale data, we should refresh cache if modified_on = null
        $rows = $this->createAuctionCacheReadRepository()
            ->filterAuctionId($auctionIds)
            ->select(
                [
                    'ac.auction_id',
                    'ac.total_active_lots'
                ]
            )
            ->loadRows();
        $auctionCacheRows = ArrayHelper::produceIndexedArray($rows, 'auction_id', ['total_active_lots']);
        return $auctionCacheRows;
    }
}
