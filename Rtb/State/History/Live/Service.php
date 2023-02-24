<?php

namespace Sam\Rtb\State\History\Live;

/**
 * Class Service
 * @package Sam\Rtb\State\History\Live
 */
class Service extends \Sam\Rtb\State\History\Base\Service
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
