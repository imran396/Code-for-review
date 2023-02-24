<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class EnableEnterFloorNo
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class EnableEnterFloorNo extends \Sam\Rtb\Command\Concrete\Base\EnableEnterFloorNo
{
    use HelpersAwareTrait;
    use HybridRtbCommandHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
