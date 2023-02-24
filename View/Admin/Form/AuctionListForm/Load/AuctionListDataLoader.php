<?php
/**
 * Auction List Data Loader
 *
 * SAM-5584: Refactor data loader for Auction List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 28, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionListForm\Load;

use AuctionCustField;
use QMySqli5DatabaseResult;
use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\AuctionListFormConstants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Db\DbTextTransformer;
use Sam\CustomField\Auction\Help\AuctionCustomFieldHelperAwareTrait;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\SearchIndex\Engine\Entity\Auction\AuctionEntitySearchQueryBuilderCreateTrait;
use Sam\SearchIndex\Engine\Entity\LotItem\LotItemEntitySearchQueryBuilderCreateTrait;
use Sam\SearchIndex\Engine\Fulltext\FulltextSearchQueryBuilderCreateTrait;
use Sam\SearchIndex\SearchIndexManagerCreateTrait;
use Sam\Storage\Entity\AwareTrait\AuctionCustomFieldsAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\View\Admin\Form\AuctionListForm\AuctionListConstants;

/**
 * Class AuctionListDataLoader
 */
class AuctionListDataLoader extends CustomizableClass
{
    use ApplicationAccessCheckerCreateTrait;
    use AuctionCustomFieldHelperAwareTrait;
    use AuctionCustomFieldLoaderAwareTrait;
    use AuctionCustomFieldsAwareTrait;
    use AuctionHelperAwareTrait;
    use AuctionEntitySearchQueryBuilderCreateTrait;
    use BaseCustomFieldHelperAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use EditorUserAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FulltextSearchQueryBuilderCreateTrait;
    use LimitInfoAwareTrait;
    use LotItemEntitySearchQueryBuilderCreateTrait;
    use SearchIndexManagerCreateTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;

    protected ?string $filterAuctionType = null;
    protected string $filterShowAuctionStatus = AuctionListFormConstants::SAS_DEFAULT;
    protected string $filterSaleNo = '';
    protected string $filterSearchKey = '';
    protected string $sortOrderDefaultIndex = AuctionListConstants::ORD_DEFAULT;
    protected ?int $filterPublished = null;
    /**
     * @var string[][]
     */
    protected array $orderFieldsMapping = [
        AuctionListConstants::ORD_AUCTION_DATE => [
            'asc' => 'auction_date ASC',
            'desc' => 'auction_date DESC',
        ],
        AuctionListConstants::ORD_START_CLOSING_DATE => [
            'asc' => 'start_closing_date ASC',
            'desc' => 'start_closing_date DESC',
        ],
        AuctionListConstants::ORD_END_DATE => [
            'asc' => 'end_date ASC',
            'desc' => 'end_date DESC',
        ],
        AuctionListConstants::ORD_TOTAL_LOTS => [
            'asc' => 'total_lots ASC',
            'desc' => 'total_lots DESC',
        ],
        AuctionListConstants::ORD_SALE_NO => [
            'asc' => 'sale_num ASC, sale_num_ext ASC',
            'desc' => 'sale_num DESC, sale_num_ext DESC',
        ],
        AuctionListConstants::ORD_NAME => [
            'asc' => 'name ASC',
            'desc' => 'name DESC',
        ],
        AuctionListConstants::ORD_AUCTION_STATUS_ID => [
            'asc' => 'auction_status_id ASC',
            'desc' => 'auction_status_id DESC',
        ],
        AuctionListConstants::ORD_AUCTION_TYPE => [
            'asc' => 'auction_type ASC',
            'desc' => 'auction_type DESC',
        ],
        AuctionListConstants::ORD_PUBLISHED => [
            'asc' => 'is_published ASC',
            'desc' => 'is_published DESC',
        ],
        AuctionListConstants::ORD_ACCOUNT_NAME => [
            'asc' => 'account_name ASC',
            'desc' => 'account_name DESC',
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
     * @override AuctionCustomFieldsAwareTrait::getAuctionCustomFields()
     * @return array
     */
    protected function getAuctionCustomFields(): array
    {
        if ($this->auctionCustomFields === null) {
            $this->auctionCustomFields = $this->getAuctionCustomFieldLoader()->loadForAdminList();
        }
        return $this->auctionCustomFields;
    }

    /**
     * @param string|null $auctionType null means no filtering
     * @return static
     */
    public function filterAuctionType(?string $auctionType): AuctionListDataLoader
    {
        $this->filterAuctionType = $auctionType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterAuctionType(): ?string
    {
        return $this->filterAuctionType;
    }

    /**
     * @param string $showAuctionStatus
     * @return static
     */
    public function filterShowAuctionStatus(string $showAuctionStatus): static
    {
        $this->filterShowAuctionStatus = $showAuctionStatus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterShowAuctionStatus(): ?string
    {
        return $this->filterShowAuctionStatus;
    }

    /**
     * @param string $saleNo
     * @return static
     */
    public function filterSaleNo(string $saleNo): static
    {
        $this->filterSaleNo = trim($saleNo);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterSaleNo(): ?string
    {
        return $this->filterSaleNo;
    }

    /**
     * @param string $searchKey
     * @return static
     */
    public function filterSearchKey(string $searchKey): static
    {
        $this->filterSearchKey = trim($searchKey);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilterSearchKey(): ?string
    {
        return $this->filterSearchKey;
    }

    /**
     * @param int $published
     * @return static
     */
    public function filterPublished(int $published): static
    {
        $this->filterPublished = $published;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterPublished(): ?int
    {
        return $this->filterPublished;
    }

    /**
     * @return int - return value of Auctions count
     */
    public function count(): int
    {
        $havingCount = '';
        $selectCount = "SELECT COUNT(1) AS total ";
        $countQuery = $selectCount
            . $this->buildFromClause()
            . $this->buildWhereClause()
            . $havingCount;
        $dbCount = $this->query($countQuery);
        $row = $dbCount->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        return (int)$row['total'];
    }

    /**
     * @return AuctionListDto[] - return values for Auctions
     */
    public function load(): array
    {
        $group = '';
        $having = '';

        $customFieldSelect = '';
        $auctionCustomFields = $this->getAuctionCustomFields();
        $auctionCustomFieldsAliases = array_map(
            function (AuctionCustField $auctionCustField) {
                return $this->getBaseCustomFieldHelper()->makeFieldAlias($auctionCustField->Name);
            },
            $auctionCustomFields
        );

        if (count($auctionCustomFields)) {
            $customFieldSelect = ', ' . $this->buildSelectClausesForList($auctionCustomFields);
            $dbTransformer = DbTextTransformer::new();
            foreach ($auctionCustomFields as $auctionCustomField) {
                $customFieldName = $dbTransformer->toDbColumn($auctionCustomField->Name);
                $this->orderFieldsMapping['c' . $customFieldName] = [
                    'asc' => "c{$customFieldName} ASC",
                    'desc' => "c{$customFieldName} DESC",
                ];
            }
        }
        $currentDateIso = $this->escape($this->getCurrentDateUtcIso());
        $selectResult = 'SELECT'
            . ' a.id AS id,'
            . ' a.start_closing_date AS start_closing_date,'
            . ' a.end_date AS end_date,'
            . ' IF (a.auction_type = "' . Constants\Auction::TIMED . '", a.end_date, a.start_closing_date) AS auction_date,'
            . ' a.start_date AS start_date,'
            . ' a.sale_num AS sale_num,'
            . ' a.sale_num_ext AS sale_num_ext,'
            . ' a.account_id AS account_id,'
            . ' a.name AS name,'
            . ' a.auction_status_id AS auction_status_id,'
            . ' a.auction_type AS auction_type,'
            . ' IF(a.publish_date <= ' . $currentDateIso . ' AND (a.unpublish_date > ' . $currentDateIso . ' OR a.unpublish_date IS NULL), 1, 0) AS is_published,'
            . ' a.created_by AS created_by,'
            . ' a.event_type AS event_type,'
            . ' atz.location AS timezone_location,'
            . ' ac.total_lots AS total_lots,'
            . ' act.name AS account_name'
            . $customFieldSelect;

        $resultQuery = $selectResult
            . $this->buildFromClause()
            . $this->buildWhereClause()
            . $group
            . $having
            . $this->buildOrderClause()
            . $this->buildLimitClause();

        $this->query($resultQuery);
        $rows = $this->fetchAllAssoc();
        $dtos = [];
        foreach ($rows as $row) {
            $dtos[] = AuctionListDto::new()->fromDbRow($row, $auctionCustomFieldsAliases);
        }
        return $dtos;
    }

    /**
     * @param string $searchKey
     * @return string
     */
    protected function lotItemQueryParts(string $searchKey): string
    {
        $queryParts = [
            'select_count' => 'SELECT COUNT(1)',
            'select' => '',
            'from' => ' FROM auction_lot_item AS ali'
                . ' INNER JOIN lot_item AS li ON ali.lot_item_id = li.id',
            'from_count' => '',
            'where' => ' WHERE'
                . ' li.active = true'
                . ' AND ali.auction_id = a.id'
                . ' AND li.name LIKE ' . $this->escape("%$searchKey%"),
            'where_count' => '',
            'group' => '',
            'group_count' => '',
            'having' => '',
            'having_count' => '',
            'create_temptable' => '',
            'from_temptable' => '',
        ];

        $params['BaseTable'] = 'lot_item';
        $params['SysAccountId'] = $this->getSystemAccountId();
        $params['AccountId'] = $this->isPortalSystemAccount()
            ? $this->getSystemAccountId()
            : null;
        $params['UserId'] = $this->getEditorUserId();
        $params['SearchKey'] = $searchKey;
        $params['Public'] = false;
        // Filter by search terms
        $queryParts = $this->addSearchTermQueryParts($params, $queryParts);
        $queryParts['where'] .= ' ';
        return $queryParts['select_count'] . $queryParts['from'] . $queryParts['where'];
    }

    /**
     * @param array $params
     * @param array|null $queryParts null - empty query parts
     * @return array
     */
    protected function addSearchTermQueryParts(array $params, array $queryParts = null): array
    {
        $accountId = $params['AccountId'] ?? null;
        $userId = $params['UserId'] ?? null;
        $searchKey = $params['SearchKey'] ?? '';
        $isPublic = $params['Public'] ?? true;
        $baseTable = $params['BaseTable'] ?? null;
        $userAccess = $params['UserAccess'] ?? null;
        $lotCustomFields = $params['CustFldArray'] ?? null;
        $n = "\n";
        if ($queryParts === null) {
            $queryParts = [
                'select' => '',
                'from' => '',
                'from_temptable' => '',
                'where' => '',
                'group' => '',
                'having' => '',
                'select_count' => '',
                'from_count' => '',
                'where_count' => '',
            ];
        }
        if ($this->createSearchIndexManager()->checkSearchKey($searchKey)) {
            $searchConds = [];
            if ($baseTable === 'lot_item') {
                $whereClauseForNumber = $this->createLotItemEntitySearchQueryBuilder()
                    ->getWhereClauseForItemNumber($searchKey);
            } else {
                $whereClauseForNumber = $this->createLotItemEntitySearchQueryBuilder()
                    ->getWhereClauseForLotNumber($searchKey);
            }
            if (!empty($whereClauseForNumber)) {
                $searchConds[] = $whereClauseForNumber;
            }
            switch ($this->cfg()->get('core->search->index->type')) {
                case Constants\Search::INDEX_FULLTEXT:
                    $searchConds[] = $this->createFulltextSearchQueryBuilder()->getWhereClause(
                        $searchKey,
                        Constants\Search::ENTITY_LOT_ITEM,
                        $isPublic,
                        $accountId
                    );
                    $searchJoin = " INNER JOIN "
                        . $this->createFulltextSearchQueryBuilder()->getJoinClause(
                            Constants\Search::ENTITY_LOT_ITEM,
                            'li.id',
                            $accountId
                        );
                    $queryParts['from'] .= $searchJoin;
                    if (isset($queryParts['from_temptable'])) {
                        $queryParts['from_temptable'] .= $searchJoin;
                    }
                    $queryParts['from_count'] .= $searchJoin;
                    break;

                default:    // SearchIndexManager::INDEX_NONE
                    $searchConds[] = $this->createLotItemEntitySearchQueryBuilder()
                        ->getWhereClause($searchKey);
                    $whereForCategory = $this->createLotItemEntitySearchQueryBuilder()
                        ->getWhereClauseForCategory($searchKey);
                    if (!empty($whereForCategory)) {
                        $searchConds[] = $whereForCategory;
                    }
                    if ($baseTable === 'lot_item') {
                        $whereForCustomField = $this->createLotItemEntitySearchQueryBuilder()
                            ->getWhereClauseForCustomFieldsOptimized($searchKey);
                    } else {
                        $whereForCustomField = $this->createLotItemEntitySearchQueryBuilder()
                            ->getWhereClauseForCustomFields(
                                $searchKey,
                                'li',
                                'ali',
                                $userId,
                                $lotCustomFields,
                                $userAccess
                            );
                    }
                    if (!empty($whereForCustomField)) {
                        $searchConds[] = $whereForCustomField;
                    }
                    break;
            }
            $queryParts['where'] .= " AND (" . implode($n . ' OR ', $searchConds) . ')';
        }
        return $queryParts;
    }

    /**
     * @return string
     */
    protected function buildFromClause(): string
    {
        $from = ' FROM auction AS a'
            . ' INNER JOIN account AS act ON act.id = a.account_id'
            . ' LEFT JOIN timezone atz ON atz.id = a.timezone_id'
            . ' LEFT JOIN auction_cache ac ON a.id = ac.auction_id'
            . ' LEFT JOIN currency c ON c.id = a.currency'
            . ' LEFT JOIN location loc ON loc.id = a.invoice_location_id';

        $searchKey = $this->getFilterSearchKey();
        if ($searchKey !== '') {
            switch ($this->cfg()->get('core->search->index->type')) {
                case Constants\Search::INDEX_FULLTEXT:
                    $searchJoin = " INNER JOIN " . $this->createFulltextSearchQueryBuilder()
                            ->getJoinClause(Constants\Search::ENTITY_AUCTION, 'a.id');
                    $from .= $searchJoin;
                    break;

                default:    // SearchIndexManager::INDEX_NONE
                    // TODO: was not implemented
                    // $strKeyCond = Search_Entity_Auction_Query::new()->getWhereClause($this->AccountId, $searchKey, 'a');
                    // $whereForCustFld = Search_Entity_Auction_Query::new()->getWhereClauseForCustomFieldsOptimized($this->AccountId, $searchKey, 'a');
                    // if (!empty($whereForCustFld)) {
                    //     $strKeyCond .= 'OR ' . $whereForCustFld;
                    // }
                    break;
            }
        }

        return $from;
    }

    /**
     * @return string
     */
    protected function buildWhereClause(): string
    {
        $conditions = ' WHERE act.active';

        if ($this->isAccountFiltering()) {
            if ($this->getFilterAccountId()) {
                $conditions .= " AND a.account_id = " . $this->escape($this->getFilterAccountId());
            }
        } else { //In case sam portal has been disabled again
            $conditions .= " AND a.account_id = " . $this->escape($this->getSystemAccountId());
        }

        if (!$this->getEditorUserAdminPrivilegeChecker()->hasSubPrivilegeForManageAllAuctions()) {
            $conditions .= " AND a.created_by = " . $this->escape($this->getEditorUserId());
        }

        if ($this->getFilterSaleNo()) {
            $searchText = $this->getFilterSaleNo();
            preg_match('!\d+!', $searchText, $results);
            $searchSaleNo = !empty($results) ? $results[0] : $searchText;
            $conditions .= ' AND a.sale_num LIKE ' . $this->escape("%$searchSaleNo%");
        }

        $auctionTypes = $this->getAuctionHelper()->getAvailableTypes($this->getSystemAccountId());
        if (in_array($this->getFilterAuctionType(), $auctionTypes, true)) {
            $conditions .= ' AND a.auction_type = ' . $this->escape($this->getFilterAuctionType());
        } else {
            $auctionTypes = $this->getAuctionHelper()->getAvailableTypes($this->getSystemAccountId());
            $auctionTypeList = '';
            foreach ($auctionTypes as $type) {
                $auctionTypeList .= $this->escape($type) . ',';
            }
            $auctionTypeList = rtrim($auctionTypeList, ',');
            $conditions .= ' AND a.auction_type IN (' . $auctionTypeList . ')';
        }

        $currentDateUtcIso = $this->getCurrentDateUtcIso();

        switch ($this->getFilterShowAuctionStatus()) {
            case AuctionListFormConstants::SAS_CLOSED:
                $conditions .= ' AND a.auction_status_id = ' . Constants\Auction::AS_CLOSED;
                break;

            case AuctionListFormConstants::SAS_ACTIVE:
                $openAuctionStatusList = implode(',', Constants\Auction::$openAuctionStatuses);
                $live = Constants\Auction::LIVE;
                $hybrid = Constants\Auction::HYBRID;
                $etOngoing = Constants\Auction::ET_ONGOING;
                $conditions .= <<<SQL
 AND (SELECT
    IF (auction_type IN ('{$live}', '{$hybrid}'),
        IF (auction_status_id in ({$openAuctionStatusList}), 1, 0),
        IF (event_type = {$etOngoing}, 1, IF (end_date > {$this->escape($currentDateUtcIso)}, 1, 0))
    )
    FROM auction AS b WHERE b.id = a.id
) = 1
SQL;
                $conditions .= ' AND a.auction_status_id NOT IN (' . implode(
                        ',',
                        [
                            Constants\Auction::AS_CLOSED,
                            Constants\Auction::AS_DELETED,
                            Constants\Auction::AS_ARCHIVED,
                        ]
                    ) . ')';
                break;

            case AuctionListFormConstants::SAS_ARCHIVED:
                $conditions .= ' AND a.auction_status_id = ' . Constants\Auction::AS_ARCHIVED;
                break;

            case AuctionListFormConstants::SAS_ALL:
                $conditions .= ' AND a.auction_status_id != ' . Constants\Auction::AS_DELETED;
                break;
        }

        switch ($this->getFilterPublished()) {
            case AuctionListFormConstants::PF_PUBLISHED:
                $currentDateUtcIsoEscaped = $this->escape($currentDateUtcIso);
                $conditions .= ' ';
                $conditions .= <<<SQL
 AND a.publish_date <= {$currentDateUtcIsoEscaped} 
AND (a.unpublish_date > {$currentDateUtcIsoEscaped} OR a.unpublish_date IS NULL)
SQL;
                break;

            case AuctionListFormConstants::PF_UNPUBLISHED:
                $currentDateUtcIsoEscaped = $this->escape($currentDateUtcIso);
                $conditions .= ' ';
                $conditions .= <<<SQL
 AND (
    a.publish_date > {$currentDateUtcIsoEscaped} 
    OR a.publish_date IS NULL 
    OR a.unpublish_date <= {$currentDateUtcIsoEscaped}
)
SQL;
                break;
        }

        $searchKey = $this->getFilterSearchKey();
        if ($searchKey !== '') {
            $lotCountQuery = $this->lotItemQueryParts($searchKey);
            switch ($this->cfg()->get('core->search->index->type')) {
                case Constants\Search::INDEX_FULLTEXT:
                    $searchKeyCond = $this->createFulltextSearchQueryBuilder()
                        ->getWhereClause($searchKey, Constants\Search::ENTITY_AUCTION);
                    break;

                default:    // SearchIndexManager::INDEX_NONE
                    $searchKeyCond = $this->createAuctionEntitySearchQueryBuilder()->getWhereClause($searchKey, 'a');
                    $whereForCustFld = $this->createAuctionEntitySearchQueryBuilder()->getWhereClauseForCustomFieldsOptimized($searchKey, 'a');
                    if (!empty($whereForCustFld)) {
                        $searchKeyCond .= 'OR ' . $whereForCustFld;
                    }
                    break;
            }

            $conditions .= " AND ( $searchKeyCond"
                . ' OR ('
                . ' a.auction_status_id IN (' . implode(
                    ',',
                    [
                        Constants\Auction::AS_CLOSED,
                        Constants\Auction::AS_DELETED,
                        Constants\Auction::AS_ARCHIVED,
                    ]
                ) . ')'
                . " AND ($lotCountQuery) > 0"
                . ' )'
                . ' )';
        }

        return $conditions;
    }

    /**
     * Return select clause to get values of passed custom fields
     *
     * @param AuctionCustField[] $auctionCustomFields
     * @return string
     */
    public function buildSelectClausesForList(array $auctionCustomFields): string
    {
        $customFieldSelect = '';
        $dbTransformer = DbTextTransformer::new();
        foreach ($auctionCustomFields as $auctionCustomField) {
            $fieldName = $dbTransformer->toDbColumn($auctionCustomField->Name);
            if ($auctionCustomField->isNumeric()) {
                $customFieldSelect .= ", (SELECT acd.`numeric` FROM auction_cust_data AS acd " .
                    "WHERE acd.auction_id = a.id AND acd.active = true " .
                    "AND acd.auction_cust_field_id = " . $this->escape($auctionCustomField->Id) . " ) AS c{$fieldName} ";
            } else {
                $customFieldSelect .= ", (SELECT acd.`text` FROM auction_cust_data AS acd " .
                    "WHERE acd.auction_id = a.id AND acd.active = true " .
                    "AND acd.auction_cust_field_id = " . $this->escape($auctionCustomField->Id) . " ) AS c{$fieldName} ";
            }
        }
        return ltrim($customFieldSelect, ', ');
    }

    /**
     * @return string
     */
    protected function buildOrderClause(): string
    {
        $sortOrder = $this->getSortColumn() ?: $this->sortOrderDefaultIndex;
        return sprintf(' ORDER BY %s', $this->orderFieldsMapping[$sortOrder][$this->isAscendingOrder() ? 'asc' : 'desc']);
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
        return sprintf(' LIMIT %s', $query);
    }

    /**
     * @return bool
     */
    public function isAccountFiltering(): bool
    {
        return $this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant(
            $this->getEditorUserId(),
            $this->getSystemAccountId(),
            true
        );
    }
}
