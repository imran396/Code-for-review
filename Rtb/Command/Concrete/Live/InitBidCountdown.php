<?php

namespace Sam\Rtb\Command\Concrete\Live;

use Sam\Rtb\Live\HelpersAwareTrait;

/**
 * Class InitBidCountdown
 * @package Sam\Rtb\Command\Concrete\Live
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
