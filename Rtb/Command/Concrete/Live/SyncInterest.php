<?php

namespace Sam\Rtb\Command\Concrete\Live;

use Sam\Rtb\Live\HelpersAwareTrait;

/**
 * Class SyncInterest
 * @package Sam\Rtb\Command\Concrete\Live
 */
class SyncInterest extends \Sam\Rtb\Command\Concrete\Base\SyncInterest
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
