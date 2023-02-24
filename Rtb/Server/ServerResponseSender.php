<?php
/**
 * This is temporary class, while we are refactoring httpServer.php and RtbDaemon
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server;

use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Command\Controller\AdminHybridController;
use Sam\Rtb\Command\Controller\AdminLiveController;
use Sam\Rtb\Command\Controller\AuctioneerController;
use Sam\Rtb\Command\Controller\BidderHybridController;
use Sam\Rtb\Command\Controller\BidderLiveController;
use Sam\Rtb\Command\Controller\ControllerBase;
use Sam\Rtb\Command\Controller\ProjectorController;
use Sam\Rtb\Command\Controller\ViewerHybridController;
use Sam\Rtb\Command\Controller\ViewerLiveController;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;

/**
 * Class ServerHelper
 * @package
 */
class ServerResponseSender extends CustomizableClass
{
    use RtbGeneralHelperAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LegacyClient|EventClient $client
     * @param string $response
     */
    public function handleResponse($client, string $response): void
    {
        log_info(
            "RESPONSE >> "
            . composeLogData(
                [
                    $client->remoteHost => $client->remotePort,
                    $client->connectionType => $response
                ]
            )
        );
        if ($client->connectionType === 'WEBSOCKET') {
            if (
                (int)$client->webSocketVersion === 8
                || (int)$client->webSocketVersion === 13
            ) {
                $response = $this->hybi10Encode($response, 'text', false);
                if ($response === false) {
                    $client->close();
                }
            } else {
                $response = str_replace("\'", "'", $response);
                $response = chr(0) . $response . chr(255);
            }
        } elseif ($client->connectionType === 'PHPSCRIPT') {
            $response = str_replace("'", "\'", $response);
            $response .= chr(0);
        }
        $client->write($response);
    }

    /**
     * @param string $payload
     * @param string $type
     * @param bool $isMasked
     * @return string|false
     */
    public function hybi10Encode(string $payload, string $type = 'text', bool $isMasked = true): string|false
    {
        $frameHead = [];
        $payloadLength = strlen($payload);

        switch ($type) {
            case 'text':
                // first byte indicates FIN, Text-Frame (10000001):
                $frameHead[0] = 129;
                break;

            case 'close':
                // first byte indicates FIN, Close Frame(10001000):
                $frameHead[0] = 136;
                break;

            case 'ping':
                // first byte indicates FIN, Ping frame (10001001):
                $frameHead[0] = 137;
                break;

            case 'pong':
                // first byte indicates FIN, Pong frame (10001010):
                $frameHead[0] = 138;
                break;
        }

        // set mask and payload length (using 1, 3 or 9 bytes)
        if ($payloadLength > 65535) {
            $payloadLengthBin = str_split(sprintf('%064b', $payloadLength), 8);
            $frameHead[1] = $isMasked ? 255 : 127;
            for ($i = 0; $i < 8; $i++) {
                $frameHead[$i + 2] = bindec($payloadLengthBin[$i]);
            }
            // most significant bit MUST be 0 (close connection if frame too big)
            if ($frameHead[2] > 127) {
                return false;
            }
        } elseif ($payloadLength > 125) {
            $payloadLengthBin = str_split(sprintf('%016b', $payloadLength), 8);
            $frameHead[1] = $isMasked ? 254 : 126;
            $frameHead[2] = bindec($payloadLengthBin[0]);
            $frameHead[3] = bindec($payloadLengthBin[1]);
        } else {
            $frameHead[1] = $isMasked ? $payloadLength + 128 : $payloadLength;
        }

        // convert frame-head to string:
        foreach (array_keys($frameHead) as $i) {
            $frameHead[$i] = chr($frameHead[$i]);
        }
        $mask = [];
        if ($isMasked) {
            // generate a random mask:
            for ($i = 0; $i < 4; $i++) {
                $mask[$i] = chr(random_int(0, 255));
            }
            $frameHead = array_merge($frameHead, $mask);
        }
        $frame = implode('', $frameHead);

        // append payload to frame:
        for ($i = 0; $i < $payloadLength; $i++) {
            $frame .= $isMasked ? $payload[$i] ^ $mask[$i % 4] : $payload[$i];
        }

        return $frame;
    }

    /**
     * @param LegacyClient|EventClient $client
     * @param string $message
     * @param string $requestJson
     */
    public function handleInvalid(LegacyClient|EventClient $client, string $message, string $requestJson): void
    {
        log_warning("!!{$message} FROM << {$client->remoteHost}:{$client->remotePort};{$requestJson}");
        $client->close();
    }

    /**
     * Log profiling information from array of timestamps
     * @param array $ts associative array with names of events and microtime(true) timestamp as value
     * @param string $note optional note
     */
    public function logTs(array $ts, string $note = ''): void
    {
        if ($note) {
            $ts[$note] = microtime(true);
        }

        $benchmark = '';
        $startTs = false;
        $value = null;

        foreach ($ts as $key => $value) {
            if (!$startTs) {
                $startTs = $value;
            }
            if (isset($prevTs)) {
                $benchmark .= ($benchmark ? '; ' : '') . $key . ': ' . round(($value - $prevTs) * 1000, 2) . 'ms';
            }
            $prevTs = $value;
        }

        if ($value) {
            $benchmark .= ($benchmark ? '; ' : '') . 'total time: ' . round(($value - $startTs) * 1000, 2) . 'ms';
        }
        log_info(composeLogData(['Benchmark' => $benchmark]));
    }

    /**
     * @param LegacyClient|EventClient $client
     * @param array $responses
     * @param array $ts
     * @param int $auctionId
     */
    public function handleCommandResponse(
        LegacyClient|EventClient $client,
        array $responses,
        array $ts,
        int $auctionId
    ): void {
        $clients = $client->getRtbDaemon()->clientSockets;

        if (
            isset($responses[Constants\Rtb::RT_SINGLE])
            && trim($responses[Constants\Rtb::RT_SINGLE]) !== ''
        ) {
            $this->handleResponse($client, (string)$responses[Constants\Rtb::RT_SINGLE]);
            $ts['single resp'] = microtime(true);
        }

        if (
            isset($responses[Constants\Rtb::RT_CLERK])
            && trim($responses[Constants\Rtb::RT_CLERK]) !== ''
        ) {
            $this->broadcast(
                $clients,
                (string)$responses[Constants\Rtb::RT_CLERK],
                Constants\Rtb::UT_CLERK,
                $auctionId
            );
            $ts['admin resp'] = microtime(true);
        }

        if (
            isset($responses[Constants\Rtb::RT_AUCTIONEER])
            && trim($responses[Constants\Rtb::RT_AUCTIONEER]) !== ''
        ) {
            $this->broadcast(
                $clients,
                (string)$responses[Constants\Rtb::RT_AUCTIONEER],
                Constants\Rtb::UT_AUCTIONEER,
                $auctionId
            );
            $ts['auctioneer resp'] = microtime(true);
        }

        if (
            isset($responses[Constants\Rtb::RT_PROJECTOR])
            && trim($responses[Constants\Rtb::RT_PROJECTOR]) !== ''
        ) {
            $this->broadcast(
                $clients,
                (string)$responses[Constants\Rtb::RT_PROJECTOR],
                Constants\Rtb::UT_PROJECTOR,
                $auctionId
            );
            $ts['projector resp'] = microtime(true);
        }

        if (
            isset($responses[Constants\Rtb::RT_BIDDER])
            && trim($responses[Constants\Rtb::RT_BIDDER]) !== ''
        ) {
            $this->broadcast(
                $clients,
                (string)$responses[Constants\Rtb::RT_BIDDER],
                Constants\Rtb::UT_BIDDER,
                $auctionId
            );
            $ts['bidder resp'] = microtime(true);
        }

        if (
            isset($responses[Constants\Rtb::RT_SIMULT_BIDDER])
            && is_array($responses[Constants\Rtb::RT_SIMULT_BIDDER])
        ) {
            $this->broadcast(
                $clients,
                (string)$responses[Constants\Rtb::RT_SIMULT_BIDDER][Constants\Rtb::RES_SA_MESSAGE],
                Constants\Rtb::UT_BIDDER,
                Cast::toInt($responses[Constants\Rtb::RT_SIMULT_BIDDER][Constants\Rtb::RES_SA_AUCTION_ID])
            );
            $ts['sbidder resp'] = microtime(true);
        }

        if (
            isset($responses[Constants\Rtb::RT_VIEWER])
            && trim($responses[Constants\Rtb::RT_VIEWER]) !== ''
        ) {
            $this->broadcast(
                $clients,
                (string)$responses[Constants\Rtb::RT_VIEWER],
                Constants\Rtb::UT_VIEWER,
                $auctionId
            );
            $ts['viewer resp'] = microtime(true);
        }

        if (
            isset($responses[Constants\Rtb::RT_SIMULT_VIEWER])
            && is_array($responses[Constants\Rtb::RT_SIMULT_VIEWER])
        ) {
            $this->broadcast(
                $clients,
                (string)$responses[Constants\Rtb::RT_SIMULT_VIEWER][Constants\Rtb::RES_SA_MESSAGE],
                Constants\Rtb::UT_VIEWER,
                Cast::toInt($responses[Constants\Rtb::RT_SIMULT_VIEWER][Constants\Rtb::RES_SA_AUCTION_ID])
            );
            $ts['sviewer resp'] = microtime(true);
        }

        if (
            isset($responses[Constants\Rtb::RT_INDIVIDUAL])
            && count($responses[Constants\Rtb::RT_INDIVIDUAL]) > 1
        ) {
            $this->broadcast(
                $clients,
                (string)$responses[Constants\Rtb::RT_INDIVIDUAL][1],
                null,
                $auctionId,
                Cast::toInt($responses[Constants\Rtb::RT_INDIVIDUAL][0])
            );
            $ts['individual resp'] = microtime(true);
        }

        if (
            isset($responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL])
            && count($responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL]) > 1
        ) {
            $this->broadcast(
                $clients,
                (string)$responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL][Constants\Rtb::RES_SA_MESSAGE],
                null,
                Cast::toInt($responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL][Constants\Rtb::RES_SA_AUCTION_ID]),
                Cast::toInt($responses[Constants\Rtb::RT_SIMULT_INDIVIDUAL][Constants\Rtb::RES_SA_USER_ID])
            );
            $ts['sindividual resp'] = microtime(true);
        }

        if (isset($responses[Constants\Rtb::RT_SMS])) {
            $client->sendTextMessage($responses[Constants\Rtb::RT_SMS]);
            $ts['sms resp'] = microtime(true);
        }
        $this->logTs($ts);
    }


    /**
     * @param LegacyClient[]|EventClient[] $clients
     * @param string $response
     * @param int|null $userType
     * @param int|null $auctionId
     * @param int|null $userId
     */
    public function broadcast(array $clients, string $response, ?int $userType = null, ?int $auctionId = null, ?int $userId = null): void
    {
        $userType = Cast::toInt($userType, Constants\Rtb::$rtbConsoleUserTypes);
        $auctionId = Cast::toInt($auctionId, Constants\Type::F_INT_POSITIVE);
        $userId = Cast::toInt($userId, Constants\Type::F_INT_POSITIVE);

        foreach ($clients as $client) {
            // ignore Stats_SocketClient etc
            if (!$this->getRtbGeneralHelper()->checkSocketClient($client)) {
                continue;
            }

            $rtbCommandController = $client->getRtbCommandController();
            // Send to all client connected
            if (
                $userType === null
                && $auctionId === null
            ) {
                $this->handleResponse($client, $response);
            } elseif (
                $userType === Constants\Rtb::UT_CLERK
                && (
                    $rtbCommandController instanceof AdminLiveController
                    || $rtbCommandController instanceof AdminHybridController
                )
                && $rtbCommandController->getAuctionId() === $auctionId
            ) {
                $this->handleResponse($client, $response);
            } elseif (
                $userType === Constants\Rtb::UT_PROJECTOR
                && $rtbCommandController instanceof ProjectorController
                && $rtbCommandController->getAuctionId() === $auctionId
            ) {
                $this->handleResponse($client, $response);
            } elseif (
                $userType === Constants\Rtb::UT_BIDDER
                && (
                    $rtbCommandController instanceof BidderLiveController
                    || $rtbCommandController instanceof BidderHybridController
                )
                && $rtbCommandController->getAuctionId() === $auctionId
            ) {
                $this->handleResponse($client, $response);
            } elseif (
                $userType === Constants\Rtb::UT_VIEWER
                && (
                    $rtbCommandController instanceof ViewerLiveController
                    || $rtbCommandController instanceof ViewerHybridController
                )
                && $rtbCommandController->getAuctionId() === $auctionId
            ) {
                $this->handleResponse($client, $response);
            } elseif (
                $userType === Constants\Rtb::UT_AUCTIONEER
                && $rtbCommandController instanceof AuctioneerController
                && $rtbCommandController->getAuctionId() === $auctionId
            ) {
                $this->handleResponse($client, $response);
            } elseif (
                $rtbCommandController instanceof ControllerBase
                && $rtbCommandController->getAuctionId() === $auctionId
                && $userId > 0
                && $rtbCommandController->getEditorUserId() === $userId
            ) {
                $this->handleResponse($client, $response);
            }
        }
    }
}
