<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;

/**
 * Class EnableAutoStart
 * @package Sam\Rtb\Command\Concrete\Hybrid
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
