<?php
/**
 * Auction Phone Bidder Data Loader
 *
 * SAM-5817: Refactor data loader for Auction Phone Bidder page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 18, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionPhoneBidderForm\Load;

use Sam\AuctionLot\Order\Query\AuctionLotOrderMysqlQueryBuilderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Filter\Entity\FilterUserAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Auction\PhoneBidder\QueryBuilder;
use Sam\Storage\ReadRepository\Entity\AbsenteeBid\AbsenteeBidReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\PhoneBidderDedicatedClerk\PhoneBidderDedicatedClerkReadRepositoryCreateTrait;

/**
 * Class AuctionPhoneBidderDataLoader
 */
class AuctionPhoneBidderDataLoader extends CustomizableClass
{
    use AbsenteeBidReadRepositoryCreateTrait;
    use AuctionLotOrderMysqlQueryBuilderCreateTrait;
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;
    use FilterUserAwareTrait;
    use PhoneBidderDedicatedClerkReadRepositoryCreateTrait;

    protected string $filterAssignedClerk = '';
    protected string $filterAssignedClerkText = '';
    protected string $filterLastLotNumPrefix = '';
    protected string $filterLastLotNumExt = '';
    protected string $filterPhoneBidNumPrefix = '';
    protected string $filterPhoneBidNumExt = '';
    protected ?int $filterLastLotNum = null;
    protected ?int $filterPhoneBidNum = null;
    protected ?int $filterLotItemId = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $assignedClerk
     * @return $this
     */
    public function filterAssignedClerk(string $assignedClerk): static
    {
        $this->filterAssignedClerk = $assignedClerk;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterAssignedClerk(): string
    {
        return $this->filterAssignedClerk;
    }

    /**
     * @param string $assignedClerkText
     * @return $this
     */
    public function filterAssignedClerkText(string $assignedClerkText): static
    {
        $this->filterAssignedClerkText = $assignedClerkText;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterAssignedClerkText(): string
    {
        return $this->filterAssignedClerkText;
    }

    /**
     * @param string $lastLotNumPrefix
     * @return $this
     */
    public function filterLastLotNumPrefix(string $lastLotNumPrefix): static
    {
        $this->filterLastLotNumPrefix = $lastLotNumPrefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterLastLotNumPrefix(): string
    {
        return $this->filterLastLotNumPrefix;
    }

    /**
     * @param int|null $lastLotNum null means no lot number
     * @return $this
     */
    public function filterLastLotNum(?int $lastLotNum): static
    {
        $this->filterLastLotNum = $lastLotNum;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterLastLotNum(): ?int
    {
        return $this->filterLastLotNum;
    }

    /**
     * @param string $lastLotNumExt
     * @return $this
     */
    public function filterLastLotNumExt(string $lastLotNumExt): static
    {
        $this->filterLastLotNumExt = $lastLotNumExt;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterLastLotNumExt(): string
    {
        return $this->filterLastLotNumExt;
    }

    /**
     * @param string $phoneBidNumPrefix
     * @return $this
     */
    public function filterPhoneBidNumPrefix(string $phoneBidNumPrefix): static
    {
        $this->filterPhoneBidNumPrefix = $phoneBidNumPrefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterPhoneBidNumPrefix(): string
    {
        return $this->filterPhoneBidNumPrefix;
    }

    /**
     * @param int|null $phoneBidNum null means no lot number
     * @return $this
     */
    public function filterPhoneBidNum(?int $phoneBidNum): static
    {
        $this->filterPhoneBidNum = $phoneBidNum;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterPhoneBidNum(): ?int
    {
        return $this->filterPhoneBidNum;
    }

    /**
     * @param string $phoneBidNumExt
     * @return $this
     */
    public function filterPhoneBidNumExt(string $phoneBidNumExt): static
    {
        $this->filterPhoneBidNumExt = $phoneBidNumExt;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilterPhoneBidNumExt(): string
    {
        return $this->filterPhoneBidNumExt;
    }

    /**
     * @param int|null $lotItemId null means no lot item
     * @return $this
     */
    public function filterLotItemId(?int $lotItemId): static
    {
        $this->filterLotItemId = $lotItemId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterLotItemId(): ?int
    {
        return $this->filterLotItemId;
    }

    /**
     * @return AuctionPhoneBidderItemsToLstBidderDto[] - return values of Items to Bidder list
     */
    public function loadItemsToLstBidder(): array
    {
        // @formatter:off
        $query = 'SELECT'
                . ' DISTINCT ab.user_id,'
                . ' aub.bidder_num,'
                . ' ui.first_name,'
                . ' ui.last_name,'
                . ' u.username'
            . ' FROM auction_lot_item ali'
            . ' LEFT JOIN absentee_bid ab'
                . ' ON ali.auction_id = ab.auction_id'
                . ' AND ali.lot_item_id = ab.lot_item_id'
            . ' LEFT JOIN auction_bidder aub'
                . ' ON ab.auction_id = aub.auction_id'
                . ' AND ab.user_id = aub.user_id'
            . ' LEFT JOIN user u'
                . ' ON u.id = ab.user_id'
            . ' LEFT JOIN user_info ui'
                . ' ON ui.user_id = ab.user_id'
            . ' WHERE'
                . ' ali.auction_id = ' . $this->escape($this->getFilterAuctionId())
                . ' AND ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')'
                . ' AND ab.bid_type = ' . Constants\Bid::ABT_PHONE
            . ' ORDER BY'
                 . ' ali.lot_num_prefix,'
                 . ' ali.lot_num,'
                 . ' ali.lot_num_ext,'
                 . ' IF(ab.placed_on is null,ab.created_on,ab.placed_on)';

        $this->query($query);
        $dtos = [];
        foreach ($this->fetchAllAssoc() as $row) {
            $dtos[] = AuctionPhoneBidderItemsToLstBidderDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @return AuctionPhoneBiddersDto[] - return values for Auction Phone Bidders
     */
    public function loadPhoneBidders(): array
    {
        $queryParts = $this->buildQueryParts(['AuctionId' => $this->getFilterAuctionId(), 'AllLots' => 1]);
        $select = $queryParts['select'];
        $from = $queryParts['from'];
        $where = $queryParts['where'];
        $order = $queryParts['order'];
        $resultQuery = "{$select}{$from}{$where}{$order}";
        $this->query($resultQuery);

        $phoneBidDtos = [];
        while ($row = $this->fetchAssoc()) {
            $phoneBidDtos[] = AuctionPhoneBiddersDto::new()->fromDbRow($row);
        }

        return $phoneBidDtos;
    }

    /**
     * @return AuctionPhoneBidderLastLotDto|null
     */
    public function loadLastLot(): ?AuctionPhoneBidderLastLotDto
    {
        // @formatter:off
        $queryLL = 'SELECT'
                . ' user_id AS last_assigned,'
                . ' ali.lot_num_prefix,'
                . ' ali.lot_num,'
                . ' ali.lot_num_ext'
            . ' FROM auction_lot_item ali'
            . ' LEFT JOIN absentee_bid ab2'
                . ' ON ali.auction_id = ab2.auction_id'
                . ' AND ali.lot_item_id = ab2.lot_item_id'
            . ' WHERE ali.lot_status_id IN ('.implode(',',Constants\Lot::$availableLotStatuses).')'
                . ' AND ab2.auction_id = ' . $this->escape($this->getFilterAuctionId())
                . ' AND ab2.bid_type = ' . Constants\Bid::ABT_PHONE
                . ' AND ab2.assigned_clerk = ' . $this->escape($this->getFilterAssignedClerkText())
            . ' ORDER BY'
                . ' ali.lot_num_prefix DESC,'
                . ' ali.lot_num DESC,'
                . ' ali.lot_num_ext DESC,'
                . ' IF(ab2.placed_on is null,ab2.created_on ,ab2.placed_on) DESC'
            . ' LIMIT 1';
        // @formatter:on
        $this->query($queryLL);

        $dto = null;
        foreach ($this->fetchAllAssoc() as $row) {
            $dto = AuctionPhoneBidderLastLotDto::new()->fromDbRow($row);
        }
        return $dto;
    }

    /**
     * @return int - return value of Lots between count
     */
    public function countLotsBetween(): int
    {
        // @formatter:off
        $queryBL = 'SELECT ali.id'
            . ' FROM auction_lot_item ali'
            . ' LEFT JOIN absentee_bid ab2'
                . ' ON ali.auction_id = ab2.auction_id'
                . ' AND ali.lot_item_id = ab2.lot_item_id'
            . ' WHERE ali.lot_status_id IN (' . implode(',', Constants\Lot::$availableLotStatuses) . ')'
                . ' AND ab2.auction_id = ' . $this->escape($this->getFilterAuctionId())
                . ' AND ab2.bid_type = ' . Constants\Bid::ABT_PHONE;
        // @formatter:on
        //'AND CONCAT(IFNULL(ali.lot_num_prefix,\'\'),ali.lot_num,IFNULL(ali.lot_num_ext,\'\')) > ' .  $db->SqlVariable($strLotLL) . ' ' .
        //'AND CONCAT(IFNULL(ali.lot_num_prefix,\'\'),ali.lot_num,IFNULL(ali.lot_num_ext,\'\')) < ' .  $db->SqlVariable($strLotCL) . ' ';

        if ($this->getFilterLastLotNumPrefix()) {
            $queryBL .= ' AND ali.lot_num_prefix > ' . $this->escape($this->getFilterLastLotNumPrefix());
        }
        if ($this->getFilterLastLotNum()) {
            $queryBL .= ' AND ali.lot_num > ' . $this->escape($this->getFilterLastLotNum());
        }
        if ($this->getFilterLastLotNumExt()) {
            $queryBL .= ' AND ali.lot_num_ext > ' . $this->escape($this->getFilterLastLotNumExt());
        }

        if ($this->getFilterPhoneBidNumPrefix()) {
            $queryBL .= ' AND ali.lot_num_prefix < ' . $this->escape($this->getFilterPhoneBidNumPrefix());
        }
        if ($this->getFilterPhoneBidNum()) {
            $queryBL .= ' AND ali.lot_num < ' . $this->escape($this->getFilterPhoneBidNum());
        }
        if ($this->getFilterPhoneBidNumExt()) {
            $queryBL .= ' AND ali.lot_num_ext < ' . $this->escape($this->getFilterPhoneBidNumExt());
        }
        $queryBL .= ' GROUP BY ali.lot_item_id';

        $this->query($queryBL);

        return $this->countRows();
    }

    /**
     * @return string
     */
    public function loadDedicatedClerk(): string
    {
        $row = $this->createPhoneBidderDedicatedClerkReadRepository()
            ->filterAuctionId($this->getFilterAuctionId())
            ->filterBidderId($this->getFilterUserId())
            ->select(['assigned_clerk'])
            ->loadRow();
        return $row['assigned_clerk'] ?? '';
    }

    /**
     * @return bool
     */
    public function hasDedicatedClerkForLot(): bool
    {
        return $this->createPhoneBidderDedicatedClerkReadRepository()
            ->joinAbsenteeBidFilterAuctionId($this->getFilterAuctionId())
            ->joinAbsenteeBidFilterLotItemId($this->getFilterLotItemId())
            ->filterAssignedClerk($this->getFilterAssignedClerk())
            ->exist();
    }

    /**
     * @return bool
     */
    public function hasClerkForLot(): bool
    {
        $dtoLL = $this->loadLastLot();
        if ($dtoLL) {
            $lb = $this->countLotsBetween();
            $lastAssigned = $dtoLL->lastAssigned;
            if (Floating::eq($lb, 0)) {
                $willAssigned = $this->createAbsenteeBidReadRepository()
                    ->filterAssignedClerk('')
                    ->filterAuctionId($this->getFilterAuctionId())
                    ->filterBidType(Constants\Bid::ABT_PHONE)
                    ->filterLotItemId($this->getFilterLotItemId())
                    ->filterUserId($lastAssigned)
                    ->joinAccountFilterActive(true)
                    ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
                    ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                    ->joinLotItemFilterActive(true)
                    ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
                    ->count();
                return $willAssigned > 0 && $lastAssigned !== $this->getFilterUserId();
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasDedicatedClerkForNextLot(): bool
    {
        // @formatter:off
        $queryNL = "SELECT ali.*"
            . " FROM"
                . " auction_lot_item ali, "
                . " auction_lot_item ali2,"
                . " lot_item li "
            . " WHERE"
                . " ali.auction_id = " . $this->escape($this->getFilterAuctionId())
                . " AND li.id=ali.lot_item_id"
                . " AND li.active"
                . " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")"
                . " AND ali2.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")"
                . " AND ali2.lot_item_id = " . $this->escape($this->getFilterLotItemId())
                . " AND ali2.auction_id = ali.auction_id"
                . " AND " . $this->createAuctionLotOrderMysqlQueryBuilder()->buildNextLotsWhereClause()
            . " ORDER BY " . $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause()
            . " LIMIT 1";
        // @formatter:on
        $this->query($queryNL);
        $nextLotRow = $this->fetchAssoc();
        if (!empty($nextLotRow)) {
            $lotNL = $nextLotRow['lot_num_prefix'] . $nextLotRow['lot_num'] . $nextLotRow['lot_num_ext']; // Next lot
            return $this->createPhoneBidderDedicatedClerkReadRepository()
                ->joinAbsenteeBidFilterAuctionId($this->getFilterAuctionId())
                ->joinAbsenteeBidFilterBidType(Constants\Bid::ABT_PHONE)
                ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                ->joinAuctionLotItemFilterLotNL($lotNL)
                ->filterAssignedClerk($this->getFilterAssignedClerk())
                ->exist();
        }
        return false;
    }

    /**
     * @return bool
     */
    public function hasDedicatedClerkForCurrLot(): bool
    {
        return $this->createPhoneBidderDedicatedClerkReadRepository()
            ->joinAbsenteeBidFilterAuctionId($this->getFilterAuctionId())
            ->joinAbsenteeBidFilterBidType(Constants\Bid::ABT_PHONE)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuctionLotItemFilterLotItemId($this->getFilterLotItemId())
            ->filterAssignedClerk($this->getFilterAssignedClerk())
            ->exist();
    }

    /**
     * @return bool
     */
    public function hasPrevClerkForCurrentLot(): bool
    {
        // @formatter:off
        $queryPL = "SELECT ali.*"
            . " FROM"
                . " auction_lot_item ali,"
                . " auction_lot_item ali2,"
                . " lot_item li,"
                . " absentee_bid ab"
            . " WHERE"
                . " ali.auction_id = " . $this->escape($this->getFilterAuctionId())
                . " AND ali.auction_id = ab.auction_id"
                . " AND ali.lot_item_id = ab.lot_item_id "
                . " AND li.id=ali.lot_item_id"
                . " AND li.active"
                . " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")"
                . " AND ali2.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")"
                . " AND ali2.lot_item_id = " . $this->escape($this->getFilterAssignedClerk())
                . " AND ali2.auction_id = ali.auction_id"
                . " AND " . $this->createAuctionLotOrderMysqlQueryBuilder()->buildPrevLotsWhereClause()
                . ' AND ab.bid_type = ' . Constants\Bid::ABT_PHONE
            . " ORDER BY " . $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause(false)
            . " LIMIT 1 ";
        // @formatter:on
        $this->query($queryPL);
        $prevLotRow = $this->fetchAssoc();
        if (!empty($prevLotRow)) {
            $lotPL = $prevLotRow['lot_num_prefix'] . $prevLotRow['lot_num'] . $prevLotRow['lot_num_ext']; // Next lot
            $assignedClerk = 'Clerk' . $this->getFilterAssignedClerk();
            $row = $this->createAbsenteeBidReadRepository()
                ->filterAssignedClerk($assignedClerk)
                ->filterAuctionId($this->getFilterAuctionId())
                ->joinAccountFilterActive(true)
                ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
                ->joinAuctionLotItemFilterLotPl($lotPL)
                ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                ->joinLotItemFilterActive(true)
                ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
                ->select(['user_id'])
                ->loadRow();
            $userId = Cast::toInt($row['user_id'] ?? null);
            if ($userId) {
                $isFount = $this->createAbsenteeBidReadRepository()
                    ->filterAuctionId($this->getFilterAuctionId())
                    ->filterLotItemId($this->getFilterLotItemId())
                    ->filterUserId($userId)
                    ->joinAccountFilterActive(true)
                    ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
                    ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                    ->joinLotItemFilterActive(true)
                    ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
                    ->exist();
                return $isFount;
            }
        }
        return false;
    }

    /**
     * @return int|null
     */
    public function loadPrevLotAssignedClerk(): ?int
    {
        // @formatter:off
        $queryPL = "SELECT ali.*"
            . " FROM"
                . " auction_lot_item ali,"
                . " auction_lot_item ali2,"
                . " lot_item li,"
                . " absentee_bid ab"
            . " WHERE"
                . " ali.auction_id = " . $this->escape($this->getFilterAuctionId())
                . " AND ali.auction_id = ab.auction_id"
                . " AND ali.lot_item_id = ab.lot_item_id"
                . " AND li.id=ali.lot_item_id"
                . " AND li.active"
                . " AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")"
                . " AND ali2.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ")"
                . " AND ali2.lot_item_id = " . $this->escape($this->getFilterLotItemId())
                . " AND ali2.auction_id = ali.auction_id"
                . " AND " . $this->createAuctionLotOrderMysqlQueryBuilder()->buildPrevLotsWhereClause()
                . ' AND ab.bid_type = ' . Constants\Bid::ABT_PHONE
            . " ORDER BY " . $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause(false)
            . " LIMIT 1 ";
        // @formatter:on
        $this->query($queryPL);
        $row = $this->fetchAssoc();
        if (!empty($row)) {
            $lotPl = $row['lot_num_prefix'] . $row['lot_num'] . $row['lot_num_ext']; // Next lot
            $acRow = $this->createAbsenteeBidReadRepository()
                ->filterAuctionId($this->getFilterAuctionId())
                ->filterBidType(Constants\Bid::ABT_PHONE)
                ->filterUserId($this->getFilterUserId())
                ->joinAccountFilterActive(true)
                ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
                ->joinAuctionLotItemFilterLotPl($lotPl)
                ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                ->joinLotItemFilterActive(true)
                ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
                ->select(['assigned_clerk'])
                ->loadRow();
            $assignedClerk = $acRow['assigned_clerk'] ?? '--';
            return $assignedClerk !== '--'
                ? (int)str_replace('Clerk', '', $acRow['assigned_clerk'])
                : null;
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isAlreadyAssigned(): bool
    {
        return $this->createAbsenteeBidReadRepository()
            ->filterAssignedClerk($this->getFilterAssignedClerkText())
            ->filterAuctionId($this->getFilterAuctionId())
            ->filterBidType(Constants\Bid::ABT_PHONE)
            ->filterLotItemId($this->getFilterLotItemId())
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->exist();
    }

    /**
     * @return array
     */
    public function loadPhoneBidderDedicatedClerks(): array
    {
        return $this->createPhoneBidderDedicatedClerkReadRepository()
            ->filterAuctionId($this->getFilterAuctionId())
            ->select(['bidder_id', 'assigned_clerk', 'bidder_id'])
            ->loadRows();
    }

    /**
     * @return bool
     */
    public function hasDedicatedClerkForBidder(): bool
    {
        return $this->createPhoneBidderDedicatedClerkReadRepository()
            ->filterAuctionId($this->getFilterAuctionId())
            ->filterAssignedClerk($this->getFilterAssignedClerk())
            ->exist();
    }

    /**
     * @param array $params
     * @return array
     */
    protected function buildQueryParts(array $params): array
    {
        return QueryBuilder::new()->getQueryParts($params);
    }
}
