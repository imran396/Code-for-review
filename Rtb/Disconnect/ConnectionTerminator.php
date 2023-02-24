<?php
/**
 * SAM-5013: Implement Rtbd disconnection response to caller scope
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           04.09.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Rtb\Disconnect;

use Sam\Core\Service\CustomizableClass;
use ReflectionClass;
use ReflectionException;
use Sam\Rtb\Command\Controller\AdminHybridController;
use Sam\Rtb\Command\Controller\AdminLiveController;
use Sam\Rtb\Command\Controller\AuctioneerController;
use Sam\Rtb\Command\Controller\BidderHybridController;
use Sam\Rtb\Command\Controller\BidderLiveController;
use Sam\Rtb\Command\Controller\ControllerBase;
use Sam\Rtb\Command\Controller\ViewerHybridController;
use Sam\Rtb\Command\Controller\ViewerLiveController;
use Sam\Core\Constants;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Server\SocketBase\AwareTrait\RtbWebSocketClientAwareTrait;
use Sam\Rtb\Server\ServerResponseSenderCreateTrait;
use Laminas\Json\Json;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;

/**
 * Class ConnectionTerminator
 * @package Sam\Rtb\Disconnect
 */
class ConnectionTerminator extends CustomizableClass
{
    use RtbWebSocketClientAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use ServerResponseSenderCreateTrait;
    use UserAwareTrait;

    /** @var EventClient[]|LegacyClient[] */
    public array $clientSockets = [];
    public string $ip = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return ConnectionTerminator
     */
    public function setIp(string $ip): ConnectionTerminator
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return EventClient[]|LegacyClient[]
     */
    public function getClientSockets(): array
    {
        return $this->clientSockets;
    }

    /**
     * @param EventClient[]|LegacyClient[] $clientSockets
     *
     * @return ConnectionTerminator
     */
    public function setClientSockets(array $clientSockets): ConnectionTerminator
    {
        $this->clientSockets = $clientSockets;
        return $this;
    }

    /**
     * Sends terminate connection request to all connected consoles of the user specified.
     */
    public function terminate(): void
    {
        $responseSender = $this->createServerResponseSender();
        $data = [];
        $userId = $this->getUserId();
        foreach ($this->getClientSockets() as $client) {
            // ignore Stats_SocketClient etc
            if (!$this->getRtbGeneralHelper()->checkSocketClient($client)) {
                continue;
            }

            // If we will need to disconnect user for any ip
            // $isFound = $socket->user_command instanceof ControllerBase
            //     && $socket->user_command->userId == $userId
            //     && (($strIpAddress
            //             && $socket->remoteAddress == $strIpAddress)
            //         || !$strIpAddress
            //     );
            $rtbCommandController = $client->getRtbCommandController();
            $isFound = $rtbCommandController instanceof ControllerBase
                && $rtbCommandController->getEditorUserId() === $userId
                && $client->remoteHost === $this->getIp();
            if ($isFound) {
                if ($this->allowedToBlockUser($rtbCommandController)) {
                    $data['AuctionIds'][] = $rtbCommandController->getAuctionId();
                    $response = [
                        Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_TERMINATE_CONNECTION_S
                    ];
                    $responseJson = Json::encode($response);
                    $responseSender->handleResponse($client, $responseJson);
                }
                log_info(
                    'TERMINATE CONNECTION for block user ' . composeSuffix(['u' => $userId])
                    . ">> {$client->remoteHost}:{$client->remotePort};{$client->connectionType}:{};"
                );
                $client->close();
            }
        }

        // Send auctions ids the user was disconnected from:
        $responseToSender = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_TERMINATE_CONNECTION_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseToSenderJson = Json::encode($responseToSender);
        $responseSender->handleResponse($this->getRtbWebSocketClient(), $responseToSenderJson);
    }

    /**
     * Check if connected user console allowed to disconnect
     * @param ControllerBase $userCommand
     * @return bool
     */
    protected function allowedToBlockUser(ControllerBase $userCommand): bool
    {
        $allowedClasses = [
            BidderLiveController::class,
            BidderHybridController::class,
            ViewerLiveController::class,
            ViewerHybridController::class,
            AdminLiveController::class,
            AdminHybridController::class,
            AuctioneerController::class,
        ];
        $className = get_class($userCommand);
        $isAllowed = false;
        try {
            if (in_array($className, $allowedClasses, true)) {
                $isAllowed = true;
            } else {
                $reflection = new ReflectionClass($className);
                foreach ($allowedClasses as $class) {
                    if ($reflection->isSubclassOf($class)) {
                        $isAllowed = true;
                        break;
                    }
                }
            }
        } catch (ReflectionException $e) {
            log_error($e->getMessage());
        }
        return $isAllowed;
    }
}
