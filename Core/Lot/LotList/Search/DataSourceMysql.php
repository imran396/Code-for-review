<?php

namespace Sam\Core\Lot\LotList\Search;

/**
 * Class DataSourceMysql
 * @package Sam\Core\Lot\LotList\Search
 */
class DataSourceMysql extends \Sam\Core\Lot\LotList\DataSourceMysql
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
        $queryParts['from'] = [
            'FROM auction_lot_item ali',
            'INNER JOIN lot_item li ON li.id = ali.lot_item_id',
        ];
        $queryParts['join'] = $this->getBaseJoins();
        $queryParts['where'] = $this->getBaseConditions();

        $queryParts = parent::initializeFilterQueryParts($queryParts);
        return $queryParts;
    }
}
