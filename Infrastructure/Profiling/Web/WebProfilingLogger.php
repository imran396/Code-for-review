<?php
/**
 * SAM-8022: Extract log output of web page profiling to separate service
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Profiling\Web;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Transform\File\FileSizeRenderer;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class WebProfilingLogger
 * @package Sam\Infrastructure\Profiling\Web
 */
class WebProfilingLogger extends CustomizableClass
{
    use OptionalsTrait;
    use ServerRequestReaderAwareTrait;

    // Input values

    public const OP_CURRENT_LOG_LEVEL = 'currentLogLevel';
    public const OP_LIMIT_LOG_LEVEL = 'limitLogLevel';
    public const OP_PROFILING_ENABLED = 'isProfiling';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param float $startTs
     * @param string $message
     * @param array $logData
     */
    public function log(float $startTs = 0, string $message = 'Profiling info', array $logData = []): void
    {
        $isProfilingEnabled = (bool)$this->fetchOptional(self::OP_PROFILING_ENABLED);
        if (!$isProfilingEnabled) {
            return;
        }

        $currentLogLevel = (int)$this->fetchOptional(self::OP_CURRENT_LOG_LEVEL);
        $limitLogLevel = (int)$this->fetchOptional(self::OP_LIMIT_LOG_LEVEL);
        if ($currentLogLevel < $limitLogLevel) {
            return;
        }

        if ($startTs) {
            $logData['exec time'] = round((microtime(true) - $startTs) * 1000) . 'ms';
        }

        $fileSizeRenderer = FileSizeRenderer::new();
        $serverRequestReader = $this->getServerRequestReader();
        $logData += [
            'max mem' => $fileSizeRenderer->renderHumanReadableSize(memory_get_peak_usage(true)),
            'curr mem' => $fileSizeRenderer->renderHumanReadableSize(memory_get_usage(true)),
            'remote addr' => $serverRequestReader->remoteAddr()
                . ':' . $serverRequestReader->remotePort(),
            'url' => $serverRequestReader->requestUri(),
            session_name() => session_id() ?: 'no session',
        ];
        $message .= ' - ' . composeLogData($logData);
        $message = trim($message, '- ');

        log_always($message);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_PROFILING_ENABLED] ??= static fn(): int => ConfigRepository::getInstance()->get('core->debug->web->profiling->enabled');
        $optionals[self::OP_LIMIT_LOG_LEVEL] ??= static fn(): int => ConfigRepository::getInstance()->get('core->debug->web->profiling->logLevel');
        $optionals[self::OP_CURRENT_LOG_LEVEL] ??= static fn(): int => ConfigRepository::getInstance()->get('core->general->debugLevel');
        $this->setOptionals($optionals);
    }
}
