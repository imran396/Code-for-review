<?php

namespace Sam\Core\Lot\LotList;

/**
 * Class SearchAll
 * @package Sam\Core\Lot\LotList
 */
class SearchAll
    extends \Sam\Core\Lot\LotList
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
