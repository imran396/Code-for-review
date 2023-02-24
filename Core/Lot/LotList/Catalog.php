<?php

namespace Sam\Core\Lot\LotList;

use Sam\Core\Lot\LotList;

/**
 * Class Catalog
 * @package Sam\Core\Lot\LotList
 */
class Catalog extends LotList
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
