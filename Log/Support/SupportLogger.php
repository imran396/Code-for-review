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

namespace Sam\Log\Support;

use Sam;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Log\Support\Internal\Content\ContentBuilder;
use Sam\Log\Support\Internal\Content\ContentBuilderCreateTrait;
use Sam\Log\Support\Internal\Path\PathResolverCreateTrait;
use Sam\Log\Support\Internal\Write\LogWriterAwareTrait;

/**
 * Class Logger
 * @package Sam\Log
 */
class SupportLogger extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ContentBuilderCreateTrait;
    use EditorUserAwareTrait;
    use LogWriterAwareTrait;
    use PathResolverCreateTrait;

    public const OP_BACKTRACE_LINE_COUNT = 'backtraceLineCount'; // int
    public const OP_IS_BACKTRACE = 'isBacktrace'; // bool
    public const OP_LOG_FILE_NAME = 'logFileName'; // string
    public const OP_SPECIAL_MARK = 'specialMark'; // string
    public const OP_EDITOR_USER_INFO = 'editorUser'; // string
    public const OP_MAX_LOG_LENGTH = 'maxLogLength'; // int
    public const OP_IS_SINGLE_LINE = 'isSingleLine'; // bool
    public const OP_IS_MULTI_LINE_PREFIX = 'isMultiLinePrefix'; // bool

    protected ?string $editorUserInfo = null;

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
     * @param mixed $message message to log. Can be callable function.
     * @param int $deep deep of the nesting level, where log() is called
     * @param array $optionals = [
     *     self::OP_BACKTRACE_LINE_COUNT => int,
     *     self::OP_EDITOR_USER_INFO => string,
     *     self::OP_IS_BACKTRACE => bool, (def: false)
     *     self::OP_LOG_FILE_NAME => string,
     *     self::OP_SPECIAL_MARK => string, (def: ''); replace isSpecialMark(),
     *     self::OP_MAX_LOG_LENGTH => int, (def: null - means disabled)
     *     self::OP_IS_SINGLE_LINE => bool, (def: false)
     *     self::OP_IS_MULTI_LINE_PREFIX => bool, (def: false)
     * ]
     */
    public function log(
        int $logLevel,
        mixed $message,
        int $deep = 0,
        array $optionals = []
    ): void {
        if (!$this->isLogLevelEnough($logLevel)) {
            return;
        }

        $optionals[ContentBuilder::OP_EDITOR_USER_INFO] = $optionals[self::OP_EDITOR_USER_INFO]
            ?? $this->getEditorUserId();
        $optionals[ContentBuilder::OP_MAX_LOG_LENGTH] = $optionals[self::OP_MAX_LOG_LENGTH]
            ?? $this->cfg()->get('core->debug->logger->maxLogLength');
        $optionals[ContentBuilder::OP_IS_SINGLE_LINE] = $optionals[self::OP_IS_SINGLE_LINE]
            ?? $this->cfg()->get('core->debug->logger->singleLine');
        $optionals[ContentBuilder::OP_IS_MULTI_LINE_PREFIX] = $optionals[self::OP_IS_MULTI_LINE_PREFIX]
            ?? $this->cfg()->get('core->debug->logger->multiLinePrefix');

        $fullMessage = $this->createContentBuilder()->build(
            $logLevel,
            $this->makeStringMessage($message),
            $deep + 3,
            $optionals
        );
        $fileRootPath = $this->makeLogFileName($optionals);
        $this->getLogWriter()->write($fullMessage, $fileRootPath);
    }

    /**
     * Check if log level meets debug level defined by installation config.
     * @param int $logLevel
     * @return bool
     */
    public function isLogLevelEnough(int $logLevel): bool
    {
        return $this->cfg()->get('core->general->debugLevel') >= $logLevel;
    }

    /**
     * @param mixed $message
     * @param string|null $logFileName
     * @param int $deep
     * @param array $optionals
     */
    public function always(
        mixed $message,
        ?string $logFileName = null,
        int $deep = 1,
        array $optionals = []
    ): void {
        $optionals[self::OP_LOG_FILE_NAME] = $logFileName;
        $this->log(
            Constants\Debug::ALWAYS,
            $message,
            $deep,
            $optionals
        );
    }

    /**
     * @param mixed $message
     * @param string|null $logFileName
     * @param int $deep
     * @param array $optionals
     */
    public function error(
        mixed $message,
        ?string $logFileName = null,
        int $deep = 1,
        array $optionals = []
    ): void {
        $optionals[self::OP_LOG_FILE_NAME] = $logFileName;
        $this->log(
            Constants\Debug::ERROR,
            $message,
            $deep,
            $optionals
        );
    }

    /**
     * @param mixed $message
     * @param string|null $logFileName
     * @param int $deep
     * @param array $optionals
     */
    public function warning(
        mixed $message,
        ?string $logFileName = null,
        int $deep = 1,
        array $optionals = []
    ): void {
        $optionals[self::OP_LOG_FILE_NAME] = $logFileName;
        $this->log(
            Constants\Debug::WARNING,
            $message,
            $deep,
            $optionals
        );
    }

    /**
     * @param mixed $message
     * @param string|null $logFileName
     * @param int $deep
     * @param array $optionals
     */
    public function info(
        mixed $message,
        ?string $logFileName = null,
        int $deep = 1,
        array $optionals = []
    ): void {
        $optionals[self::OP_LOG_FILE_NAME] = $logFileName;
        $this->log(
            Constants\Debug::INFO,
            $message,
            $deep,
            $optionals
        );
    }

    /**
     * @param mixed $message
     * @param string|null $logFileName
     * @param int $deep
     * @param array $optionals
     */
    public function debug(
        mixed $message,
        ?string $logFileName = null,
        int $deep = 1,
        array $optionals = []
    ): void {
        $optionals[self::OP_LOG_FILE_NAME] = $logFileName;
        $this->log(
            Constants\Debug::DEBUG,
            $message,
            $deep,
            $optionals
        );
    }

    /**
     * @param mixed $message
     * @param string|null $logFileName
     * @param int $deep
     * @param array $optionals
     */
    public function trace(
        mixed $message,
        ?string $logFileName = null,
        int $deep = 1,
        array $optionals = []
    ): void {
        $optionals[self::OP_LOG_FILE_NAME] = $logFileName;
        $this->log(
            Constants\Debug::TRACE,
            $message,
            $deep,
            $optionals
        );
    }

    /**
     * @param mixed $message
     * @param string|null $logFileName
     * @param int $deep
     * @param array $optionals
     */
    public function traceFile(
        mixed $message,
        ?string $logFileName = null,
        int $deep = 1,
        array $optionals = []
    ): void {
        $optionals[self::OP_LOG_FILE_NAME] = $logFileName;
        $this->log(
            Constants\Debug::TRACE_FILE,
            $message,
            $deep,
            $optionals
        );
    }

    /**
     * @param mixed $message
     * @param string|null $logFileName
     * @param int $deep
     * @param array $optionals
     */
    public function traceQuery(
        mixed $message,
        ?string $logFileName = null,
        int $deep = 1,
        array $optionals = []
    ): void {
        $optionals[self::OP_LOG_FILE_NAME] = $logFileName;
        // $message = TextTransformer::new()->replaceWhitespaces($message, ' ');
        $this->log(
            Constants\Debug::TRACE_QUERY,
            $message,
            $deep,
            $optionals
        );
    }

    protected function makeLogFileName(array $optionals = []): string
    {
        $logFileName = $optionals[self::OP_LOG_FILE_NAME] ?? null;
        $pathResolver = $this->createPathResolver();
        if ($logFileName) {
            return $pathResolver->detectLogDir() . DIRECTORY_SEPARATOR . $logFileName;
        }
        return $pathResolver->detectErrorLogFromIni();
    }

    protected function getEditorUserInfo(): string
    {
        if ($this->editorUserInfo === null) {
            $this->editorUserInfo = (string)($this->getEditorUserId() ?? '');
        }
        return $this->editorUserInfo;
    }

    /**
     * Prepare string output message
     * @param $message
     * @return string
     */
    protected function makeStringMessage($message): string
    {
        if (is_string($message)) {
            return $message;
        }

        if (is_callable($message)) {
            return $message();
        }

        return var_export($message, true);
    }
}
