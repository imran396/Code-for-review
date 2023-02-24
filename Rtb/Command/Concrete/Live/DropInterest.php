<?php

namespace Sam\Rtb\Command\Concrete\Live;

use Sam\Rtb\Live\HelpersAwareTrait;

/**
 * Class DropInterest
 * @package Sam\Rtb\Command\Concrete\Live
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
