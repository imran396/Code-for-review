<?php

namespace Sam\Core\Lot\LotList\Search;

use Sam\Core\Lot\LotList;

/**
 * Class Search
 * @package Sam\Core\Lot\LotList
 */
class AuctionSearch extends LotList
{
    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
