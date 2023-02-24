<?php
/**
 * SAM-9728: Move \Stats_Server to Sam namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Statistic\Internal;

use Sam\Rtb\Server\SocketBase\Event\EventSocketClient;

/**
 * Class StatisticSocketClient
 * @internal
 */
class StatisticEventClient extends EventSocketClient
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function onConnect(): void
    {
        log_trace('connected, now writing');
    }

    public function onDisconnect(): void
    {
        log_trace();
    }

    public function onRead(): void
    {
        log_trace($this->readBuffer);
    }

    public function onWrite(): void
    {
        log_trace();
    }

    public function onTimer(): void
    {
        log_trace();
    }

    /**
     * @return array
     */
    public function logData(): array
    {
        return parent::logData() + ['client' => 'stats'];
    }
}
