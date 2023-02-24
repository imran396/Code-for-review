<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           11/6/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Auth;

use Sam\Core\Service\CustomizableClass;
use EpiCurlManager;
use Exception;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;

/**
 * Class AuthQueueManager
 * @package
 */
class AuthQueueManager extends CustomizableClass
{
    public array $authQueue = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param LegacyClient|EventClient $client
     * @param EpiCurlManager|int $curl
     * @return static
     */
    public function add(int $userId, LegacyClient|EventClient $client, EpiCurlManager|int $curl): static
    {
        $ch = 'usr' . $userId;
        $authKey = md5($ch);
        $this->authQueue[$authKey] = [$client, $curl];
        return $this;
    }

    public function process(): void
    {
        foreach ($this->authQueue as $key => $mixValue) {
            try {
                /** @var LegacyClient|EventClient $client */
                $client = $mixValue[0] ?? null;
                $curl = $mixValue[1] ?? null;
                $authValue = $curl ? (int)$curl->data : null;
                if (in_array($authValue, [0, 1], true)) {
                    $client->getAuthenticator()->authenticate((bool)$authValue);
                    unset($this->authQueue[$key]);
                }
            } catch (Exception $e) {
                log_error("Caught exception on rtb authorization: " . $e->getMessage());
            }
        }
    }
}
