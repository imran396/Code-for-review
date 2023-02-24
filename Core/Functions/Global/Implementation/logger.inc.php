<?php
/** @noinspection PhpFunctionNamingConventionInspection */

use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Log\Text\LogDataComposer;
use Sam\Log\Support\SupportLogger;

/**
 * @param mixed $message
 * @param string|null $fileName
 */
function log_always(mixed $message = null, ?string $fileName = null)
{
    newSupportLogger()->always($message, $fileName, 2);
}

/**
 * @param mixed $message
 * @param string|null $fileName
 */
function log_error(mixed $message = null, ?string $fileName = null)
{
    newSupportLogger()->error($message, $fileName, 2);
}

/**
 * @param mixed $message
 * @param string|null $fileName
 */
function log_warning(mixed $message = null, ?string $fileName = null)
{
    newSupportLogger()->warning($message, $fileName, 2);
}

/**
 * @param mixed $message
 * @param string|null $fileName
 */
function log_info(mixed $message = null, ?string $fileName = null)
{
    newSupportLogger()->info($message, $fileName, 2);
}

/**
 * @param mixed $message
 * @param string|null $fileName
 */
function log_debug(mixed $message = null, ?string $fileName = null)
{
    newSupportLogger()->debug($message, $fileName, 2);
}

/**
 * @param mixed $message
 * @param string|null $fileName
 */
function log_trace(mixed $message = null, ?string $fileName = null)
{
    newSupportLogger()->trace($message, $fileName, 2);
}

/**
 * @param mixed $message
 * @param string|null $fileName
 */
function log_traceFile(mixed $message = null, ?string $fileName = null)
{
    newSupportLogger()->traceFile($message, $fileName, 2);
}

/**
 * @param mixed $message
 * @param string|null $fileName
 */
function log_traceQuery(mixed $message = null, ?string $fileName = null)
{
    newSupportLogger()->traceQuery($message, $fileName, 2);
}

/**
 * @param mixed $message
 * @param string|null $fileName
 * @param int|null $lines limit count of lines rendered for back-trace output
 */
function log_alwaysBackTrace(mixed $message = null, ?string $fileName = null, ?int $lines = null)
{
    newSupportLogger()->always(
        $message,
        $fileName,
        2,
        [
            SupportLogger::OP_IS_BACKTRACE => true,
            SupportLogger::OP_BACKTRACE_LINE_COUNT => $lines,
        ]
    );
}

/**
 * @param mixed $message
 * @param string|null $fileName
 * @param int|null $lines limit count of lines rendered for back-trace output
 */
function log_errorBackTrace(mixed $message = null, ?string $fileName = null, ?int $lines = null)
{
    newSupportLogger()->error(
        $message,
        $fileName,
        2,
        [
            SupportLogger::OP_IS_BACKTRACE => true,
            SupportLogger::OP_BACKTRACE_LINE_COUNT => $lines,
        ]
    );
}

/**
 * @param mixed $message
 * @param string|null $fileName
 * @param int|null $lines limit count of lines rendered for back-trace output
 */
function log_warningBackTrace(mixed $message = null, ?string $fileName = null, ?int $lines = null)
{
    newSupportLogger()->warning(
        $message,
        $fileName,
        2,
        [
            SupportLogger::OP_IS_BACKTRACE => true,
            SupportLogger::OP_BACKTRACE_LINE_COUNT => $lines,
        ]
    );
}

/**
 * @param mixed $message
 * @param string|null $fileName
 * @param int|null $lines limit count of lines rendered for back-trace output
 */
function log_infoBackTrace(mixed $message = null, ?string $fileName = null, ?int $lines = null)
{
    newSupportLogger()->info(
        $message,
        $fileName,
        2,
        [
            SupportLogger::OP_IS_BACKTRACE => true,
            SupportLogger::OP_BACKTRACE_LINE_COUNT => $lines,
        ]
    );
}

/**
 * @param mixed $message
 * @param string|null $fileName
 * @param int|null $lines limit count of lines rendered for back-trace output
 */
function log_debugBackTrace(mixed $message = null, ?string $fileName = null, ?int $lines = null)
{
    newSupportLogger()->debug(
        $message,
        $fileName,
        2,
        [
            SupportLogger::OP_IS_BACKTRACE => true,
            SupportLogger::OP_BACKTRACE_LINE_COUNT => $lines,
        ]
    );
}

/**
 * @param mixed $message
 * @param string|null $fileName
 * @param int|null $lines limit count of lines rendered for back-trace output
 */
function log_traceBackTrace(mixed $message = null, ?string $fileName = null, ?int $lines = null)
{
    newSupportLogger()->trace(
        $message,
        $fileName,
        2,
        [
            SupportLogger::OP_IS_BACKTRACE => true,
            SupportLogger::OP_BACKTRACE_LINE_COUNT => $lines,
        ]
    );
}

/**
 * @param mixed $message
 * @param string|null $fileName
 * @param int|null $lines limit count of lines rendered for back-trace output
 */
function log_traceFileBackTrace(mixed $message = null, ?string $fileName = null, ?int $lines = null)
{
    newSupportLogger()->traceFile(
        $message,
        $fileName,
        2,
        [
            SupportLogger::OP_IS_BACKTRACE => true,
            SupportLogger::OP_BACKTRACE_LINE_COUNT => $lines,
        ]
    );
}

/**
 * @param mixed $message
 * @param string|null $fileName
 * @param int|null $lines limit count of lines rendered for back-trace output
 */
function log_traceQueryBackTrace(mixed $message = null, ?string $fileName = null, ?int $lines = null)
{
    newSupportLogger()->traceQuery(
        $message,
        $fileName,
        2,
        [
            SupportLogger::OP_IS_BACKTRACE => true,
            SupportLogger::OP_BACKTRACE_LINE_COUNT => $lines,
        ]
    );
}

/**
 * Create ' (key1: value1, key2: value2, ...)' string from $parts
 * @param array|string $parts
 * @param string $separator
 * @return string
 */
function composeSuffix(mixed $parts, string $separator = ', '): string
{
    return LogDataComposer::new()->composeSuffix($parts, $separator);
}

/**
 * Create 'key1: value1, key2: value2, ...' string from $parts
 * @param array|string $parts
 * @param string $separator
 * @return string
 */
function composeLogData(mixed $parts, string $separator = ', '): string
{
    return LogDataComposer::new()->composeLogData($parts, $separator);
}

/**
 * Log and live
 * @param mixed $mixVar variable to debug
 */
function ll(mixed $mixVar = null)
{
    newSupportLogger()->always(
        $mixVar,
        null,
        2,
        [SupportLogger::OP_SPECIAL_MARK => '#']
    );
}

/**
 * Log back-trace
 * @param string|null $message
 */
function bt(?string $message = null)
{
    newSupportLogger()
        ->always(
            $message ?? 'Back-trace',
            null,
            2,
            [
                SupportLogger::OP_SPECIAL_MARK => '#',
                SupportLogger::OP_IS_BACKTRACE => true,
            ]
        );
}

function newSupportLogger(): SupportLogger
{
    return SupportLogger::new();
}

/**
 * Sleep and log
 * @param int $time
 * @param string $before
 * @param string $after
 */
function sleepAndLog(int $time, string $before = '', string $after = '')
{
    $before = $before ?: "Before sleep({$time})";
    $after = $after ?: "After sleep({$time})";
    ll($before);
    sleep($time);
    ll($after);
}

$debugLevel = 0;

/**
 * Turn on full logging
 */
function fullLog()
{
    global $debugLevel;
    $cfg = ConfigRepository::getInstance();
    $debugLevel = $cfg->get('core->general->debugLevel');
    $cfg->set('core->general->debugLevel', 7);
}

/**
 * Restore logging to config defaults
 */
function restoreLog()
{
    global $debugLevel;
    ConfigRepository::getInstance()->set('core->general->debugLevel', $debugLevel);
    $debugLevel = 0;
}
