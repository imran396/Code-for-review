<?php
/**
 * SAM-10467: Implement a GraphQL nested structure for a single auction
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 28, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load;

use GraphQL\Deferred;
use GraphQL\Executor\Promise\Promise;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\QueryPlan;
use Overblog\DataLoader\DataLoader;
use Overblog\DataLoader\Promise\Adapter\Webonyx\GraphQL\SyncPromiseAdapter;
use Overblog\PromiseAdapter\Adapter\WebonyxGraphQLSyncPromiseAdapter;
use Sam\Api\GraphQL\Load\Internal\Account\AccountDataLoader;
use Sam\Api\GraphQL\Load\Internal\Auction\AuctionDataLoader;
use Sam\Api\GraphQL\Load\Internal\Auction\AuctionFilterCondition;
use Sam\Api\GraphQL\Load\Internal\AuctionAuctioneer\AuctionAuctioneerDataLoader;
use Sam\Api\GraphQL\Load\Internal\AuctionBidder\AuctionBidderDataLoader;
use Sam\Api\GraphQL\Load\Internal\Currency\CurrencyDataLoader;
use Sam\Api\GraphQL\Load\Internal\Location\LocationDataLoader;
use Sam\Api\GraphQL\Load\Internal\Location\LocationFilterCondition;
use Sam\Api\GraphQL\Load\Internal\Lot\AuctionLotIdentifier;
use Sam\Api\GraphQL\Load\Internal\Lot\LotDataLoader;
use Sam\Api\GraphQL\Load\Internal\Lot\LotFilterCondition;
use Sam\Api\GraphQL\Load\Internal\LotCategory\LotCategoryDataLoader;
use Sam\Api\GraphQL\Load\Internal\LotImage\LotImageDataLoader;
use Sam\Api\GraphQL\Load\Internal\QueryPlanAnalyzerAwareTrait;
use Sam\Api\GraphQL\Load\Internal\TaxDefinition\TaxDefinitionDataLoader;
use Sam\Api\GraphQL\Load\Internal\TaxDefinition\TaxDefinitionFilterCondition;
use Sam\Api\GraphQL\Load\Internal\TaxSchema\TaxSchemaDataLoader;
use Sam\Api\GraphQL\Load\Internal\TaxSchema\TaxSchemaFilterCondition;
use Sam\Api\GraphQL\Load\Internal\Timezone\TimezoneDataLoader;
use Sam\Api\GraphQL\Load\Internal\User\UserDataLoader;
use Sam\Api\GraphQL\Type\Query\Definition\AccountType;
use Sam\Api\GraphQL\Type\Query\Definition\AuctionBidderType;
use Sam\Api\GraphQL\Type\Query\Definition\AuctioneerType;
use Sam\Api\GraphQL\Type\Query\Definition\AuctionType;
use Sam\Api\GraphQL\Type\Query\Definition\CurrencyType;
use Sam\Api\GraphQL\Type\Query\Definition\ItemImageType;
use Sam\Api\GraphQL\Type\Query\Definition\ItemType;
use Sam\Api\GraphQL\Type\Query\Definition\LocationType;
use Sam\Api\GraphQL\Type\Query\Definition\LotCategoryType;
use Sam\Api\GraphQL\Type\Query\Definition\LotType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxDefinitionType;
use Sam\Api\GraphQL\Type\Query\Definition\TaxSchemaType;
use Sam\Api\GraphQL\Type\Query\Definition\TimezoneType;
use Sam\Api\GraphQL\Type\Query\Definition\UserType;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DeferredDataLoader
 * @package Sam\Api\GraphQL\Load
 */
class DeferredDataLoader extends CustomizableClass
{
    use QueryPlanAnalyzerAwareTrait;

    protected WebonyxGraphQLSyncPromiseAdapter $promiseAdapter;
    protected int $systemAccountId;
    protected ?int $editorUserId;
    public readonly bool $isReadOnlyDb;

    /**
     * @var DataLoader[]
     */
    protected array $loaders = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        SyncPromiseAdapter $promiseAdapter,
        int $systemAccountId,
        ?int $editorUserId,
        bool $isReadOnlyDb = false
    ): static {
        $this->promiseAdapter = new WebonyxGraphQLSyncPromiseAdapter($promiseAdapter);
        $this->systemAccountId = $systemAccountId;
        $this->editorUserId = $editorUserId;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }

    public function applyQueryPlan(FieldDefinition $field, QueryPlan $queryPlan): void
    {
        $this->getQueryPlanAnalyzer()->applyQueryPlan($field, $queryPlan);
    }

    public function loadAccounts(): Deferred
    {
        return new Deferred(function () {
            $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(AccountType::NAME);
            $accounts = AccountDataLoader::new()->loadAll($dataFields, $this->isReadOnlyDb);
            foreach ($accounts as $accountId => $account) {
                $this->getAccountLoader()->prime($accountId, $account);
            }
            return $accounts;
        });
    }

    public function loadAccount(int $accountId): Promise
    {
        return $this->getAccountLoader()->load($accountId);
    }

    public function loadAuction(int $auctionId): Promise
    {
        return $this->getAuctionLoader()->load($auctionId);
    }

    public function loadAuctions(AuctionFilterCondition $filterCondition, int $limit, int $offset): Deferred
    {
        return new Deferred(function () use ($filterCondition, $limit, $offset) {
            $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(AuctionType::NAME);
            $auctions = AuctionDataLoader::new()->loadList(
                filterCondition: $filterCondition,
                limit: $limit,
                offset: $offset,
                fields: $dataFields,
                systemAccountId: $this->systemAccountId,
                editorUserId: $this->editorUserId,
                isReadOnlyDb: true
            );
            foreach ($auctions as $id => $auction) {
                $this->getAuctionLoader()->prime($id, $auction);
            }
            return $auctions;
        });
    }

    public function loadLocation(int $locationId): Promise
    {
        return $this->getLocationLoader()->load($locationId);
    }

    public function loadLocations(
        LocationFilterCondition $filterCondition,
        array $order,
        int $limit,
        int $offset,
    ): Deferred {
        return new Deferred(function () use ($filterCondition, $order, $limit, $offset) {
            $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(LocationType::NAME);
            $locations = LocationDataLoader::new()->loadList(
                filterCondition: $filterCondition,
                order: $order,
                limit: $limit,
                offset: $offset,
                fields: $dataFields,
                editorUserId: $this->editorUserId,
                systemAccountId: $this->systemAccountId,
                isReadOnlyDb: $this->isReadOnlyDb
            );
            foreach ($locations as $id => $location) {
                $this->getLocationLoader()->prime($id, $location);
            }
            return $locations;
        });
    }

    /**
     * If grouping was applied in a query, we cannot use all the fields that were collected
     * by QueryPlanAnalyzer, so we cannot use the execution result in subsequent queries
     */
    public function loadLocationsWithGrouping(
        array $fields,
        LocationFilterCondition $filterCondition,
        array $order,
        int $limit,
        int $offset,
    ): Deferred {
        return new Deferred(function () use ($fields, $filterCondition, $order, $limit, $offset) {
            $locations = LocationDataLoader::new()->loadListWithGrouping(
                filterCondition: $filterCondition,
                order: $order,
                limit: $limit,
                offset: $offset,
                fields: $fields,
                editorUserId: $this->editorUserId,
                systemAccountId: $this->systemAccountId,
                isReadOnlyDb: $this->isReadOnlyDb
            );
            return $locations;
        });
    }

    public function loadAuctionBidder(int $auctionBidderId): Promise
    {
        return $this->getAuctionBidderLoader()->load($auctionBidderId);
    }

    public function loadAuctioneer(int $auctioneerId): Promise
    {
        return $this->getAuctioneerLoader()->load($auctioneerId);
    }

    public function loadAuctioneers(): Deferred
    {
        return new Deferred(function () {
            $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(AuctioneerType::NAME);
            $auctioneers = AuctionAuctioneerDataLoader::new()->loadAll($this->systemAccountId, $dataFields, $this->isReadOnlyDb);
            foreach ($auctioneers as $id => $auctioneer) {
                $this->getAuctioneerLoader()->prime($id, $auctioneer);
            }
            return $auctioneers;
        });
    }

    public function loadTimezone(int $timezoneId): Promise
    {
        return $this->getTimezoneLoader()->load($timezoneId);
    }

    public function loadCurrency(int $currencyId): Promise
    {
        return $this->getCurrencyLoader()->load($currencyId);
    }

    public function loadUser(int $userId): Promise
    {
        return $this->getUserLoader()->load($userId);
    }

    public function loadAuctionLot(int $lotItemId, int $auctionId): Promise
    {
        return $this->getAuctionLotLoader()->load(AuctionLotIdentifier::new()->construct($lotItemId, $auctionId));
    }

    public function loadLotItem(int $lotItemId): Promise
    {
        return $this->getLotItemLoader()->load($lotItemId);
    }

    public function loadLotsForCatalog(
        int $auctionId,
        LotFilterCondition $filterCondition,
        ?string $sortCriteria,
        int $limit,
        int $offset
    ): Deferred {
        return new Deferred(function () use ($auctionId, $filterCondition, $sortCriteria, $limit, $offset) {
            $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(LotType::NAME);
            $lots = LotDataLoader::new()->loadForCatalog(
                auctionId: $auctionId,
                systemAccountId: $this->systemAccountId,
                editorUserId: $this->editorUserId,
                fields: $dataFields,
                filterCondition: $filterCondition,
                sortCriteria: $sortCriteria,
                limit: $limit,
                offset: $offset,
                isReadOnlyDb: true
            );
            foreach ($lots as $lot) {
                $this->getLotItemLoader()->prime((int)$lot['id'], $lot);
                if ($lot['auction_id']) {
                    $identifier = AuctionLotIdentifier::new()->construct((int)$lot['id'], (int)$lot['auction_id']);
                    $this->getAuctionLotLoader()->prime($identifier, $lot);
                }
            }
            return $lots;
        });
    }

    public function loadLotsForSearch(
        LotFilterCondition $filterCondition,
        ?string $sortCriteria,
        int $limit,
        int $offset
    ): Deferred {
        return new Deferred(function () use ($filterCondition, $sortCriteria, $limit, $offset) {
            $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(LotType::NAME);
            $lots = LotDataLoader::new()->loadForSearch(
                systemAccountId: $this->systemAccountId,
                editorUserId: $this->editorUserId,
                fields: $dataFields,
                filterCondition: $filterCondition,
                sortCriteria: $sortCriteria,
                limit: $limit,
                offset: $offset,
                isReadOnlyDb: true
            );
            foreach ($lots as $lot) {
                $this->getLotItemLoader()->prime((int)$lot['id'], $lot);
                if ($lot['auction_id']) {
                    $identifier = AuctionLotIdentifier::new()->construct((int)$lot['id'], (int)$lot['auction_id']);
                    $this->getAuctionLotLoader()->prime($identifier, $lot);
                }
            }
            return $lots;
        });
    }

    public function loadLotsForMyItems(
        string $pageType,
        LotFilterCondition $filterCondition,
        ?string $sortCriteria,
        int $limit,
        int $offset
    ): Deferred {
        return new Deferred(function () use ($pageType, $filterCondition, $sortCriteria, $limit, $offset) {
            $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(LotType::NAME);
            $lots = LotDataLoader::new()->loadForMyItems(
                systemAccountId: $this->systemAccountId,
                editorUserId: $this->editorUserId,
                fields: $dataFields,
                filterCondition: $filterCondition,
                sortCriteria: $sortCriteria,
                pageType: $pageType,
                limit: $limit,
                offset: $offset,
                isReadOnlyDb: true
            );
            foreach ($lots as $lot) {
                $this->getLotItemLoader()->prime((int)$lot['id'], $lot);
                if ($lot['auction_id']) {
                    $identifier = AuctionLotIdentifier::new()->construct((int)$lot['id'], (int)$lot['auction_id']);
                    $this->getAuctionLotLoader()->prime($identifier, $lot);
                }
            }
            return $lots;
        });
    }

    public function loadLotCategory(int $id): Promise
    {
        return $this->getLotCategoryLoader()->load($id);
    }

    public function loadLotItemCategories(int $lotItemId): Promise
    {
        return $this->getLotItemCategoriesLoader()->load($lotItemId);
    }

    public function loadTaxDefinition(int $taxDefinitionId): Promise
    {
        return $this->getTaxDefinitionLoader()->load($taxDefinitionId);
    }

    public function loadTaxDefinitions(
        TaxDefinitionFilterCondition $filterCondition,
        array $order,
        int $limit,
        int $offset,
    ): Deferred {
        return new Deferred(function () use ($filterCondition, $order, $limit, $offset) {
            $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(TaxDefinitionType::NAME);
            $definitions = TaxDefinitionDataLoader::new()->loadList(
                filterCondition: $filterCondition,
                orderBy: $order,
                limit: $limit,
                offset: $offset,
                fields: $dataFields,
                editorUserId: $this->editorUserId,
                systemAccountId: $this->systemAccountId,
                isReadOnlyDb: $this->isReadOnlyDb
            );
            foreach ($definitions as $id => $definition) {
                $this->getTaxDefinitionLoader()->prime($id, $definition);
            }
            return $definitions;
        });
    }

    public function loadTaxSchema(int $tasSchemaId): Promise
    {
        return $this->getTaxSchemaLoader()->load($tasSchemaId);
    }

    public function loadTaxSchemas(
        TaxSchemaFilterCondition $filterCondition,
        array $order,
        int $limit,
        int $offset,
    ): Deferred {
        return new Deferred(function () use ($filterCondition, $order, $limit, $offset) {
            $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(TaxSchemaType::NAME);
            $schemas = TaxSchemaDataLoader::new()->loadList(
                filterCondition: $filterCondition,
                orderBy: $order,
                limit: $limit,
                offset: $offset,
                fields: $dataFields,
                editorUserId: $this->editorUserId,
                systemAccountId: $this->systemAccountId,
                isReadOnlyDb: $this->isReadOnlyDb
            );
            foreach ($schemas as $id => $definition) {
                $this->getTaxSchemaLoader()->prime($id, $definition);
            }
            return $schemas;
        });
    }

    public function loadLotImages(int $lotItemId, int $limit): Promise
    {
        return $this->getLotImageLoader()
            ->load($lotItemId)
            ->then(fn(array $images) => array_slice($images, 0, $limit));
    }

    protected function getAccountLoader(): DataLoader
    {
        if (!isset($this->loaders['account'])) {
            $this->loaders['account'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(AccountType::NAME);
                $accounts = AccountDataLoader::new()->load($ids, $dataFields, $this->isReadOnlyDb);
                return $this->prepareResult($ids, $accounts);
            }, $this->promiseAdapter);
        }
        return $this->loaders['account'];
    }

    protected function getAuctionBidderLoader(): DataLoader
    {
        if (!isset($this->loaders['auction_bidder'])) {
            $this->loaders['auction_bidder'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(AuctionBidderType::NAME);
                $bidders = AuctionBidderDataLoader::new()->load($ids, $dataFields, $this->isReadOnlyDb);
                return $this->prepareResult($ids, $bidders);
            }, $this->promiseAdapter);
        }
        return $this->loaders['auction_bidder'];
    }

    protected function getLocationLoader(): DataLoader
    {
        if (!isset($this->loaders['location'])) {
            $this->loaders['location'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(LocationType::NAME);
                $locations = LocationDataLoader::new()->load($ids, $dataFields, $this->isReadOnlyDb);
                return $this->prepareResult($ids, $locations);
            }, $this->promiseAdapter);
        }
        return $this->loaders['location'];
    }

    protected function getAuctionLoader(): DataLoader
    {
        if (!isset($this->loaders['auction'])) {
            $this->loaders['auction'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(AuctionType::NAME);
                $auctions = AuctionDataLoader::new()->load($ids, $dataFields, $this->systemAccountId, $this->editorUserId, $this->isReadOnlyDb);
                return $this->prepareResult($ids, $auctions);
            }, $this->promiseAdapter);
        }
        return $this->loaders['auction'];
    }

    protected function getAuctioneerLoader(): DataLoader
    {
        if (!isset($this->loaders['auctioneer'])) {
            $this->loaders['auctioneer'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(AuctioneerType::NAME);
                $auctioneers = AuctionAuctioneerDataLoader::new()->load($ids, $dataFields, $this->isReadOnlyDb);
                return $this->prepareResult($ids, $auctioneers);
            }, $this->promiseAdapter);
        }
        return $this->loaders['auctioneer'];
    }

    protected function getTimezoneLoader(): DataLoader
    {
        if (!isset($this->loaders['timezone'])) {
            $this->loaders['timezone'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(TimezoneType::NAME);
                $auctioneers = TimezoneDataLoader::new()->load($ids, $dataFields, $this->isReadOnlyDb);
                return $this->prepareResult($ids, $auctioneers);
            }, $this->promiseAdapter);
        }
        return $this->loaders['timezone'];
    }

    protected function getCurrencyLoader(): DataLoader
    {
        if (!isset($this->loaders['currency'])) {
            $this->loaders['currency'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(CurrencyType::NAME);
                $currencies = CurrencyDataLoader::new()->load($ids, $dataFields, $this->isReadOnlyDb);
                return $this->prepareResult($ids, $currencies);
            }, $this->promiseAdapter);
        }
        return $this->loaders['currency'];
    }

    protected function getUserLoader(): DataLoader
    {
        if (!isset($this->loaders['user'])) {
            $this->loaders['user'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(UserType::NAME);
                $users = UserDataLoader::new()->load($ids, $dataFields, $this->isReadOnlyDb);
                return $this->prepareResult($ids, $users);
            }, $this->promiseAdapter);
        }
        return $this->loaders['user'];
    }

    protected function getLotItemLoader(): DataLoader
    {
        if (!isset($this->loaders['lot_item'])) {
            $this->loaders['lot_item'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(ItemType::NAME);
                $lotItems = LotDataLoader::new()->loadLotItems(
                    $ids,
                    $dataFields,
                    $this->systemAccountId,
                    $this->editorUserId,
                    $this->isReadOnlyDb
                );
                return $this->prepareResult($ids, $lotItems);
            }, $this->promiseAdapter);
        }
        return $this->loaders['lot_item'];
    }

    protected function getAuctionLotLoader(): DataLoader
    {
        if (!isset($this->loaders['auction_lot'])) {
            $this->loaders['auction_lot'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(LotType::NAME);
                $auctionLots = LotDataLoader::new()->loadAuctionLots(
                    $ids,
                    $dataFields,
                    $this->systemAccountId,
                    $this->editorUserId,
                    $this->isReadOnlyDb
                );
                $ids = array_map(static fn(AuctionLotIdentifier $identifier) => (string)$identifier, $ids);
                return $this->prepareResult($ids, $auctionLots);
            }, $this->promiseAdapter);
        }
        return $this->loaders['auction_lot'];
    }

    protected function getLotCategoryLoader(): DataLoader
    {
        if (!isset($this->loaders['lot_category'])) {
            $this->loaders['lot_category'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(LotCategoryType::NAME);
                $categories = LotCategoryDataLoader::new()->load($ids, $dataFields, $this->isReadOnlyDb);
                return $this->prepareResult($ids, $categories);
            }, $this->promiseAdapter);
        }
        return $this->loaders['lot_category'];
    }

    protected function getLotItemCategoriesLoader(): DataLoader
    {
        if (!isset($this->loaders['lot_item_categories'])) {
            $this->loaders['lot_item_categories'] = new DataLoader(function (array $lotItemIds) {
                $lotItemCategoryIds = LotCategoryDataLoader::new()->loadCategoryIdsForLotItem($lotItemIds, $this->isReadOnlyDb);
                $lotItemCategories = array_map(fn(array $categoryIds) => $this->getLotCategoryLoader()->loadMany($categoryIds), $lotItemCategoryIds);
                return $this->prepareResult($lotItemIds, $lotItemCategories);
            }, $this->promiseAdapter);
        }
        return $this->loaders['lot_item_categories'];
    }

    protected function getTaxDefinitionLoader(): DataLoader
    {
        if (!isset($this->loaders['tax_definition'])) {
            $this->loaders['tax_definition'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(TaxDefinitionType::NAME);
                $definitions = TaxDefinitionDataLoader::new()->load($ids, $dataFields, $this->editorUserId, $this->systemAccountId, $this->isReadOnlyDb);
                return $this->prepareResult($ids, $definitions);
            }, $this->promiseAdapter);
        }
        return $this->loaders['tax_definition'];
    }

    protected function getTaxSchemaLoader(): DataLoader
    {
        if (!isset($this->loaders['tax_schema'])) {
            $this->loaders['tax_schema'] = new DataLoader(function (array $ids) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(TaxSchemaType::NAME);
                $schemas = TaxSchemaDataLoader::new()->load($ids, $dataFields, $this->editorUserId, $this->systemAccountId, $this->isReadOnlyDb);
                return $this->prepareResult($ids, $schemas);
            }, $this->promiseAdapter);
        }
        return $this->loaders['tax_schema'];
    }

    protected function getLotImageLoader(): DataLoader
    {
        if (!isset($this->loaders['lot_image'])) {
            $this->loaders['lot_image'] = new DataLoader(function (array $lotItemIds) {
                $dataFields = $this->getQueryPlanAnalyzer()->getTypeDataFields(ItemImageType::NAME);
                $definitions = LotImageDataLoader::new()->loadForLot($lotItemIds, $dataFields, $this->isReadOnlyDb);
                return $this->prepareResult($lotItemIds, $definitions);
            }, $this->promiseAdapter);
        }
        return $this->loaders['lot_image'];
    }

    protected function prepareResult(array $requestedIds, array $indexedRows): Promise
    {
        $result = array_map(static fn(mixed $id) => $indexedRows[$id] ?? null, $requestedIds);
        return $this->promiseAdapter->createFulfilled($result);
    }
}
