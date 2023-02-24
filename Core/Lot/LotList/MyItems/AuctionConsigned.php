<?php

namespace Sam\Core\Lot\LotList\MyItems;

use Sam\Core\Lot\LotList\MyItems;

/**
 * Class AuctionConsigned
 * @package Sam\Core\Lot\LotList\MyItems
 */
class AuctionConsigned extends MyItems
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
