<?php

namespace Sam\Rtb\Live;

/**
 * Class ResponseHelper
 * @package Sam\Rtb\Live
 */
class ResponseHelper extends \Sam\Rtb\Base\ResponseHelper
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
