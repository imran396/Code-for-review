<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;

/**
 * Class InitBidCountdown
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class InitBidCountdown extends \Sam\Rtb\Command\Concrete\Base\InitBidCountdown
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
