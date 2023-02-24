<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Sms\Internal;

use Sam\Rtb\Server\SocketBase\Event\EventSocketClient;

/**
 * Class SocketClient
 * @package Sam\Rtb\Server\Sms\Internal
 * @internal
 */
class SmsEventClient extends EventSocketClient
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
        log_debug('');
    }

    public function onDisconnect(): void
    {
        log_trace('');
    }

    public function onRead(): void
    {
        log_info($this->readBuffer);
    }

    public function onWrite(): void
    {
        log_trace('');
    }

    public function onTimer(): void
    {
        log_trace('');
    }

    /**
     * @return array
     */
    public function logData(): array
    {
        return parent::logData() + ['client' => 'sms'];
    }
}
