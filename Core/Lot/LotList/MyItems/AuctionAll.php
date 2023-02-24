<?php

namespace Sam\Core\Lot\LotList\MyItems;

use Sam\Core\Lot\LotList\MyItems;

/**
 * Class AuctionAll
 * @package Sam\Core\Lot\LotList\MyItems
 */
class AuctionAll extends MyItems
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
