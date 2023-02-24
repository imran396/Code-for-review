<?php

namespace Sam\Rtb\Command\Concrete\Live;

use Sam\Rtb\Live\HelpersAwareTrait;

/**
 * Class EnableAutoStart
 * @package Sam\Rtb\Command\Concrete\Live
 */
class EnableAutoStart extends \Sam\Rtb\Command\Concrete\Base\EnableAutoStart
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
