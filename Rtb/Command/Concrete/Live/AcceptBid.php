<?php

namespace Sam\Rtb\Command\Concrete\Live;

use Sam\Rtb\Live\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Live\LiveRtbCommandHelperAwareTrait;

/**
 * Class AcceptBid
 * @package Sam\Rtb\Command\Concrete\Live
 */
class AcceptBid extends \Sam\Rtb\Command\Concrete\Base\AcceptBid
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
