<?php

namespace Sam\Rtb\Command\Concrete\Live;

use Sam\Rtb\Live\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Live\LiveRtbCommandHelperAwareTrait;

/**
 * Class ResetLot
 * @package Sam\Rtb\Command\Concrete\Live
 */
class ResetLot extends \Sam\Rtb\Command\Concrete\Base\ResetLot
{
    use HelpersAwareTrait;
    use LiveRtbCommandHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
