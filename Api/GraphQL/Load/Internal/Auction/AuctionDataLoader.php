<?php
/**
 * SAM-10467: Implement a GraphQL nested structure for a single auction
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Auction;

use Sam\Account\Filter\AccountApplicationFilterDetectorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\Api\GraphQL\Load\Internal\Auction\Internal\DataSourceMysql;

/**
 * Class AuctionDataLoader
 * @package Sam\Api\GraphQL\Load\Internal\Auction
 */
class AuctionDataLoader extends CustomizableClass
{
    use AccountApplicationFilterDetectorAwareTrait;
    use AuctionCustomFieldLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(
        array $auctionIds,
        array $fields,
        int $systemAccountId,
        ?int $editorUserId,
        bool $isReadOnlyDb = false
    ): array {
        $dataSource = $this->prepareDatasource($fields, $editorUserId, $isReadOnlyDb);
        $accountId = $this->getAccountApplicationFilterDetector()
            ->setSystemAccountId($systemAccountId)
            ->detectSingle();
        if ($accountId) {
            $dataSource->filterAccountId($accountId);
        }
        $dataSource->filterIds($auctionIds);
        $rows = $dataSource->getResults();
        return ArrayHelper::produceIndexedArray($rows, 'id');
    }

    public function loadList(
        AuctionFilterCondition $filterCondition,
        int $limit,
        int $offset,
        array $fields,
        int $systemAccountId,
        ?int $editorUserId,
        bool $isReadOnlyDb = false
    ): array {
        $dataSource = $this->prepareDatasource($fields, $editorUserId, $isReadOnlyDb);
        $accountId = $this->getAccountApplicationFilterDetector()
            ->setSystemAccountId($systemAccountId)
            ->setSelectedAccountId(ArrayCast::makeIntArray($filterCondition->account))
            ->detectSingle();
        if ($accountId) {
            $dataSource->filterAccountId($accountId);
        }
        $dataSource->enableRegisteredInAuction($filterCondition->onlyRegisteredIn);
        $dataSource->filterAuctionTypes(ArrayCast::makeStringArray($filterCondition->auctionType));
        $dataSource->filterAuctioneerId($filterCondition->auctioneer);
        $dataSource->filterVisibleAuctionStatus($filterCondition->status);
        $dataSource->setLimit($limit);
        $dataSource->setOffset($offset);
        $dataSource->setOrder('status');
        $rows = $dataSource->getResults();
        return ArrayHelper::produceIndexedArray($rows, 'id');
    }

    protected function prepareDatasource(array $fields, ?int $editorUserId, bool $isReadOnlyDb = false): DataSourceMysql
    {
        $fields[] = 'id';
        $fields = array_unique($fields);
        $dataSource = DataSourceMysql::new();
        $dataSource->enableReadOnlyDb($isReadOnlyDb);
        $auctionCustomFields = $this->getAuctionCustomFieldLoader()->loadAll(true);
        $dataSource->setMappedCustomFields($auctionCustomFields);
        $dataSource->filterAuctionAccessCheck([Constants\Auction::ACCESS_RESTYPE_AUCTION_VISIBILITY]);
        $dataSource->filterAuctionStatuses(Constants\Auction::$availableAuctionStatuses);
        $dataSource->filterPublished(true);
        $dataSource->setUserId($editorUserId);
        $dataSource->setResultSetFields($fields);
        return $dataSource;
    }
}
