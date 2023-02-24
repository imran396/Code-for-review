<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Internal;

use Google\Protobuf\Internal\Message;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Class SyncDataResponseRenderer
 * @package Sam\AuctionLot\Sync\Internal
 */
class SyncDataResponseRenderer extends CustomizableClass
{
    use OptionalsTrait;
    use SupportLoggerAwareTrait;

    public const OP_BINARY_PROTOCOL_ENABLED = 'protobufBinaryProtocolEnabled';
    public const OP_IS_PROFILING = 'isProfiling';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Serializes and outputs the protobuf message with an auction lot sync data
     *
     * @param Message $message
     * @return string
     */
    public function render(Message $message): string
    {
        $tmpTs = microtime(true);
        $output = $this->fetchOptional(self::OP_BINARY_PROTOCOL_ENABLED)
            ? $message->serializeToString()
            : $message->serializeToJsonString();

        $isProfilingEnabled = (bool)$this->fetchOptional(self::OP_IS_PROFILING);
        if ($isProfilingEnabled) {
            $this->getSupportLogger()->trace('message encode: ' . ((microtime(true) - $tmpTs) * 1000) . 'ms');
        }

        $this->output($output, $isProfilingEnabled);

        return $output;
    }

    /**
     * @param string $output
     * @param bool $isProfilingEnabled
     */
    public function output(string $output, bool $isProfilingEnabled = false): void
    {
        $tmpTs = microtime(true);

        header('Content-type: ' . $this->detectContentType());
        echo $output;

        if ($isProfilingEnabled) {
            $this->getSupportLogger()->trace('send: ' . ((microtime(true) - $tmpTs) * 1000) . 'ms');
        }
    }

    /**
     * @return string
     */
    protected function detectContentType(): string
    {
        return $this->fetchOptional(self::OP_BINARY_PROTOCOL_ENABLED)
            ? 'application/x-protobuf'
            : 'application/json';
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_BINARY_PROTOCOL_ENABLED] = $optionals[self::OP_BINARY_PROTOCOL_ENABLED]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->app->protobuf->binaryProtocol');
            };
        $optionals[self::OP_IS_PROFILING] = $optionals[self::OP_IS_PROFILING]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->debug->web->profiling->enabled');
            };
        $this->setOptionals($optionals);
    }
}
