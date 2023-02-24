<?php

namespace Sam\Core\Constants;

/**
 * Class Soap
 * @package Sam\Core\Constants
 */
class Soap
{
    public const ESB_CLEAR = 'clear';
    public const ESB_DEFAULT = 'default';
    public const ESB_NONE = '';
    /** @var string[] */
    public static array $emptyStringBehaviors = [self::ESB_CLEAR, self::ESB_DEFAULT, self::ESB_NONE];
    /** @var string[] */
    public static array $emptyStringBehaviorNames = [
        self::ESB_CLEAR => 'Clear',
        self::ESB_DEFAULT => 'Default',
        self::ESB_NONE => 'No action',
    ];
}
