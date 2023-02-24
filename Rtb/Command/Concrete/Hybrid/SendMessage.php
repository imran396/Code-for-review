<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;

/**
 * Class SendMessage
 * @package Sam\Rtb\Command\Concrete\Hybrid
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
