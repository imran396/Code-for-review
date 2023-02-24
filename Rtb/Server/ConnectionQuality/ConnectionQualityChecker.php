<?php
/**
 * SAM-5739: RTB ping
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\ConnectionQuality;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\Server\ServerResponseSenderCreateTrait;
use Sam\Core\Constants;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Event\EventSocketClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacySocketClient;

/**
 * Class ConnectionQualityChecker
 * @package Sam\Rtb\Server\ConnectionQuality
 */
class ConnectionQualityChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ServerResponseSenderCreateTrait;

    protected array $scheduledClientsChecks = [];
    protected int $variance;
    protected int $interval;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initInstance(): static
    {
        $this->variance = (int)$this->cfg()->get('core->rtb->ping->variance');
        $this->interval = (int)$this->cfg()->get('core->rtb->ping->interval');
        return $this;
    }

    /**
     * @param LegacySocketClient[]|EventSocketClient[] $clients
     */
    public function process(array $clients): void
    {
        array_walk($clients, [$this, 'processClient']);
    }

    /**
     * @param LegacySocketClient|EventSocketClient $client
     */
    protected function processClient(LegacySocketClient|EventSocketClient $client): void
    {
        if (!$this->interval) {
            // Ping disabled when interval = 0
            return;
        }

        if (
            !is_a($client, EventClient::class)
            && !is_a($client, LegacyClient::class)
        ) {
            // Skip connections of statistics sending, sms request
            return;
        }

        $clientHash = spl_object_hash($client);
        if (!isset($this->scheduledClientsChecks[$clientHash])) {
            $this->scheduleNextCheck($clientHash);
            return;
        }
        if ($this->scheduledClientsChecks[$clientHash] <= time()) {
            $this->sendPing($client);
            $this->scheduleNextCheck($clientHash);
        }
    }

    /**
     * @param LegacyClient $client
     */
    protected function sendPing(LegacyClient $client): void
    {
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_REVERSE_PING_S,
            Constants\Rtb::RES_DATA => [
                Constants\Rtb::RES_REVERSE_PING_TS => microtime(true),
            ],
        ];
        $responseJson = json_encode($response);
        $this->createServerResponseSender()->handleResponse($client, $responseJson);
    }

    /**
     * @param string $clientHash
     */
    protected function scheduleNextCheck(string $clientHash): void
    {
        $this->scheduledClientsChecks[$clientHash] = time() + $this->makeVariableInterval();
    }

    /**
     * @return int
     */
    protected function makeVariableInterval(): int
    {
        return $this->interval - ($this->variance / 2) + random_int(0, $this->variance);
    }
}
