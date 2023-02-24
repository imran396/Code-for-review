<?php

namespace Sam\Core\Constants;

/**
 * Class UserLog
 * @package Sam\Core\Constants
 */
class UserLog
{
    /**
     * Possible Logging mode options for user profile fields logging
     * Defined by configuration constant: core->user->logProfile->mode
     */
    public const MODE_OFF = 0;     // do not log changes in profile fields
    public const MODE_USER = 1;    // log only user's own changes
    public const MODE_ALL = 2;     // log any changes (user's own, admin and system)
    /** @var int[] */
    public static array $modes = [self::MODE_OFF, self::MODE_USER, self::MODE_ALL];
    /** @var string[] */
    public static array $modeDescriptions = [
        self::MODE_OFF => 'do not log changes in profile fields',
        self::MODE_USER => 'log only user\'s own changes',
        self::MODE_ALL => 'log any changes (user\'s own, admin and system)',
    ];
}
