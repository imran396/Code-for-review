<?php

namespace Sam\Core\Constants;

/**
 * Class ActionQueue
 * @package Sam\Core\Constants
 */
class ActionQueue
{
    public const LOW = 1;
    public const MEDIUM = 2;
    public const HIGH = 3;
    /** @var int[] */
    public static array $priorities = [self::LOW, self::MEDIUM, self::HIGH];

    public const AQID_GLOBAL_ORDER_UPDATE = 'GlobalOrderUpdate';
}
