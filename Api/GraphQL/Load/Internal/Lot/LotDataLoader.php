<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Lot;

use InvalidArgumentException;
use Sam\Account\Filter\AccountApplicationFilterDetectorAwareTrait;
use Sam\Api\GraphQL\Load\Aggregate\AggregateDataField;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\AuctionLotDataSourceMysql;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\CatalogLotAggregateDataSourceMysql;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\LotAggregateDataSourceInterface;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\LotItemDataSourceMysql;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\MyItems\AllMyItemsAggregateDatasourceMysql;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\MyItems\BiddingMyItemsAggregateDatasourceMysql;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\MyItems\ConsignedMyItemsAggregateDatasourceMysql;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\MyItems\NotWonMyItemsAggregateDatasourceMysql;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\MyItems\WatchlistMyItemsAggregateDatasourceMysql;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\MyItems\WonMyItemsAggregateDatasourceMysql;
use Sam\Api\GraphQL\Load\Internal\Lot\Internal\SearchLotAggregateDataSourceMysql;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Lot\LotList;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\User\Access\UnknownContextAccessCheckerAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PageType\Validate\PageTypeChecker;

/**
 * Class LotDataLoader
 * @package Sam\Api\GraphQL\Load\Internal\Lot
 */
class LotDataLoader extends CustomizableClass
{
    use AccountApplicationFilterDetectorAwareTrait;
    use AuctionLoaderAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use UnknownContextAccessCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionLotIdentifier[] $identifiers
     * @param array $fields
     * @param int $systemAccountId
     * @param int|null $editorUserId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAuctionLots(
        array $identifiers,
        array $fields,
        int $systemAccountId,
        ?int $editorUserId,
        bool $isReadOnlyDb = false
    ): array {
        $dataSource = AuctionLotDataSourceMysql::new();
        $dataSource = $this->prepareDataSource($dataSource, $systemAccountId, $editorUserId, $isReadOnlyDb);
        $resultSet = $this->makeResultSetFields($fields);
        $dataSource->addResultSetFields($resultSet);

        $filterAccountIds = $this->getAccountApplicationFilterDetector()
            ->setSystemAccountId($systemAccountId)
            ->detect();
        if ($filterAccountIds) {
            $dataSource->filterAccountIds($filterAccountIds);
        }
        $dataSource->filterIdentifier($identifiers);
        $rows = $dataSource->getResults();
        $indexedResult = [];
        foreach ($rows as $row) {
            $identifier = AuctionLotIdentifier::new()->construct((int)$row['id'], (int)$row['auction_id']);
            $indexedResult[(string)$identifier] = $row;
        }
        return $indexedResult;
    }

    public function loadLotItems(
        array $lotItemIds,
        array $fields,
        int $systemAccountId,
        ?int $editorUserId,
        bool $isReadOnlyDb = false
    ): array {
        $dataSource = LotItemDataSourceMysql::new();
        $dataSource = $this->prepareDataSource($dataSource, $systemAccountId, $editorUserId, $isReadOnlyDb);
        $filterAccountIds = $this->getAccountApplicationFilterDetector()
            ->setSystemAccountId($systemAccountId)
            ->detect();
        if ($filterAccountIds) {
            $dataSource->filterAccountIds($filterAccountIds);
        }
        $dataSource->filterLotItemIds($lotItemIds);
        $resultSet = $this->makeResultSetFields($fields);
        $dataSource->addResultSetFields($resultSet);
        $rows = $dataSource->getResults();
        $indexedResult = [];
        foreach ($rows as $row) {
            $indexedResult[(int)$row['id']] = $row;
        }
        return $indexedResult;
    }

    public function loadForCatalog(
        int $auctionId,
        int $systemAccountId,
        ?int $editorUserId,
        array $fields,
        LotFilterCondition $filterCondition,
        ?string $sortCriteria,
        int $limit,
        int $offset,
        bool $isReadOnlyDb = false
    ): array {
        $catalogAuction = $this->getAuctionLoader()->load($auctionId, true);
        if (!$catalogAuction) {
            throw new InvalidArgumentException('Auction does not exist');
        }

        $dataSource = LotList\Catalog\DataSourceMysql::new();
        $dataSource = $this->prepareDataSource($dataSource, $systemAccountId, $editorUserId, $isReadOnlyDb);
        $dataSource = $this->applyFilterCondition($dataSource, $filterCondition, $systemAccountId);
        $dataSource->filterAuctionIds([$auctionId]);
        $dataSource->filterPublished(true);

        $resultSet = $this->makeResultSetFields($fields);
        $dataSource->addResultSetFields($resultSet);

        if ($catalogAuction->OnlyOngoingLots) {
            $dataSource->enableOnlyOngoingLotsFilter(true);
        } elseif (
            $catalogAuction->isTimed()
            && $catalogAuction->NotShowUpcomingLots
        ) {
            $dataSource->enableNotShowUpcomingLotsFilter(true);
        }
        $dataSource->orderBy($sortCriteria ?? 'order_num');
        $dataSource->setOffset($offset);
        $dataSource->setLimit($limit);
        $rows = $dataSource->getResults();
        return $rows;
    }

    /**
     * @param int $auctionId
     * @param int $systemAccountId
     * @param int|null $editorUserId
     * @param AggregateDataField[] $fields
     * @param LotFilterCondition $filterCondition
     * @param int $limit
     * @param int $offset
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function aggregateForCatalog(
        int $auctionId,
        int $systemAccountId,
        ?int $editorUserId,
        array $fields,
        LotFilterCondition $filterCondition,
        int $limit,
        int $offset,
        bool $isReadOnlyDb = false
    ): array {
        $catalogAuction = $this->getAuctionLoader()->load($auctionId, true);
        if (!$catalogAuction) {
            throw new InvalidArgumentException('Auction does not exist');
        }

        $dataSource = CatalogLotAggregateDataSourceMysql::new();
        $dataSource = $this->prepareDataSource($dataSource, $systemAccountId, $editorUserId, $isReadOnlyDb);
        $dataSource = $this->applyFilterCondition($dataSource, $filterCondition, $systemAccountId);
        $dataSource->filterAuctionIds([$auctionId]);
        $dataSource->filterPublished(true);

        $dataSource->setAggregateFields($fields);

        if ($catalogAuction->OnlyOngoingLots) {
            $dataSource->enableOnlyOngoingLotsFilter(true);
        } elseif (
            $catalogAuction->isTimed()
            && $catalogAuction->NotShowUpcomingLots
        ) {
            $dataSource->enableNotShowUpcomingLotsFilter(true);
        }
        $dataSource->setOffset($offset);
        $dataSource->setLimit($limit);
        $rows = $dataSource->getResults();
        return $rows;
    }

    public function loadForSearch(
        int $systemAccountId,
        ?int $editorUserId,
        array $fields,
        LotFilterCondition $filterCondition,
        ?string $sortCriteria,
        int $limit,
        int $offset,
        bool $isReadOnlyDb = false
    ): array {
        $dataSource = LotList\Search\DataSourceMysql::new();
        $dataSource = $this->prepareDataSource($dataSource, $systemAccountId, $editorUserId, $isReadOnlyDb);
        $dataSource = $this->applyFilterCondition($dataSource, $filterCondition, $systemAccountId);
        $dataSource->enableOnlyOngoingLotsFilter(true);
        $dataSource->filterAuctionPublished(true);
        $dataSource->filterAuctionStatusIds(Constants\Auction::$availableAuctionStatuses);
        $dataSource->enableNotShowUpcomingLotsFilter(true);
        $resultSet = $this->makeResultSetFields($fields);
        $dataSource->addResultSetFields($resultSet);
        $dataSource->orderBy($sortCriteria ?? 'timeleft');
        $dataSource->setOffset($offset);
        $dataSource->setLimit($limit);
        $rows = $dataSource->getResults();
        return $rows;
    }

    /**
     * @param int $systemAccountId
     * @param int|null $editorUserId
     * @param AggregateDataField[] $fields
     * @param LotFilterCondition $filterCondition
     * @param int $limit
     * @param int $offset
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function aggregateForSearch(
        int $systemAccountId,
        ?int $editorUserId,
        array $fields,
        LotFilterCondition $filterCondition,
        int $limit,
        int $offset,
        bool $isReadOnlyDb = false
    ): array {
        $dataSource = SearchLotAggregateDataSourceMysql::new();
        $dataSource = $this->prepareDataSource($dataSource, $systemAccountId, $editorUserId, $isReadOnlyDb);
        $dataSource->setAggregateFields($fields);
        $dataSource = $this->applyFilterCondition($dataSource, $filterCondition, $systemAccountId);
        $dataSource->enableOnlyOngoingLotsFilter(true);
        $dataSource->filterAuctionPublished(true);
        $dataSource->filterAuctionStatusIds(Constants\Auction::$availableAuctionStatuses);
        $dataSource->enableNotShowUpcomingLotsFilter(true);
        $dataSource->setOffset($offset);
        $dataSource->setLimit($limit);
        $rows = $dataSource->getResults();
        return $rows;
    }

    public function loadForMyItems(
        int $systemAccountId,
        int $editorUserId,
        array $fields,
        LotFilterCondition $filterCondition,
        ?string $sortCriteria,
        string $pageType,
        int $limit,
        int $offset,
        bool $isReadOnlyDb = false
    ): array {
        $dataSource = $this->createMyItemsLotListDatasource($pageType);
        $dataSource = $this->prepareDataSource($dataSource, $systemAccountId, $editorUserId, $isReadOnlyDb);
        $dataSource = $this->applyFilterCondition($dataSource, $filterCondition, $systemAccountId);
        if (
            !$filterCondition->auctionId
            && $filterCondition->onlyUnassigned
        ) {
            $dataSource->filterAuctionIds([null]);    // ali.auction_id IS NULL
        }

        $resultSet = $this->makeResultSetFields($fields);
        $dataSource->addResultSetFields($resultSet);

        $dataSource->orderBy($sortCriteria ?? $this->detectMyItemsDefaultSortCriteria($pageType));
        $dataSource->setOffset($offset);
        $dataSource->setLimit($limit);
        $rows = $dataSource->getResults();
        return $rows;
    }

    /**
     * @param int $systemAccountId
     * @param int $editorUserId
     * @param AggregateDataField[] $fields
     * @param LotFilterCondition $filterCondition
     * @param string $pageType
     * @param int $limit
     * @param int $offset
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function aggregateForMyItems(
        int $systemAccountId,
        int $editorUserId,
        array $fields,
        LotFilterCondition $filterCondition,
        string $pageType,
        int $limit,
        int $offset,
        bool $isReadOnlyDb = false
    ): array {
        $dataSource = $this->createMyItemsAggregateDataSource($pageType);
        $dataSource = $this->prepareDataSource($dataSource, $systemAccountId, $editorUserId, $isReadOnlyDb);
        $dataSource = $this->applyFilterCondition($dataSource, $filterCondition, $systemAccountId);
        if (
            !$filterCondition->auctionId
            && $filterCondition->onlyUnassigned
        ) {
            $dataSource->filterAuctionIds([null]);    // ali.auction_id IS NULL
        }

        $dataSource->setAggregateFields($fields);
        $dataSource->setOffset($offset);
        $dataSource->setLimit($limit);
        $rows = $dataSource->getResults();
        return $rows;
    }

    /**
     * @template DataSourceTemplate of LotList\DataSourceMysql
     * @param DataSourceTemplate $dataSource
     * @param LotFilterCondition $filterCondition
     * @param int $systemAccountId
     * @return DataSourceTemplate
     */
    protected function applyFilterCondition(
        LotList\DataSourceMysql $dataSource,
        LotFilterCondition $filterCondition,
        int $systemAccountId
    ): LotList\DataSourceMysql {
        $filterAccountIds = $this->getAccountApplicationFilterDetector()
            ->setSystemAccountId($systemAccountId)
            ->setSelectedAccountId($filterCondition->accountIds)
            ->detect();
        if ($filterAccountIds) {
            $dataSource->filterAccountIds($filterAccountIds);
        }
        $dataSource->filterAuctionIds((array)$filterCondition->auctionId);
        if ($filterCondition->auctioneerId) {
            $dataSource->filterAuctioneerIds([$filterCondition->auctioneerId]);
        }
        if ($filterCondition->auctionTypes) {
            $dataSource->filterAuctionTypes($filterCondition->auctionTypes);
        }
        if ($filterCondition->categoryIds) {
            $dataSource->filterLotCategoryIds($filterCondition->categoryIds);
            $dataSource->setCategoryMatch($filterCondition->categoryMatch);
        }
        if ($filterCondition->onlyFeatured) {
            $dataSource->filterSampleLot($filterCondition->onlyFeatured);
        }
        if (in_array(Constants\Auction::TIMED, $filterCondition->auctionTypes, true)) {
            $dataSource->filterTimedItemOptions($filterCondition->timedOptions);
        }
        if ($filterCondition->lotNo) {
            $dataSource->filterLotNo($filterCondition->lotNo);
        }
        if (
            $filterCondition->minPrice
            || $filterCondition->maxPrice
        ) {
            $prices = ['min' => (float)$filterCondition->minPrice, 'max' => (float)$filterCondition->maxPrice];
            $dataSource->filterPrices($prices);
        }
        if ($filterCondition->excludeClosedLots) {
            $dataSource->enableExcludeClosedLotsFilter(true);
        }
        $dataSource->setSearchKey($filterCondition->searchKey ?? '');
        $dataSource->filterCustomFields($filterCondition->customFieldsCondition);
        return $dataSource;
    }

    /**
     * @template T of LotList\DataSourceMysql
     * @param T $dataSource
     * @param int $systemAccountId
     * @param int|null $editorUserId
     * @param bool $isReadOnlyDb
     * @return T
     */
    protected function prepareDataSource(
        LotList\DataSourceMysql $dataSource,
        int $systemAccountId,
        ?int $editorUserId,
        bool $isReadOnlyDb = false
    ): LotList\DataSourceMysql {
        $dataSource->enableReadOnlyDb($isReadOnlyDb);
        $dataSource->filterAuctionAccessCheck([Constants\Auction::ACCESS_RESTYPE_AUCTION_CATALOG]);
        $dataSource->setSystemAccountId($systemAccountId);
        $dataSource->setUserId($editorUserId);
        $accessRoles = $this->getUnknownContextAccessChecker()->detectRoles($editorUserId)[0];
        $customFields = $this->createLotCustomFieldLoader()->loadByRole($accessRoles, true);
        $dataSource->setMappedLotCustomFields($customFields);
        return $dataSource;
    }

    protected function makeResultSetFields(array $fields): array
    {
        $fields[] = 'id';
        $fields[] = 'auction_id';
        $fields = array_unique($fields);
        return $fields;
    }

    protected function createMyItemsLotListDatasource(string $pageType): LotList\DataSourceMysql
    {
        $dataSource = match ($pageType) {
            Constants\Page::ALL => LotList\MyItems\DataSource\AllMysql::new(),
            Constants\Page::BIDDING => LotList\MyItems\DataSource\BiddingMysql::new(),
            Constants\Page::CONSIGNED => LotList\MyItems\DataSource\ConsignedMysql::new(),
            Constants\Page::NOTWON => LotList\MyItems\DataSource\NotWonMysql::new(),
            Constants\Page::WATCHLIST => LotList\MyItems\DataSource\WatchlistMysql::new(),
            Constants\Page::WON => LotList\MyItems\DataSource\WonMysql::new(),
            default => throw new InvalidArgumentException("Invalid page type '{$pageType}'")
        };
        return $dataSource;
    }

    protected function createMyItemsAggregateDataSource(
        string $pageType
    ): LotList\DataSourceMysql&LotAggregateDataSourceInterface {
        $dataSource = match ($pageType) {
            Constants\Page::ALL => AllMyItemsAggregateDatasourceMysql::new(),
            Constants\Page::BIDDING => BiddingMyItemsAggregateDatasourceMysql::new(),
            Constants\Page::CONSIGNED => ConsignedMyItemsAggregateDatasourceMysql::new(),
            Constants\Page::NOTWON => NotWonMyItemsAggregateDatasourceMysql::new(),
            Constants\Page::WATCHLIST => WatchlistMyItemsAggregateDatasourceMysql::new(),
            Constants\Page::WON => WonMyItemsAggregateDatasourceMysql::new(),
            default => throw new InvalidArgumentException("Invalid page type '{$pageType}'")
        };
        return $dataSource;
    }

    protected function detectMyItemsDefaultSortCriteria(string $pageType): string
    {
        $sortCriteria = 'timeleft';
        $pageTypeChecker = PageTypeChecker::new();
        if (
            $pageTypeChecker->isMyItemsWon($pageType)
            || $pageTypeChecker->isMyItemsNotWon($pageType)
        ) {
            $sortCriteria .= ' desc';
        }
        return $sortCriteria;
    }
}
