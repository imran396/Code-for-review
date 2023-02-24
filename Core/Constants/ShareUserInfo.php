<?php

namespace Sam\Core\Constants;

/**
 * Class ShareUserInfo
 * @package Sam\Core\Constants
 */
class ShareUserInfo
{
    public const NONE = 0;
    public const VIEW = 1;
    public const EDIT = 2;

    /** @var string[] */
    public static array $names = [
        self::NONE => 'None',
        self::VIEW => 'View',
        self::EDIT => 'Edit',
    ];
}
