<?php

namespace Sam\Rtb\Command\Concrete\Live;

use Sam\Rtb\Hybrid\HelpersAwareTrait;

/**
 * Class StopAuction
 * @package Sam\Rtb\Command\Concrete\Live
 */
class StopAuction extends \Sam\Rtb\Command\Concrete\Base\StopAuction
{
    use HelpersAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
