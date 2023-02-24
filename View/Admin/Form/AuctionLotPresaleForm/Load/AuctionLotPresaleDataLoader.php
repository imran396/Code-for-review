<?php
/**
 * Auction Lot Presale Data Loader
 *
 * SAM-5586: Refactor data loader for Auction Lot Presale page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 05, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotPresaleForm\Load;

use AbsenteeBid;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Entity\FilterLotItemAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepository;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionBidder\AuctionBidderReadRepositoryCreateTrait;

/**
 * Class AuctionLotPresaleDataLoader
 */
class AuctionLotPresaleDataLoader extends CustomizableClass
{
    use AbsenteeBidReadRepositoryCreateTrait;
    use AuctionBidderReadRepositoryCreateTrait;
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;
    use FilterLotItemAwareTrait;
    use LimitInfoAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isReadOnlyDb
     * @return array - return values of bidders
     */
    public function loadBidders(bool $isReadOnlyDb = false): array
    {
        $rows = $this->createAuctionBidderReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->joinUserInfo()
            ->filterAuctionId($this->getFilterAuctionId())
            ->inlineCondition("aub.bidder_num IS NOT NULL OR aub.bidder_num != ''")
            ->inlineCondition(
                "(SELECT COUNT(1) FROM absentee_bid ab"
                . " WHERE ab.auction_id = {$this->escape($this->getFilterAuctionId())}"
                . " AND ab.lot_item_id = {$this->escape($this->getFilterLotItemId())}"
                . " AND ab.user_id = aub.user_id"
                . " AND ab.max_bid > 0"
                . ") = 0"
            )
            ->orderByBidderNum()
            ->select(
                [
                    'u.id',
                    'u.customer_no',
                    'ui.first_name',
                    'ui.last_name',
                    'aub.bidder_num'
                ]
            )
            ->loadRows();
        $dtos = [];
        foreach ($rows as $row) {
            $dtos[] = AuctionLotPresaleDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @param bool $isReadOnlyDb
     * @return int - return value of absentees count
     */
    public function countAbsenteeBids(bool $isReadOnlyDb = false): int
    {
        return $this->prepareAbsenteeBidReadRepository($isReadOnlyDb)->count();
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AbsenteeBid[] - return array of absentees
     */
    public function loadAbsenteeBids(bool $isReadOnlyDb = false): array
    {
        return $this->prepareAbsenteeBidReadRepository($isReadOnlyDb)
            ->orderByMaxBid(false)
            ->orderById()
            ->offset($this->getOffset())
            ->limit($this->getLimit())
            ->loadEntities();
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AbsenteeBidReadRepository
     */
    protected function prepareAbsenteeBidReadRepository(bool $isReadOnlyDb = false): AbsenteeBidReadRepository
    {
        return $this->createAbsenteeBidReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->filterLotItemId($this->getFilterLotItemId())
            ->filterAuctionId($this->getFilterAuctionId())
            ->inlineCondition(
                "ab.bid_type = " . Constants\Bid::ABT_PHONE
                . " OR (ab.bid_type = " . Constants\Bid::ABT_REGULAR . " AND ab.max_bid > 0)"
            );
    }
}
