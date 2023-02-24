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

use Sam\Rtb\Server\SocketBase\Legacy\LegacySocketClient;

/**
 * Class SocketClient
 * @package Sam\Rtb\Server\Sms\Internal
 * @internal
 */
class SmsLegacyClient extends LegacySocketClient
{
    /**
     * @return bool
     */
    public function doWrite(): bool
    {
        if ($this->connecting) {
            log_trace("still connecting");
            return false;
        }
        if ($this->disconnected) {
            log_trace("disconnected");
            return false;
        }

        log_debug('writing ' . $this->writeBuffer);
        $success = parent::doWrite();
        log_debug(($success ? 'success' : 'failed'));
        return $success;
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
