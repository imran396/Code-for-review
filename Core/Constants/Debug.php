<?php

namespace Sam\Core\Constants;

/**
 * Class Debug
 * @package Sam\Core\Constants
 */
class Debug
{
    public const ALWAYS = 0;
    public const ERROR = 1;
    public const WARNING = 2;
    public const INFO = 3;
    public const DEBUG = 4;
    public const TRACE = 5;
    public const TRACE_FILE = 6;
    public const TRACE_QUERY = 7;
    /** @var int[] */
    public static array $levels = [
        self::ALWAYS,
        self::ERROR,
        self::WARNING,
        self::INFO,
        self::DEBUG,
        self::TRACE,
        self::TRACE_FILE,
        self::TRACE_QUERY,
    ];
    /** @var string[] */
    public static array $levelNames = [
        self::ALWAYS => 'Always',
        self::ERROR => 'Error',
        self::WARNING => 'Warning',
        self::INFO => 'Info',
        self::DEBUG => 'Debug',
        self::TRACE => 'Trace',
        self::TRACE_FILE => 'Trace file',
        self::TRACE_QUERY => 'Trace query',
    ];

    /** @var string[] */
    public static array $levelCodes = [
        self::ERROR => 'ERROR',
        self::WARNING => 'WARNING',
        self::INFO => 'INFO',
        self::DEBUG => 'DEBUG',
        self::TRACE => 'TRACE',
        self::TRACE_FILE => 'TRACE-F',
        self::TRACE_QUERY => 'TRACE-Q',
    ];
}
