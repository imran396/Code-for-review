<?php

namespace Sam\Core\Lot\LotList\MyItems\DataSource;

/**
 * Class AuctionWatchlistMysql
 * @package Sam\Core\Lot\LotList\MyItems\DataSource
 */
class AuctionWatchlistMysql extends WatchlistMysql
{
    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $queryParts
     * @return array
     */
    protected function initializeFilterQueryParts(array $queryParts = []): array
    {
        $queryParts = parent::initializeFilterQueryParts($queryParts);
        $queryParts['group'] = 'ali.auction_id';
        return $queryParts;
    }
}
