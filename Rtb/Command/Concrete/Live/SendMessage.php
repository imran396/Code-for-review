<?php

namespace Sam\Rtb\Command\Concrete\Live;

use Sam\Rtb\Live\HelpersAwareTrait;

/**
 * Class SendMessage
 * @package Sam\Rtb\Command\Concrete\Live
 */
class SendMessage extends \Sam\Rtb\Command\Concrete\Base\SendMessage
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
