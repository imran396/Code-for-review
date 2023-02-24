<?php
/**
 * SAM-5657: Refactor data loader for Assigned lot datagrid at Auction Lot List page at admin side
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/26/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load;

use LotItemCustField;
use Sam\AuctionLot\Order\Query\AuctionLotOrderMysqlQueryBuilderCreateTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Lot\Search\Query\LotSearchQuery;
use Sam\Qform\LotList\LotListCustomFieldAdminFilterHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Admin\Form\AuctionLotListForm\Assigned\AssignedAuctionLotListConstants;
use Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Dto\AssignedLotDto;
use Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Query\AssignedAuctionLotListQueryBuilder;
use Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Query\AssignedAuctionLotListQueryBuilderCreateTrait;
use Sam\View\Admin\Form\AuctionLotListForm\Assigned\Load\Query\AssignedAuctionLotListQueryCriteria;

/**
 * Class AssignedAuctionLotListDataLoader
 * @package
 */
class AssignedAuctionLotListDataLoader extends CustomizableClass
{
    use AssignedAuctionLotListQueryBuilderCreateTrait;
    use AuctionAwareTrait;
    use AuctionLotOrderMysqlQueryBuilderCreateTrait;
    use BaseCustomFieldHelperAwareTrait;
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotCustomFieldsAwareTrait;
    use LotListCustomFieldAdminFilterHelperAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    protected ?int $filterBiddingUserId = null;
    protected ?int $filterItemNum = null;
    protected ?string $filterKeyword = null;
    protected int $filterKeywordOption = AssignedAuctionLotListQueryBuilder::LOT_ITEM_KEYWORD_OPTION_INDEX;
    protected ?array $filterLotCategoryIds = null;
    protected ?int $filterLotConsignorUserId = null;
    protected ?int $filterLotStatus = null;
    protected ?int $filterWinningUserId = null;
    protected ?int $filterBidCountStrategy = null;
    protected ?int $filterReserveMeetStrategy = null;
    protected ?array $orderFieldsMapping = null;
    protected bool $isAuctionReverse = false;
    protected array $orderFieldsMappingDefault = [
        AssignedAuctionLotListConstants::ORD_LOT_ORDER => [
            'asc' => 'ali.`order` ASC',
            'desc' => 'ali.`order` DESC',
        ],
        AssignedAuctionLotListConstants::ORD_IMAGE_COUNT => [
            'asc' => 'image_count ASC',
            'desc' => 'image_count DESC',
        ],
        AssignedAuctionLotListConstants::ORD_LOT_NO => [
            'asc' => 'ali.lot_num_prefix ASC, ali.lot_num ASC, ali.lot_num_ext ASC',
            'desc' => 'ali.lot_num_prefix DESC, ali.lot_num DESC, ali.lot_num_ext DESC',
        ],
        AssignedAuctionLotListConstants::ORD_ITEM_NO => [
            'asc' => 'li.item_num ASC, li.item_num_ext ASC',
            'desc' => 'li.item_num DESC, li.item_num_ext DESC',
        ],
        AssignedAuctionLotListConstants::ORD_GROUP => [
            'asc' => 'ali.group_id ASC',
            'desc' => 'ali.group_id DESC',
        ],
        AssignedAuctionLotListConstants::ORD_LOT_NAME => [
            'asc' => 'li.name ASC',
            'desc' => 'li.name DESC',
        ],
        AssignedAuctionLotListConstants::ORD_QUANTITY => [
            'asc' => 'ali.quantity ASC',
            'desc' => 'ali.quantity DESC',
        ],
        AssignedAuctionLotListConstants::ORD_ESTIMATE => [
            'asc' => 'li.low_estimate ASC',
            'desc' => 'li.low_estimate DESC',
        ],
        AssignedAuctionLotListConstants::ORD_STARTING_BID => [
            'asc' => 'li.starting_bid ASC',
            'desc' => 'li.starting_bid DESC',
        ],
        AssignedAuctionLotListConstants::ORD_HAMMER_PRICE => [
            'asc' => 'li.hammer_price ASC',
            'desc' => 'li.hammer_price DESC',
        ],
        AssignedAuctionLotListConstants::ORD_RESERVE_PRICE => [
            'asc' => 'li.reserve_price ASC',
            'desc' => 'li.reserve_price DESC',
        ],
        AssignedAuctionLotListConstants::ORD_WINNER => [
            'asc' => 'uw.username ASC',
            'desc' => 'uw.username DESC',
        ],
        AssignedAuctionLotListConstants::ORD_WINNER_COMPANY => [
            'asc' => 'uwi.company_name ASC',
            'desc' => 'uwi.company_name DESC',
        ],
        AssignedAuctionLotListConstants::ORD_WINNER_EMAIL => [
            'asc' => 'uw.email ASC',
            'desc' => 'uw.email DESC',
        ],
        AssignedAuctionLotListConstants::ORD_INTERNET_BID => [
            'asc' => 'li.internet_bid ASC',
            'desc' => 'li.internet_bid DESC',
        ],
        AssignedAuctionLotListConstants::ORD_CURRENT_BID_TIMED => [
            'asc' => 'alic.current_bid ASC',
            'desc' => 'alic.current_bid DESC',
        ],
        AssignedAuctionLotListConstants::ORD_LOT_BID_COUNT => [
            'asc' => 'bid_count ASC',
            'desc' => 'bid_count DESC',
        ],
        AssignedAuctionLotListConstants::ORD_CURRENT_BID_LIVE => [
            'asc' => 'alic.current_bid ASC, alic.bid_count ASC',
            'desc' => 'alic.current_bid DESC, alic.bid_count DESC',
        ],
        AssignedAuctionLotListConstants::ORD_CURRENT_MAX_BID => [
            'asc' => 'alic.current_max_bid ASC',
            'desc' => 'alic.current_max_bid DESC',
        ],
        AssignedAuctionLotListConstants::ORD_CONSIGNOR => [
            'asc' => 'uc.username ASC',
            'desc' => 'uc.username DESC',
        ],
        AssignedAuctionLotListConstants::ORD_CONSIGNOR_COMPANY => [
            'asc' => 'uci.company_name ASC',
            'desc' => 'uci.company_name DESC',
        ],
        AssignedAuctionLotListConstants::ORD_CONSIGNOR_EMAIL => [
            'asc' => 'uc.email ASC',
            'desc' => 'uc.email DESC',
        ],
        AssignedAuctionLotListConstants::ORD_VIEW_COUNT => [
            'asc' => 'alic.view_count ASC',
            'desc' => 'alic.view_count DESC',
        ],
        AssignedAuctionLotListConstants::ORD_LOT_STATUS => [
            'asc' => 'ali.lot_status_id ASC',
            'desc' => 'ali.lot_status_id DESC',
        ],
        AssignedAuctionLotListConstants::ORD_EDITOR => [
            'asc' => 'ucr.username ASC, umo.username ASC',
            'desc' => 'ucr.username DESC, umo.username DESC',
        ],
        AssignedAuctionLotListConstants::ORD_LOT_END_DATE => [
            'asc' => 'lot_end_date ASC',
            'desc' => 'lot_end_date DESC',
        ],
        AssignedAuctionLotListConstants::ORD_LOT_HIGH_BIDDER => [
            'asc' => 'high_bidder_username ASC',
            'desc' => 'high_bidder_username DESC',
        ],
        AssignedAuctionLotListConstants::ORD_LOT_HIGH_BIDDER_COMPANY => [
            'asc' => 'high_bidder_company ASC',
            'desc' => 'high_bidder_company DESC',
        ],
        AssignedAuctionLotListConstants::ORD_LOT_HIGH_BIDDER_EMAIL => [
            'asc' => 'high_bidder_email ASC',
            'desc' => 'high_bidder_email DESC',
        ],
        AssignedAuctionLotListConstants::ORD_LOT_LAST_BID_TIME => [
            'asc' => 'alic.current_bid_placed ASC',
            'desc' => 'alic.current_bid_placed DESC',
        ],
    ];
    protected ?string $resultQueryNoPagination = null;
    protected ?LotSearchQuery $queryCache = null;

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
        $lotOrderClauseAsc = $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause();
        $lotOrderClauseDesc = $this->createAuctionLotOrderMysqlQueryBuilder()->buildLotOrderClause(false);
        $descForDescAliases = [
            AssignedAuctionLotListConstants::ORD_IMAGE_COUNT,
            AssignedAuctionLotListConstants::ORD_ITEM_NO,
            AssignedAuctionLotListConstants::ORD_LOT_ORDER,
            AssignedAuctionLotListConstants::ORD_LOT_STATUS,
        ];
        foreach ($this->orderFieldsMappingDefault as $alias => $props) {
            $this->orderFieldsMappingDefault[$alias]['asc'] .= ", {$lotOrderClauseAsc}";
            if (in_array($alias, $descForDescAliases, true)) {
                $this->orderFieldsMappingDefault[$alias]['desc'] .= ", {$lotOrderClauseDesc}";
            } else {
                $this->orderFieldsMappingDefault[$alias]['desc'] .= ", {$lotOrderClauseAsc}";
            }
        }
        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $countQuery = $this->getQuery()->getCountSql();
        $this->query($countQuery);
        $row = $this->fetchAssoc();
        $total = (int)$row['lot_total'];
        return $total;
    }

    /**
     * @return AssignedLotDto[]
     */
    public function load(): array
    {
        $adminCatalogCustomFields = $this->createLotCustomFieldLoader()->loadInAdminCatalog();
        $adminCatalogCustomFieldAliases = array_map(
            function (LotItemCustField $lotCustomField) {
                return $this->getBaseCustomFieldHelper()->makeFieldAlias($lotCustomField->Name);
            },
            $adminCatalogCustomFields
        );

        $resultQuery = $this->getResultQueryNoPagination() . $this->buildLimitClause();
        $this->query($resultQuery);
        $rows = $this->fetchAllAssoc();
        $dtos = array_map(
            static function (array $row) use ($adminCatalogCustomFieldAliases) {
                return AssignedLotDto::new()->fromDbRow($row, $adminCatalogCustomFieldAliases);
            },
            $rows
        );
        return $dtos;
    }

    /**
     * @return string
     */
    public function getResultQueryNoPagination(): string
    {
        if ($this->resultQueryNoPagination === null) {
            $this->resultQueryNoPagination = $this->buildResultQueryNoPagination();
        }
        return $this->resultQueryNoPagination;
    }

    /**
     * @return array
     */
    public function getOrderFieldsMapping(): array
    {
        if ($this->orderFieldsMapping === null) {
            $this->orderFieldsMapping = $this->buildOrderFieldMapping();
        }
        return $this->orderFieldsMapping;
    }

    /**
     * @param array $orderFieldsMapping
     * @return static
     * @noinspection PhpUnused
     */
    public function setOrderFieldsMapping(array $orderFieldsMapping): static
    {
        $this->orderFieldsMapping = $orderFieldsMapping;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterLotConsignorUserId(): ?int
    {
        return $this->filterLotConsignorUserId;
    }

    /**
     * @param int|null $userId
     * @return static
     */
    public function filterLotConsignorUserId(?int $userId): static
    {
        $this->filterLotConsignorUserId = $userId;
        return $this;
    }

    /**
     * @return int[]|null
     */
    public function getFilterLotCategoryIds(): ?array
    {
        return $this->filterLotCategoryIds;
    }

    /**
     * @param int[] $filterLotCategoryIds
     * @return static
     */
    public function filterLotCategoryIds(array $filterLotCategoryIds): static
    {
        $this->filterLotCategoryIds = $filterLotCategoryIds;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterItemNum(): ?int
    {
        return $this->filterItemNum;
    }

    /**
     * @param int|null $filterItemNum
     * @return static
     */
    public function filterItemNum(?int $filterItemNum): static
    {
        $this->filterItemNum = $filterItemNum;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterLotStatus(): ?int
    {
        return $this->filterLotStatus;
    }

    /**
     * @param int|null $filterLotStatus
     * @return static
     */
    public function filterLotStatus(?int $filterLotStatus): static
    {
        $this->filterLotStatus = $filterLotStatus;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterBiddingUserId(): ?int
    {
        return $this->filterBiddingUserId;
    }

    /**
     * @param int|null $filterBiddingUserId
     * @return static
     */
    public function filterBiddingUserId(?int $filterBiddingUserId): static
    {
        $this->filterBiddingUserId = $filterBiddingUserId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterWinningUserId(): ?int
    {
        return $this->filterWinningUserId;
    }

    /**
     * @param int|null $filterWinningUserId
     * @return static
     */
    public function filterWinningUserId(?int $filterWinningUserId): static
    {
        $this->filterWinningUserId = $filterWinningUserId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterBidCountStrategy(): ?int
    {
        return $this->filterBidCountStrategy;
    }

    /**
     * @param int|null $bidCountStrategy can be null if we dont want to filter by bid count
     * @return static
     */
    public function filterBidCountStrategy(?int $bidCountStrategy): static
    {
        $this->filterBidCountStrategy = $bidCountStrategy;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterReserveMeetStrategy(): ?int
    {
        return $this->filterReserveMeetStrategy;
    }

    /**
     * @param int|null $reserveMeetStrategy can be null if we dont want to filter by reserve
     * @return static
     */
    public function filterReserveMeetStrategy(?int $reserveMeetStrategy): static
    {
        $this->filterReserveMeetStrategy = $reserveMeetStrategy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterKeyword(): ?string
    {
        return $this->filterKeyword;
    }

    /**
     * @param string $searchKey
     * @return static
     */
    public function filterKeyword(string $searchKey): static
    {
        $this->filterKeyword = trim($searchKey);
        return $this;
    }

    /**
     * @return int
     */
    public function getFilterKeywordOption(): int
    {
        return $this->filterKeywordOption;
    }

    /**
     * @param int $filterKeywordOption
     * @return static
     */
    public function filterKeywordOption(int $filterKeywordOption): static
    {
        $this->filterKeywordOption = $filterKeywordOption;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuctionReverse(): bool
    {
        return $this->isAuctionReverse;
    }

    /**
     * @param bool $isReverse
     * @return static
     */
    public function enableAuctionReverse(bool $isReverse): static
    {
        $this->isAuctionReverse = $isReverse;
        return $this;
    }

    /**
     * @return LotSearchQuery
     */
    protected function getQuery(): LotSearchQuery
    {
        if ($this->queryCache === null) {
            $this->queryCache = $this->buildQuery();
        }
        return clone $this->queryCache;
    }

    /**
     * @return string
     */
    protected function buildResultQueryNoPagination(): string
    {
        $sql = $this->getQuery()->getSql();
        return $sql;
    }

    /**
     * @return LotSearchQuery
     */
    protected function buildQuery(): LotSearchQuery
    {
        $customFields = $this->getLotListCustomFieldAdminFilterHelper()->getCustomFields();
        $selectCustomFields = $this->getLotCustomFields();

        $criteria = AssignedAuctionLotListQueryCriteria::new();
        $criteria->accountId = $this->getAuction()->AccountId;
        $criteria->auctionId = $this->getFilterAuctionId();
        $criteria->isAuctionReverse = $this->isAuctionReverse();
        $criteria->bidCountStrategy = $this->getFilterBidCountStrategy();
        $criteria->bidderId = $this->getFilterBiddingUserId();
        $criteria->categoryIds = $this->getFilterLotCategoryIds();
        $criteria->consignorId = $this->getFilterLotConsignorUserId();
        $criteria->lotCustomFieldsValue = $this->getLotListCustomFieldAdminFilterHelper()->getFilterParams();
        $criteria->searchKey = $this->getFilterKeyword();
        $criteria->keywordSearchOption = $this->getFilterKeywordOption();
        $criteria->lotItemNum = $this->getFilterItemNum();
        $criteria->lotStatusId = $this->getFilterLotStatus();
        $criteria->reserveMeetStrategy = $this->getFilterReserveMeetStrategy();
        $criteria->winningBidderId = $this->getFilterWinningUserId();
        $criteria->orderBy = $this->getOrderByExpression();

        $query = $this->createAssignedAuctionLotListQueryBuilder()
            ->build($criteria, $customFields, $selectCustomFields);

        return $query;
    }

    /**
     * Get ORDER BY expression
     * @return string|null
     */
    protected function getOrderByExpression(): ?string
    {
        $orderBy = null;
        $mapping = $this->getOrderFieldsMapping();
        $allAliases = array_keys($mapping);
        $alias = Cast::toString($this->getSortColumn(), $allAliases)
            ?: AssignedAuctionLotListConstants::ORD_DEFAULT;
        $direction = $this->isAscendingOrder() ? 'asc' : 'desc';
        if (isset($mapping[$alias][$direction])) {
            $orderBy = $mapping[$alias][$direction];
        }
        return $orderBy;
    }

    /**
     * @return string
     */
    protected function buildLimitClause(): string
    {
        $limit = $this->getLimit();
        if ($limit === null) {
            return '';
        }
        $query = $limit;

        $offset = $this->getOffset();
        if ($offset) {
            $query = "{$offset}, {$limit}";
        }
        return sprintf(' LIMIT %s ', $query);
    }

    /**
     * Initialize order field mapping by default expressions and custom field related expressions
     */
    protected function buildOrderFieldMapping(): array
    {
        $mapping = $this->orderFieldsMappingDefault;
        foreach ($this->getLotCustomFields() as $lotCustomField) {
            $alias = $this->getBaseCustomFieldHelper()->makeFieldAlias($lotCustomField->Name);
            $mapping[$alias] = [
                'asc' => "{$alias} ASC",
                'desc' => "{$alias} DESC",
            ];
        }
        return $mapping;
    }
}
