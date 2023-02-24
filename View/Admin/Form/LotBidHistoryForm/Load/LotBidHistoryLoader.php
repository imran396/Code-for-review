<?php
/**
 * SAM-6684: Merge the two admin bidding histories and Improvement for Lot bidding History
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/29/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotBidHistoryForm\Load;

use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepository;
use Sam\Storage\ReadRepository\Entity\BidTransaction\BidTransactionReadRepositoryCreateTrait;
use Sam\View\Admin\Form\LotBidHistoryForm\Load\Dto\LotBidHistoryDto;

/**
 * Class LotBidHistoryLoader
 * @package Sam\View\Admin\Form\LotBidHistory\Load
 */
class LotBidHistoryLoader extends CustomizableClass
{
    use BidTransactionLoaderCreateTrait;
    use BidTransactionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function count(
        int $auctionId,
        int $lotItemId,
        bool $isReadOnlyDb = false
    ): int {
        $repo = $this->prepareRepository($auctionId, $lotItemId, $isReadOnlyDb);
        return $repo->count();
    }

    /**
     * @param int $lotItemId
     * @param int $auctionId
     * @param array $options
     * @return array
     */
    public function load(int $auctionId, int $lotItemId, array $options): array
    {
        $rows = $this->loadAll($auctionId, $lotItemId, $options);
        foreach ($rows as $key => $row) {
            $rows[$key] = LotBidHistoryDto::new()->fromDbRow($row);
        }
        return $rows;
    }

    /**
     * Load all bids, including increased by owner ones
     * @param int $auctionId
     * @param int $lotItemId
     * @param array $options
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAll(
        int $auctionId,
        int $lotItemId,
        array $options = [],
        bool $isReadOnlyDb = false
    ): array {
        $createdOnOrder = $options['order']['CreatedOn'] ?? false;
        $bidOrder = $options['order']['Bid'] ?? false;
        $idOrder = $options['order']['Id'] ?? false;
        $offset = $options['offset'] ?? 0;

        $repo = $this->prepareRepository($auctionId, $lotItemId, $isReadOnlyDb)
            ->orderByCreatedOn($createdOnOrder)
            ->orderByBid($bidOrder)
            ->orderById($idOrder)
            ->select(
                [
                    'bt.id',
                    'bt.auction_id',
                    'bt.failed',
                    'bt.created_on',
                    'bt.user_id',
                    'bt.floor_bidder',
                    'bt.bid',
                    'bt.is_buy_now',
                    'bt.max_bid',
                    'bt.modified_on',
                    'bt.modified_by',
                ]
            );
        if (isset($options['limit'])) {
            $repo->limit($options['limit'])->offset($offset);
        }
        return $repo->loadRows();
    }

    /**
     * @param int $auctionId
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return BidTransactionReadRepository
     */
    public function prepareRepository(
        int $auctionId,
        int $lotItemId,
        bool $isReadOnlyDb = false
    ): BidTransactionReadRepository {
        $repo = $this->createBidTransactionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterLotItemId($lotItemId)
            ->filterDeleted(false)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId([Constants\User::US_ACTIVE, null]);  // SAM-4371
        return $repo;
    }

}
