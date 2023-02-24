<?php

namespace Sam\Core\Lot\LotList\MyItems;

use Sam\Core\Lot\LotList\MyItems;

/**
 * Class AuctionBidding
 * @package Sam\Core\Lot\LotList\MyItems
 */
class AuctionBidding extends MyItems
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
