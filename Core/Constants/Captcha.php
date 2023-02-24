<?php

namespace Sam\Core\Constants;

/**
 * Class Captcha
 * @package Sam\Core\Constants
 */
class Captcha
{
    public const NONE = 'none';
    public const SIMPLE = 'simple';
    public const ALTERNATIVE = 'alternative';
    /** @var string[] */
    public static array $types = [
        self::NONE,
        self::SIMPLE,
        self::ALTERNATIVE
    ];

    public const SESSION_KEY = "captcha";
}
