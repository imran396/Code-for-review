<?php

namespace Sam\Core\Constants;

/**
 * Class Message
 * @package Sam\Core\Constants
 */
class Message
{
    public const INFO = 1;
    public const SUCCESS = 2;
    public const WARNING = 3;
    public const ERROR = 4;

    /** @var int[] */
    public static array $types = [
        self::INFO,
        self::SUCCESS,
        self::WARNING,
        self::ERROR,
    ];

    public const TITLE_INFO = 'Info';
    public const TITLE_SUCCESS = 'Success';
    public const TITLE_WARNING = 'Warning';
    public const TITLE_ERROR = 'Error';
}
