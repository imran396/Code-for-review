<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May. 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\FeeReference\Internal\Load;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * This class contains methods to load bid value for fee reference
 *
 * Class ConsignorFeeReferenceBidLoader
 * @package Sam\Consignor\Commission\FeeReference\Internal\Load
 * @internal
 */
class ConsignorFeeReferenceBidLoader extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use HighAbsenteeBidDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load lot max bid to use as fee reference. For live or Hybrid auctions we use the high absentee bid
     * @param int $lotItemId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function loadMaxBid(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): float
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            return 0.;
        }

        if ($auction->isTimed()) {
            $row = $this->prepareAuctionLotItemRepository($lotItemId, $auctionId, $isReadOnlyDb)
                ->select(['bt_cb.max_bid as max_bid'])
                ->loadRow();
            $maxBid = $row ? (float)$row['max_bid'] : 0.;
        } else {
            $maxAbsenteeBid = $this->createHighAbsenteeBidDetector()->detectFirstHigh($lotItemId, $auctionId, $isReadOnlyDb)->MaxBid ?? 0.;
            $currentBid = $this->loadCurrentBid($lotItemId, $auctionId, $isReadOnlyDb);
            $maxBid = Floating::gt($maxAbsenteeBid, $currentBid)
                ? $maxAbsenteeBid
                : $currentBid;
        }
        return $maxBid;
    }

    /**
     * Load lot current bid to use as fee reference
     * @param int $lotItemId
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return float
     */
    public function loadCurrentBid(int $lotItemId, ?int $auctionId, bool $isReadOnlyDb = false): float
    {
        if (!$auctionId) {
            return 0.;
        }

        $row = $this->prepareAuctionLotItemRepository($lotItemId, $auctionId, $isReadOnlyDb)
            ->select(['bt_cb.bid as current_bid'])
            ->loadRow();

        $currentBid = $row ? (float)$row['current_bid'] : 0.;
        return $currentBid;
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionLotItemReadRepository
     */
    protected function prepareAuctionLotItemRepository(int $lotItemId, int $auctionId, bool $isReadOnlyDb = false): AuctionLotItemReadRepository
    {
        $repo = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterLotItemId($lotItemId)
            ->filterAuctionId($auctionId)
            ->joinBidTransactionByCurrentBidFilterDeleted([null, false]);
        return $repo;
    }
}
