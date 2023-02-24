<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;

/**
 * Class DropInterest
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class DropInterest extends \Sam\Rtb\Command\Concrete\Base\DropInterest
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
