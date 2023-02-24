<?php

/**
 * Data loading for InventoryLotItemReporter
 *
 * SAM-4622: Refactor inventory report
 *
 * @author        Vahagh Hovsepyan
 * @since         Dec 20, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Report\Lot\Inventory;

use Generator;
use LotItemCustField;
use Sam\Account\Filter\AccountApplicationFilterDetectorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchCustomFieldQueryBuilderHelperCreateTrait;
use Sam\Lot\Search\Query\Build\Helper\LotSearchQueryBuilderHelperCreateTrait;
use Sam\Lot\Search\Query\LotSearchQuery;
use Sam\Lot\Search\Query\LotSearchQueryCriteria;
use Sam\Storage\Entity\AwareTrait\LotCustomFieldsAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\ReadRepository\Entity\BidIncrement\BidIncrementReadRepositoryCreateTrait;

/**
 * Class DataLoader
 * @package Sam\Report\Lot\Inventory
 */
class DataLoader extends CustomizableClass
{
    use AccountApplicationFilterDetectorAwareTrait;
    use BidIncrementReadRepositoryCreateTrait;
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use FilterAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCustomFieldsAwareTrait;
    use LotSearchCustomFieldQueryBuilderHelperCreateTrait;
    use LotSearchQueryBuilderHelperCreateTrait;
    use SystemAccountAwareTrait;

    protected array $lotCustomFieldFilterParams = [];
    /** @var LotItemCustField[] */
    protected array $availableLotCustomFields = [];
    protected ?int $editorUserId = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setEditorUserId(?int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * @return array
     */
    public function getLotCustomFieldFilterParams(): array
    {
        return $this->lotCustomFieldFilterParams;
    }

    /**
     * @param array $lotCustomFieldFilterParams
     * @return static
     */
    public function setLotCustomFieldFilterParams(array $lotCustomFieldFilterParams): static
    {
        $this->lotCustomFieldFilterParams = $lotCustomFieldFilterParams;
        return $this;
    }

    /**
     * @return LotItemCustField[]
     */
    public function getAvailableLotCustomFields(): array
    {
        return $this->availableLotCustomFields;
    }

    /**
     * @param LotItemCustField[] $availableLotCustomFields
     * @return static
     */
    public function setAvailableLotCustomFields(array $availableLotCustomFields): static
    {
        $this->availableLotCustomFields = $availableLotCustomFields;
        return $this;
    }

    /**
     * Create generator for associative row fetching
     * @param int $chunkSize
     * @return Generator
     */
    public function createRowFetcher(int $chunkSize = 1000): Generator
    {
        $query = $this->buildResultQuery();
        $chunkNum = 0;
        do {
            $chunkedQuery = $this->applyChunkConstrain($query, $chunkSize, $chunkNum++);
            $this->query($chunkedQuery);
            yield from $this->yieldAssocRowFetcher();
        } while ($this->countRows() > 0);
    }

    /**
     * @param string $query
     * @param int $chunkSize
     * @param int $chunkNum
     * @return string
     */
    private function applyChunkConstrain(string $query, int $chunkSize, int $chunkNum = 0): string
    {
        $constraint = 'LIMIT ' . $chunkSize . ' OFFSET ' . $chunkNum * $chunkSize;
        return $query . ' ' . $constraint;
    }

    /**
     * @param int $lotItemId
     * @return array
     */
    public function loadLotBidIncrements(int $lotItemId): array
    {
        $bidIncrements = $this->createBidIncrementReadRepository()
            ->filterLotItemId($lotItemId)
            ->orderByAmount()
            ->select(
                [
                    'amount',
                    'increment',
                ]
            )
            ->loadRows();

        return $bidIncrements;
    }

    /**
     * @return string
     */
    protected function buildResultQuery(): string
    {
        $lotCustomFields = $this->getAvailableLotCustomFields();
        $lotCustomFieldsSelect = $this->getLotCustomFields();
        $searchEngine = $this->cfg()->get('core->search->index->type');

        $query = LotSearchQuery::new()->construct('lot_item', 'li');
        $query->addSelect(
            [
                "li.id AS lot_id",
                "li.item_num AS item_num",
                "li.item_num_ext AS item_num_ext",
                "li.name AS lot_name",
                "li.description AS lot_desc",
                "li.changes AS lot_changes",
                "li.warranty AS lot_warranty",
                "li.low_estimate AS low_est",
                "li.high_estimate AS high_est",
                "li.starting_bid AS start_bid",
                "li.hammer_price AS hammer_price",
                "li.replacement_price AS replacement_price",
                "li.reserve_price AS reserve_price",
                "li.cost AS cost",
                "li.auction_id AS aid",
                "li.sales_tax AS sales_tax",
                "li.no_tax_oos AS no_tax_oos",
                "li.only_tax_bp AS only_tax_bp",
                "li.returned AS returned",
                "li.seo_meta_title AS seo_meta_title",
                "li.seo_meta_keywords AS seo_meta_keywords",
                "li.seo_meta_description AS seo_meta_description",
                "li.buyers_premium_id AS buyers_premium_id",
                "li.bp_range_calculation AS bp_range_calculation",
                "li.additional_bp_internet AS additional_bp_internet",
                "l.name AS location_name",
                "uw.username AS winning_bidder",
                "uc.username AS consignor",
                "ui.company_name AS company_name",
                "'' AS curr_sign"
            ]
        );
        $query->addJoin(
            [
                "LEFT JOIN user AS uw ON li.winning_bidder_id = uw.id AND uw.user_status_id = " . Constants\User::US_ACTIVE,
                "LEFT JOIN user AS uc ON li.consignor_id = uc.id AND uc.user_status_id = " . Constants\User::US_ACTIVE,
                "LEFT JOIN user_info AS ui ON li.winning_bidder_id = ui.user_id",
                "LEFT JOIN location AS l ON li.location_id = l.id AND l.active = true",
            ]
        );
        $query->addWhere('li.active = true');

        $criteria = $this->buildCriteria();
        $queryBuilderHelper = $this->createLotSearchQueryBuilderHelper();
        $query = $queryBuilderHelper->applyAccountFilter($query, $criteria);
        $query = $queryBuilderHelper->applyAuctioneerFilter($query, $criteria);
        $query = $queryBuilderHelper->applyCategoryFilter($query, $criteria);
        $query = $queryBuilderHelper->applyConsignorFilter($query, $criteria);
        $query = $queryBuilderHelper->applyOverallLotStatusFilter($query, $criteria);
        $query = $queryBuilderHelper->applySearchTermFilter($query, $criteria, $searchEngine, $lotCustomFields);

        $lotCustomFieldsQueryBuilderHelper = $this->createLotSearchCustomFieldQueryBuilderHelper();
        $query = $lotCustomFieldsQueryBuilderHelper->applyCustomFieldFilter($query, $criteria, $lotCustomFields);
        $query = $lotCustomFieldsQueryBuilderHelper->applyCustomFieldSelect($query, $lotCustomFieldsSelect);

        return $query->getSql();
    }

    /**
     * @return LotSearchQueryCriteria
     */
    protected function buildCriteria(): LotSearchQueryCriteria
    {
        $criteria = LotSearchQueryCriteria::new();

        $filterAccountId = $this->getAccountApplicationFilterDetector()
            ->setSystemAccountId($this->getSystemAccountId())
            ->setSelectedAccountId((array)$this->getFilterAccountId())
            ->detectSingle();
        $criteria->accountId = $filterAccountId;
        $criteria->auctionId = $this->getFilterAuctionId();
        $criteria->categoryIds = (array)$this->getLotCategoryId();
        $criteria->consignorId = $this->getConsignorUserId();
        $criteria->lotCustomFieldsValue = $this->getLotCustomFieldFilterParams();
        $criteria->overallLotStatus = $this->getOverallLotStatus();
        $criteria->isPrivateSearch = true;
        $criteria->searchKey = $this->getSearchKey();
        $criteria->userId = $this->editorUserId;
        return $criteria;
    }
}
