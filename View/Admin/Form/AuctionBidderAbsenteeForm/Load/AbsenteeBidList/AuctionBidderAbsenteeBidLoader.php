<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract logic and cover with unit test for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Load\AbsenteeBidList;

use Sam\Core\Constants;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepository;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\View\Admin\Form\AuctionBidderAbsenteeForm\AuctionBidderAbsenteeBidConstants;

/**
 * Class AuctionBidderAbsenteeBidLoader
 * @package Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Load\AbsenteeBidList
 */
class AuctionBidderAbsenteeBidLoader extends CustomizableClass
{
    use AbsenteeBidReadRepositoryCreateTrait;
    use AuctionAwareTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;
    use UserAwareTrait;

    private const DEFAULT_SORT_ENABLE_ASCENDING = true;

    protected bool $isNewBid = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->setSortColumn(AuctionBidderAbsenteeBidConstants::ORD_BY_LOT_FULL_NUM);
        $this->enableAscendingOrder(self::DEFAULT_SORT_ENABLE_ASCENDING);

        return $this;
    }

    /**
     * @param bool $isNewBid
     * @return static
     */
    public function enableNewBid(bool $isNewBid): static
    {
        $this->isNewBid = $isNewBid;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNewBid(): bool
    {
        return $this->isNewBid;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->prepareRepository()->count();
    }

    /**
     * @return array
     */
    public function load(): array
    {
        $rows = $this->prepareRepository()->loadRows();
        if ($this->isNewBid()) {
            $rows[] = [
                'id' => null,
                'auction_id' => null,
                'lot_item_id' => null,
                'max_bid' => null,
                'placed_on' => null,
                'bid_type' => null,
                'account_id' => null,
                'lot_num' => null,
                'lot_num_prefix' => '',
                'lot_num_ext' => '',
                'lot_seo_url' => null,
                'name' => '',
            ];
        }

        $dtos = [];
        foreach ($rows as $row) {
            $dtos[] = AuctionBidderAbsenteeBidDto::new()->fromDbRow($row);
        }

        return $dtos;
    }

    /**
     * @return AbsenteeBidReadRepository
     */
    private function prepareRepository(): AbsenteeBidReadRepository
    {
        $repository = $this->createAbsenteeBidReadRepository()
            ->filterAuctionId($this->getAuctionId())
            ->filterUserId($this->getUserId())
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemCache()
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->select(
                [
                    'ab.id AS id',
                    'ab.auction_id',
                    'ab.lot_item_id',
                    'ab.max_bid',
                    'ab.placed_on',
                    'ab.bid_type',
                    'ali.account_id',
                    'ali.lot_num',
                    'ali.lot_num_prefix',
                    'ali.lot_num_ext',
                    'alic.seo_url AS lot_seo_url',
                    'li.name',
                ]
            );
        $this->prepareRepositoryLimits($repository)
            ->prepareRepositoryOrderBy($repository);

        return $repository;
    }

    /**
     * @param AbsenteeBidReadRepository $repository
     * @return static
     */
    private function prepareRepositoryLimits(AbsenteeBidReadRepository $repository): static
    {
        if ($this->getLimit() !== null) {
            $repository->limit($this->getLimit());
        }
        if ($this->getOffset() !== null) {
            $repository->offset($this->getOffset());
        }

        return $this;
    }

    /**
     * @param AbsenteeBidReadRepository $repository
     * @return static
     */
    private function prepareRepositoryOrderBy(AbsenteeBidReadRepository $repository): static
    {
        $sortColumn = $this->getSortColumn();
        if ($sortColumn === AuctionBidderAbsenteeBidConstants::ORD_BY_MAX_BID) {
            $repository->orderByMaxBid($this->isAscendingOrder());
        } elseif ($sortColumn === AuctionBidderAbsenteeBidConstants::ORD_BY_PLACED_ON) {
            $repository->orderByPlacedOn($this->isAscendingOrder());
        } elseif ($sortColumn === AuctionBidderAbsenteeBidConstants::ORD_BY_BID_TYPE) {
            $repository->orderByBidType($this->isAscendingOrder());
        } else {
            $repository->innerJoinAuctionLotItemOrderByLotFullNumber($this->isAscendingOrder());
        }

        return $this;
    }
}
