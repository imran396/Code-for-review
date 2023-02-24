<?php
/**
 * Auction Lot List Data Loader
 *
 * SAM-5583: Refactor data loader for Assign-ready item list at Auction Lot List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 29, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\AssignReady\Load;

use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchCustomFieldQueryBuilderHelperCreateTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;
use Sam\Lot\Search\Query\LotSearchQuery;
use Sam\Lot\Search\Query\LotSearchQueryCriteria;
use Sam\Qform\LotList\LotListCustomFieldAdminFilterHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Admin\Form\AuctionLotListForm\AssignReady\AssignReadyLotListConstants;
use Sam\View\Admin\Form\AuctionLotListForm\AssignReady\Load\Dto\AssignReadyLotDto;

/**
 * Class AuctionLotListDataLoader
 * - AuctionAwareTrait is used for running entity-context auction
 * - FilterAuctionAwareTrait is used for auction defined in filter criteria
 */
class AssignReadyLotListDataLoader extends CustomizableClass
{
    use AuctionAwareTrait;
    use BaseCustomFieldHelperAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;
    use EditorUserAwareTrait;
    use FilterAuctionAwareTrait;
    use LimitInfoAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotCustomFieldsAwareTrait;
    use LotListCustomFieldAdminFilterHelperAwareTrait;
    use LotSearchCustomFieldQueryBuilderHelperCreateTrait;
    use LotSearchQueryBuilderHelperCreateTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    protected ?int $filterLotBillingStatus = null;
    protected ?int $filterOverallLotStatus = null;
    protected ?int $filterLotConsignorId = null;
    protected bool $isBlockSoldLots = false;
    protected ?string $searchKey = null;
    /**
     * @var int[]
     */
    protected array $filterLotCategoryIds;
    protected ?array $orderFieldsMapping = null;
    protected array $orderFieldsMappingDefault = [
        AssignReadyLotListConstants::ORD_ITEM_NO => [
            'asc' => 'li.item_num ASC, li.item_num_ext ASC',
            'desc' => 'li.item_num DESC, li.item_num_ext DESC',
        ],
        AssignReadyLotListConstants::ORD_LOT_NAME => [
            'asc' => 'li.name ASC',
            'desc' => 'li.name DESC',
        ],
        AssignReadyLotListConstants::ORD_ESTIMATE => [
            'asc' => 'li.low_estimate ASC',
            'desc' => 'li.low_estimate DESC',
        ],
        AssignReadyLotListConstants::ORD_STARTING_BID => [
            'asc' => 'li.starting_bid ASC',
            'desc' => 'li.starting_bid DESC',
        ],
        AssignReadyLotListConstants::ORD_HAMMER_PRICE => [
            'asc' => 'li.hammer_price ASC',
            'desc' => 'li.hammer_price DESC',
        ],
        AssignReadyLotListConstants::ORD_WINNER => [
            'asc' => 'uw.username ASC',
            'desc' => 'uw.username DESC',
        ],
        AssignReadyLotListConstants::ORD_CONSIGNOR => [
            'asc' => 'consignor ASC',
            'desc' => 'consignor DESC',
        ],
        AssignReadyLotListConstants::ORD_CREATED_ON => [
            'asc' => 'li.created_on ASC',
            'desc' => 'li.created_on DESC',
        ],
        AssignReadyLotListConstants::ORD_EDITOR => [
            'asc' => 'created_username ASC, modified_username ASC',
            'desc' => 'created_username DESC, modified_username DESC',
        ],
        AssignReadyLotListConstants::ORD_RECENT_AUCTION => [
            'asc' => 'orderby_recentauction ASC, orderby_recentauction_secondary ASC, li.id ASC',
            'desc' => 'orderby_recentauction DESC, orderby_recentauction_secondary DESC, li.id DESC',
        ],
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
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
     * Initialize order field mapping by default expressions and custom field related expressions
     * @return array
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
     * @return int
     */
    public function getFilterLotConsignorId(): ?int
    {
        return $this->filterLotConsignorId;
    }

    /**
     * @param int|null $filterLotConsignorId null means absent filtering by consignor user id
     * @return static
     */
    public function filterLotConsignorId(?int $filterLotConsignorId): static
    {
        $this->filterLotConsignorId = $filterLotConsignorId;
        return $this;
    }

    /**
     * @return int
     */
    public function getFilterLotBillingStatus(): ?int
    {
        return $this->filterLotBillingStatus;
    }

    /**
     * @param int $filterLotBillingStatus
     * @return static
     */
    public function filterLotBillingStatus(int $filterLotBillingStatus): static
    {
        $this->filterLotBillingStatus = Cast::toInt($filterLotBillingStatus, Constants\MySearch::$lotBillingStatuses);
        return $this;
    }

    /**
     * @return int[]
     */
    public function getFilterLotCategoryIds(): array
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
     * @return int
     */
    public function getFilterOverallLotStatus(): ?int
    {
        return $this->filterOverallLotStatus;
    }

    /**
     * @param int $filterOverallLotStatus
     * @return static
     */
    public function filterOverallLotStatus(int $filterOverallLotStatus): static
    {
        $this->filterOverallLotStatus = Cast::toInt($filterOverallLotStatus, Constants\MySearch::$assignReadyLotStatusFilters);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearchKey(): ?string
    {
        return $this->searchKey;
    }

    /**
     * @param string $searchKey
     * @return static
     */
    public function filterSearchKey(string $searchKey): static
    {
        $this->searchKey = trim($searchKey);
        return $this;
    }

    /**
     * @return bool
     */
    public function isBlockSoldLots(): bool
    {
        return $this->isBlockSoldLots;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableBlockSoldLots(bool $enable): static
    {
        $this->isBlockSoldLots = $enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOrderByRecentAuction(): bool
    {
        return $this->getSortColumn() === AssignReadyLotListConstants::ORD_RECENT_AUCTION;
    }

    /**
     * @return int - count Assign-Ready Lots
     */
    public function count(): int
    {
        if (!$this->checkFilteringCriteria()) {
            return 0;
        }

        $query = $this->buildQuery();
        $sql = $query->getCountSql();
        $this->query($sql);
        $row = $this->fetchAssoc();
        return (int)$row['lot_total'];
    }

    /**
     * @return AssignReadyLotDto[] - load data for Assign-Ready Lot List
     */
    public function load(): array
    {
        if (!$this->checkFilteringCriteria()) {
            return [];
        }

        $adminCatalogCustomFields = $this->createLotCustomFieldLoader()->loadInAdminCatalog();
        $adminCatalogCustomFieldAliases = array_map(
            function (LotItemCustField $lotCustomField) {
                return $this->getBaseCustomFieldHelper()->makeFieldAlias($lotCustomField->Name);
            },
            $adminCatalogCustomFields
        );

        $query = $this->buildQuery();
        $sql = $query->getSql() . $this->buildLimitClause();
        $this->query($sql);
        $rows = $this->fetchAllAssoc();
        $dtos = array_map(
            static function (array $row) use ($adminCatalogCustomFieldAliases) {
                return AssignReadyLotDto::new()->fromDbRow($row, $adminCatalogCustomFieldAliases);
            },
            $rows
        );
        return $dtos;
    }

    /**
     * @return LotSearchQueryCriteria
     */
    protected function buildCriteria(): LotSearchQueryCriteria
    {
        $criteria = LotSearchQueryCriteria::new();
        $criteria->accountId = $this->getAuction()->AccountId;
        $criteria->auctionId = $this->getFilterAuctionId();  // Required for OverallLotStatus and Auction filter
        $criteria->categoryIds = $this->getFilterLotCategoryIds();
        $criteria->consignorId = $this->getFilterLotConsignorId();
        $criteria->lotCustomFieldsValue = $this->getLotListCustomFieldAdminFilterHelper()->getFilterParams();
        $criteria->isAscendingOrder = $this->isAscendingOrder();
        $criteria->lotBillingStatus = $this->getFilterLotBillingStatus();
        $criteria->overallLotStatus = $this->getFilterOverallLotStatus();
        $criteria->isPrivateSearch = true;
        $criteria->searchKey = $this->getSearchKey();
        $criteria->skipAuctionId = $this->getAuctionId(); // Exclude lots of running auction
        $criteria->skipLotStatus = $this->isBlockSoldLots() ? Constants\Lot::$wonLotStatuses : null;
        $criteria->userId = $this->getEditorUserId();
        $criteria->orderBy = $this->getSortColumn();
        $criteria->isAscendingOrder = $this->isAscendingOrder();
        return $criteria;
    }

    /**
     * @return LotSearchQuery
     */
    protected function buildQuery(): LotSearchQuery
    {
        $customFields = $this->getLotListCustomFieldAdminFilterHelper()->getCustomFields();
        $customFieldsSelect = $this->getLotCustomFields();
        $searchEngine = $this->cfg()->get('core->search->index->type');

        $query = LotSearchQuery::new()->construct('lot_item', 'li');
        $query->addSelect(
            [
                "li.id AS lot_id",
                "li.item_num AS item_num",
                "li.item_num_ext AS item_num_ext",
                "li.name AS lot_name",
                "li.account_id AS account_id",
                "li.auction_id AS auc_id",
                "li.low_estimate AS low_est",
                "li.high_estimate AS high_est",
                "li.starting_bid AS start_bid",
                "li.hammer_price AS hammer_price",
                "li.auction_info AS auction_info",
                "uc.username AS consignor",
                "uc.id AS consignor_id",
                "uw.username AS winning_bidder",
                "li.created_on AS created_on",
                "'' AS curr_sign",
                "ui.company_name AS company_name",
                "ucr.username AS created_username",
                "umo.username AS modified_username"
            ]
        );
        $join = [
            "LEFT JOIN `user` AS uw ON li.winning_bidder_id = uw.id AND uw.user_status_id = " . Constants\User::US_ACTIVE,
            "LEFT JOIN `user_info` AS ui ON li.winning_bidder_id = ui.user_id",
            "LEFT JOIN `user` AS ucr ON ucr.id = li.created_by",
            "LEFT JOIN `user` AS umo ON umo.id = li.modified_by",
            "LEFT JOIN `user` AS uc ON uc.id = li.consignor_id AND uc.user_status_id = " . Constants\User::US_ACTIVE,
        ];
        $query->addJoin($join);
        $query->addJoinCount($join);
        $query->addWhere('li.active = true');

        $criteria = $this->buildCriteria();
        $queryBuilder = $this->createLotSearchQueryBuilderHelper();
        $customFieldQueryBuilder = $this->createLotSearchCustomFieldQueryBuilderHelper();
        $query = $queryBuilder->applyAccountFilter($query, $criteria);
        $query = $queryBuilder->applyAuctionFilter($query, $criteria);
        $query = $queryBuilder->applyCategoryFilter($query, $criteria);
        $query = $queryBuilder->applyConsignorFilter($query, $criteria);
        $query = $queryBuilder->applyLotBillingStatusFilter($query, $criteria);
        $query = $queryBuilder->applyOrderByRecentAuction($query, $criteria);
        $query = $queryBuilder->applyOverallLotStatusFilter($query, $criteria);

        // disable skip filter if current auctionId is the same with filter auctionId.
        if ($this->getAuctionId() !== $this->getFilterAuctionId()) {
            $query = $queryBuilder->applySkipAuctionFiler($query, $criteria);
        }

        $query = $queryBuilder->applySkipLotStatusFilter($query, $criteria);
        $query = $queryBuilder->applySearchTermFilter($query, $criteria, $searchEngine, $customFields);
        $query = $customFieldQueryBuilder->applyCustomFieldFilter($query, $criteria, $customFields);
        $query = $customFieldQueryBuilder->applyCustomFieldSelect($query, $customFieldsSelect);
        $query = $this->applyOrderParts($query);

        return $query;
    }

    /**
     * Apply ORDER BY expression
     * @param LotSearchQuery $query
     * @return LotSearchQuery
     */
    protected function applyOrderParts(LotSearchQuery $query): LotSearchQuery
    {
        $mapping = $this->getOrderFieldsMapping();
        $allAliases = array_keys($mapping);
        $alias = Cast::toString($this->getSortColumn(), $allAliases)
            ?: AssignReadyLotListConstants::ORD_DEFAULT;
        $direction = $this->isAscendingOrder() ? 'asc' : 'desc';
        if (isset($mapping[$alias][$direction])) {
            $query->addOrderBy($mapping[$alias][$direction]);
        }
        return $query;
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
     * Check filtering criteria to be correct for lots loading, else we should result with empty array
     * @return bool
     */
    protected function checkFilteringCriteria(): bool
    {
        if (
            $this->getFilterOverallLotStatus() === Constants\MySearch::OLSF_UNASSIGNED
            && $this->getFilterAuctionId()
        ) {
            return false;
        }
        return true;
    }
}
