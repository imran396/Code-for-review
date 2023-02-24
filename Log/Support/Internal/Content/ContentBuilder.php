<?php
/**
 * Main application logging class to file (e.g. logs/error.log)
 *
 * SAM-9561: Refactor support logger
 * SAM-3312: Replace old error logging with new way https://bidpath.atlassian.net/browse/SAM-3312
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Feb 16, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Support\Internal\Content;

use Exception;
use RuntimeException;
use Sam;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Log\Support\Internal\Content\Internal\Load\DataProviderCreateTrait;

/**
 * Class Logger
 * @package Sam\Log
 */
class ContentBuilder extends CustomizableClass
{
    use DataProviderCreateTrait;
    use ServerRequestReaderAwareTrait;

    public const OP_BACKTRACE_LINE_COUNT = 'backtraceLineCount'; // int
    public const OP_IS_BACKTRACE = 'isBacktrace'; // bool
    public const OP_SPECIAL_MARK = 'specialMark'; // string
    public const OP_EDITOR_USER_INFO = 'editorUser'; // string
    public const OP_MAX_LOG_LENGTH = 'maxLogLength'; // int
    public const OP_IS_SINGLE_LINE = 'isSingleLine'; // bool
    public const OP_IS_MULTI_LINE_PREFIX = 'isMultiLinePrefix'; // bool

    /**
     * Get instance of Logger
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Log a message
     * @param int $logLevel
     * @param string $message message to log
     * @param int $deep deep of the nesting level, where log() is called
     * @param array $optionals = [
     *     self::OP_BACKTRACE_LINE_COUNT => int,
     *     self::OP_IS_BACKTRACE => bool, (def: false)
     *     self::OP_SPECIAL_MARK => string, (def: ''); replace isSpecialMark(),
     *     self::OP_EDITOR_USER_INFO => string,
     *     self::OP_MAX_LOG_LENGTH => int, (def: null - means disabled)
     *     self::OP_IS_SINGLE_LINE => bool, (def: false)
     *     self::OP_IS_MULTI_LINE_PREFIX => bool, (def: false)
     * ]
     * @return string
     */
    public function build(
        int $logLevel,
        string $message,
        int $deep = 0,
        array $optionals = []
    ): string {
        $dataProvider = $this->createDataProvider();
        $ip = $this->getServerRequestReader()->remoteAddr();
        $backTrace = $dataProvider->detectDebugBacktrace($deep);
        $file = $dataProvider->detectBasename($backTrace[$deep - 1]['file'] ?? '');
        $class = $backTrace[$deep]['class'] ?? '';
        $function = $backTrace[$deep]['function'] ?? '';
        $line = $backTrace[$deep - 1]['line'] ?? '';
        $levelCodes = Constants\Debug::$levelCodes;
        $logLevelName = $levelCodes[$logLevel] ?? '';
        $memoryUsage = $dataProvider->detectMemoryUsage() . 'B';
        $userId = $optionals[self::OP_EDITOR_USER_INFO] ?? '';
        $processGuid = $dataProvider->detectProcessGuid();
        $specialMarkRepeated = $this->makeSpecialMark($optionals);
        $dateFormatted = $this->makeDateFormatted();
        $message .= $this->processBackTrace($optionals);

        $length = $optionals[self::OP_MAX_LOG_LENGTH] ?? null;
        if ($length) {
            $message = substr($message, 0, $length);
        }

        $isSingleLine = $optionals[self::OP_IS_SINGLE_LINE] ?? false;
        if ($isSingleLine) {
            $message = str_replace(["\r\n", "\r", "\n"], ' ', $message);
        }

        $linePrefix =
            $specialMarkRepeated .
            $logLevelName . ';' .
            $ip . ';' .
            $userId . ';' .
            $processGuid . ';' .
            $memoryUsage . ';' .
            $file . '(' .
            $line . ')' .
            ($class || $function ? ' ' : '') .
            $class .
            ($class ? '::' : '') . $function . ':';
        $linePrefix = sprintf('[%s] %s', $dateFormatted, $linePrefix);

        $isMultiLinePrefix = $optionals[self::OP_IS_MULTI_LINE_PREFIX] ?? false;
        if ($isMultiLinePrefix) {
            return $this->toPrefixedMultipleLine($message, $linePrefix);
        }

        return $linePrefix . ' ' . $message . PHP_EOL;
    }

    protected function toPrefixedMultipleLine(string $message, string $linePrefix): string
    {
        $fullMessage = '';
        $separator = "\r\n";
        $line = strtok($message, $separator);
        $firstLine = true;

        while ($line !== false) {
            $fullMessage .= $linePrefix . ($firstLine ? ' ' : '+') . $line . PHP_EOL;
            $line = strtok($separator);
            $firstLine = false;
        }
        return $fullMessage;
    }

    /**
     * @param array $optionals
     * @return string
     */
    protected function processBackTrace(array $optionals = []): string
    {
        $trace = '';
        $isBackTrace = $optionals[self::OP_IS_BACKTRACE] ?? false;
        if ($isBackTrace) {
            try {
                throw new RuntimeException('ignore this string');
            } catch (RuntimeException $e) {
                /**
                 * TODO: Can we use value provided by self::OP_DEBUG_BACKTRACE instead trick with exception?
                 * Does debug_backtrace() supply the same as this hack?
                 */
                $trace = $e->getTraceAsString();
                $n = "\n";
                $lineCount = $optionals[self::OP_BACKTRACE_LINE_COUNT] ?? 0;
                if ($lineCount) {
                    $allTraceLines = explode($n, $trace);
                    $total = count($allTraceLines);
                    $traceLines = array_slice($allTraceLines, 0, $lineCount);
                    $traceLines[] = "... back-trace is limited to {$lineCount} lines" . composeSuffix(['total' => $total]);
                    $trace = implode($n, $traceLines);
                }
                $trace = $n . $trace;
            }
        }
        return $trace;
    }

    protected function makeSpecialMark(array $optionals = []): string
    {
        $specialMark = $optionals[self::OP_SPECIAL_MARK] ?? '';
        $specialMarkRepeated = $specialMark ? str_repeat($specialMark, 10) : '';
        return $specialMarkRepeated;
    }

    protected function makeDateFormatted(): string
    {
        try {
            $now = $this->createDataProvider()->detectCurrentDateUtc();
            $dateFormatted = $now->format('d-M-Y H:i:s.v e');
        } catch (Exception $e) {
            $dateFormatted = $e->getMessage() . ' - ' . $e->getCode();
        }
        return $dateFormatted;
    }
}
