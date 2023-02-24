<?php

namespace Sam\Core\Lot\LotList\Search\DataSource;

/**
 * Class DataSourceMysql
 * @package Sam\Core\Lot\LotList\Search
 */
class AuctionSearchMysql extends \Sam\Core\Lot\LotList\DataSourceMysql
{
    protected bool $isEnabledConsiderOptionHideUnsoldLots = true;

    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Define mapping for result fields
     * @param array $queryParts
     * @return array
     */
    protected function initializeFilterQueryParts(array $queryParts = []): array
    {
        /** @noinspection SuspiciousAssignmentsInspection */
        $queryParts = [];
        $queryParts['from'] = [
            'FROM auction_lot_item ali',
            'INNER JOIN lot_item li ON li.id = ali.lot_item_id',
        ];
        $queryParts['join'] = $this->getBaseJoins();
        $queryParts['where'] = $this->getBaseConditions();
        $queryParts['group'] = 'ali.auction_id';
        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }

}
