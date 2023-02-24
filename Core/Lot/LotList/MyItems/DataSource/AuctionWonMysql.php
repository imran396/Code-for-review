<?php

namespace Sam\Core\Lot\LotList\MyItems\DataSource;

/**
 * Class AuctionWonMysql
 * @package Sam\Core\Lot\LotList\MyItems\DataSource
 */
class AuctionWonMysql extends WonMysql
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
