<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;

/**
 * Class SyncInterest
 * @package Sam\Rtb\Command\Concrete\Hybrid
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
