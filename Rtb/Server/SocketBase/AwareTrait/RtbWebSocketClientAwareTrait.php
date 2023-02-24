<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/4/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\SocketBase\AwareTrait;

use RuntimeException;
use Sam\Rtb\GeneralHelper;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;

/**
 * Trait RtbWebSocketClientAwareTrait
 */
trait RtbWebSocketClientAwareTrait
{
    protected LegacyClient|EventClient|null $rtbWebSocketClient = null;

    /**
     * @return LegacyClient|EventClient
     */
    protected function getRtbWebSocketClient(): LegacyClient|EventClient
    {
        if (!GeneralHelper::new()->checkSocketClient($this->rtbWebSocketClient)) {
            throw new RuntimeException('RTB Web socket client not defined');
        }
        return $this->rtbWebSocketClient;
    }

    /**
     * @param LegacyClient|EventClient $rtbWebSocketClient
     * @return static
     */
    public function setRtbWebSocketClient(LegacyClient|EventClient $rtbWebSocketClient): static
    {
        $this->rtbWebSocketClient = $rtbWebSocketClient;
        return $this;
    }
}
