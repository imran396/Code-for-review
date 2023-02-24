<?php

namespace Sam\Core\Constants;

/**
 * @package Sam\Core\Constants
 */
class Increment
{
    public const LEVEL_LOT = 1;
    public const LEVEL_AUCTION = 2;
    public const LEVEL_ACCOUNT = 3;
    /** @var string[] */
    public static array $levelNames = [self::LEVEL_LOT => 'lot', self::LEVEL_AUCTION => 'auction', self::LEVEL_ACCOUNT => 'account'];

    /**
     * Default value for increment for Advanced clerking style, when advanced increment table is not defined
     */
    public const ADVANCED_CLERKING_INCREMENT_DEFAULT = 1.;
}
