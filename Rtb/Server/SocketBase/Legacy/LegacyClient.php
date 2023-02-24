<?php
/*
phpSocketDaemon 1.0 - httpd server demo implementation
Copyright (C) 2006 Chris Chabot <chabotc@xs4all.nl>
See http://www.chabotc.nl/ for more information

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace Sam\Rtb\Server\SocketBase\Legacy;

use Laminas\Json\Json;
use Rtb_HttpCommand;
use RuntimeException;
use Sam\Application\RequestParam\ParamFetcherForRtbd;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\BidderInterest\BidderInterestConsoleDisconnecterCreateTrait;
use Sam\Rtb\Command\Controller\ControllerBase;
use Sam\Rtb\Disconnect\ConnectionTerminatorAwareTrait;
use Sam\Rtb\Server\Auth\AuthenticatorAwareTrait;
use Sam\Rtb\Server\Daemon\RtbDaemonLegacy;
use Sam\Rtb\Server\Daemon\SocketHelper;
use Sam\Rtb\Server\ServerResponseSenderCreateTrait;
use Sam\Rtb\Server\Sms\SmsGatewayClientCreateTrait;
use Sam\Rtb\Server\SocketBase\Common\Origin\OriginChecker;
use Sam\Settings\SettingsManagerAwareTrait;
use stdClass;

class LegacyClient extends LegacySocketServerClient
{
    use AuctionLoaderAwareTrait;
    use AuthenticatorAwareTrait;
    use BidderInterestConsoleDisconnecterCreateTrait;
    use BidderNumPaddingAwareTrait;
    use ConfigRepositoryAwareTrait;
    use ConnectionTerminatorAwareTrait;
    use ServerResponseSenderCreateTrait;
    use SettingsManagerAwareTrait;
    use SmsGatewayClientCreateTrait;

    /** @var int|null */
    protected $maxTotalTime;
    /** @var int|null */
    protected $maxIdleTime;
    /** @var bool */
    protected $keepAlive = false;
    /** @var int|null */
    protected $acceptedTime;
    /** @var int|null */
    protected $lastActionTime;
    /** @var string */
    public $connectionType = 'WEBSOCKET';
    /** @var ControllerBase|null */
    protected $rtbCommandController;
    /** @var int|null */
    public $lastNoopTime;
    /** @var int|null */
    public $webSocketVersion;
    /** @var string|null */
    public $hixie76headers;
    /** @var string|null */
    public $tempBuffer;

    /**
     * httpdServerClient constructor.
     * @param $socketResource
     * @param RtbDaemonLegacy $daemon
     * @throws LegacySocketException
     */
    public function __construct($socketResource, $daemon)
    {
        $this->setRtbDaemon($daemon);
        $this->maxTotalTime = (int)cfg()->core->rtb->server->maxTotalTime;
        $this->maxIdleTime = (int)cfg()->core->rtb->server->maxIdleTime;
        $this->keepAlive = (bool)cfg()->core->rtb->server->keepAlive;

        parent::__construct($socketResource);
    }

    /**
     * @param string|null $requestJson
     */
    public function handleRequest($requestJson): void
    {
        $ts = ['start' => microtime(true)];

        log_debug("REQUEST << {$this->remoteHost}:{$this->remotePort};{$requestJson};");

        $request = null;
        $requestJson = trim((string)$requestJson);
        $responseSender = $this->createServerResponseSender();

        try {
            $request = Json::decode($requestJson);
            $ts['decode'] = microtime(true);
        } catch (RuntimeException $e) {
            log_warning($e->getMessage() . composeSuffix(['Request' => $requestJson]));
        }

        if (!is_object($request)) {
            $responseSender->logTs($ts, 'invalid cmd');
            $responseSender->handleInvalid($this, 'Invalid Command Format', $requestJson);
            return;
        }

        if (empty($request->{Constants\Rtb::REQ_COMMAND})) {
            $responseSender->logTs($ts, 'no cmd');
            $responseSender->handleInvalid($this, 'Command Not Defined', $requestJson);
            return;
        }

        $cmd = $request->{Constants\Rtb::REQ_COMMAND};

        if ($cmd === Constants\Rtb::CMD_NOOP) {
            $ts[$cmd] = microtime(true);
            $responseSender->logTs($ts);
            return;
        }
        $this->lastNoopTime = $this->lastActionTime; // reset noop timer

        $data = property_exists($request, Constants\Rtb::REQ_DATA)
            ? $request->{Constants\Rtb::REQ_DATA} : new stdClass();
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);

        if ($cmd === Constants\Rtb::CMD_AUTH_Q) {
            $authenticator = $this->getAuthenticator()
                ->construct($this, $this->getRtbDaemon(), $paramFetcher, $requestJson, $ts);
            $authenticator->auth();
            $this->setRtbCommandController($authenticator->getRtbCommandController());
            $responseSender->logTs($ts);
            return;
        }

        if ($cmd === Constants\Rtb::CMD_TERMINATE_CONNECTION_Q) {
            $this->getConnectionTerminator()
                ->setClientSockets($this->getRtbDaemon()->clientSockets)
                ->setRtbWebSocketClient($this)
                ->setUserId($paramFetcher->getIntPositive(Constants\Rtb::REQ_USER_ID))
                ->setIp($paramFetcher->getString(Constants\Rtb::REQ_IP))
                ->terminate();
            $ts[$cmd] = microtime(true);
            $responseSender->logTs($ts);
            return;
        }

        $rtbCommandController = $this->getRtbCommandController();
        if ($rtbCommandController) {
            $resp = $rtbCommandController->response($cmd, $data);
            $ts[$cmd] = microtime(true);
            $responseSender->handleCommandResponse($this, $resp, $ts, $rtbCommandController->getAuctionId());
            return;
        }

        $responseSender->logTs($ts, 'undefined command');
        $responseSender->handleInvalid($this, 'Undefined command object terminate connection ', $requestJson);
    }

    /**
     * @param string $responseJson
     */
    public function sendTextMessage(string $responseJson): void
    {
        $rtbCommandController = $this->getRtbCommandController();
        if (!$rtbCommandController) {
            log_error('RtbCommandController not found', 'action_queue.log');
            return;
        }

        $accountId = $this->getAuctionLoader()
            ->load($rtbCommandController->getAuctionId())
            ->AccountId;

        $this->createSmsGatewayClient()->sendMessage($responseJson, $accountId, $this->getRtbDaemon());
    }

    /**
     * @param string $data
     * @return array|bool
     */
    protected function hybi10FrameType($data)
    {
        if (!isset($data[0]) || !isset($data[1])) {
            return false;
        }

        // estimate frame type:
        $firstByteBinary = sprintf('%08b', ord($data[0]));
        $secondByteBinary = sprintf('%08b', ord($data[1]));
        $opcode = bindec(substr($firstByteBinary, 4, 4));

        $fMasked = (int)$secondByteBinary[0];
        $isMasked = $fMasked === 1;
        $payloadLength = $isMasked ? ord($data[1]) & 127 : ord($data[1]);

        if ($payloadLength === 126) {
            $mask = substr($data, 4, 4);
            $payloadOffset = 8;
        } elseif ($payloadLength === 127) {
            $mask = substr($data, 10, 4);
            $payloadOffset = 14;
        } else {
            $mask = substr($data, 2, 4);
            $payloadOffset = 6;
        }

        return [
            'opcode' => $opcode,
            'isMasked' => $isMasked,
            'mask' => $mask,
            'payloadOffset' => $payloadOffset,
        ];
    }

    /**
     * @param string $data
     * @return string
     */
    protected function hybi10Parser($data): string
    {
        $dataLength = strlen($data);
        if ($dataLength > 0) {
            $unmaskedPayload = '';
            $frameTypeRow = $this->hybi10FrameType($data);
            if ($frameTypeRow === false) {
                log_warning("!!Failed to get the hybi10 frame type: ");
                $this->close();
                return '';
            }
            $opcode = $frameTypeRow['opcode'];
            $isMasked = $frameTypeRow['isMasked'];
            $mask = $frameTypeRow['mask'];
            $payloadOffset = $frameTypeRow['payloadOffset'];

            // close connection if unmasked frame is received:
            if ($isMasked === false) {
                log_warning("!!Unmasked data close connection");
                $this->close();
                return '';
            }

            switch ($opcode) {
                case 1: //text frame:
                    //$decodedData['type'] = 'text';
                    break;
                case 8: //connection close frame:
                    //$decodedData['type'] = 'close';
                    break;
                case 9: //ping frame:
                    //$decodedData['type'] = 'ping';
                    break;
                case 10: //pong frame:
                    //$decodedData['type'] = 'pong';
                    break;
                default: //unknown opcode: close connection
                    log_warning("!!Unknown data (opcode)");
                    $this->close();
                    return '';
            }

            if ($isMasked) {
                $old = '';
                $str = '';

                for ($i = $payloadOffset; $i < $dataLength; $i++) {
                    $j = $i - $payloadOffset;
                    $str = (string)($data[$i] ^ $mask[$j % 4]);
                    if ($old !== ''
                        && ord($old) === 10
                        && ord($str) === 10
                    ) {
                        $ndata = substr($data, $i + 1);
                        if ($ndata !== '') { // Check if there still a command request in the binary data that needs to be decoded
                            $data = $ndata;
                            //error_log('PENDING DECODING: ' . strlen($data));
                            $payLoad = $this->hybi10Parser($data);
                            if ($payLoad !== '') {
                                $unmaskedPayload .= "\n" . $payLoad;
                            } // Make sure not to add an empty payload;
                        }
                        // else {
                        //     //error_log('FINISHED DECODING');
                        // }
                        break;
                    }

                    $old = $str;
                    $unmaskedPayload .= $str;
                }

                if (ord($str) === 10) { // Only add complete package request 10 means terminated data
                    return $unmaskedPayload;
                }

                //error_log('HAS A PENDING BUFFER');
                $this->tempBuffer = $data;
                return '';
            }

            //error_log('MASKED = false');
            $payloadOffset -= 4;
            $unmaskedPayload = substr($data, $payloadOffset);
            return $unmaskedPayload;
        }
        return '';
    }

    /**
     * @param string $data
     * @return array
     */
    protected function hybi10Decode($data): array
    {
        if ((string)$this->tempBuffer !== '') {
            $data = $this->tempBuffer . $data;
            $this->tempBuffer = null; // Reset temp_buffer
        }

        $decodedData = [];

        $payLoad = $this->hybi10Parser($data);
        if ($payLoad !== '') {
            $decodedData['payload'] = $payLoad;
        }

        //error_log('RETURN: ' /*. print_r($decodedData, true)*/);
        return $decodedData;
    }

    public function onRead(): void
    {
        if ($this->connectionType !== 'WEBSOCKET'
            /* && $this->webSocketVersion != 8 && (int)$this->webSocketVersion !== 13*/
        ) {
            $this->readBuffer = str_replace(chr(255), '', $this->readBuffer); // remove chr 255 character
            $this->readBuffer = str_replace(chr(0), '', $this->readBuffer); // remove chr 0 character
        }

        $this->lastActionTime = time();
        $buffer = $this->readBuffer;
        if (preg_match('/GET (.*?) HTTP/', $buffer, $matches)) {
            $requestUri = $matches[1];
            if (preg_match('/upgrade: websocket/i', $buffer, $matches)) {    // websocket request
                //echo "do handshake \n";
                $contents = $this->doHandshake($buffer);
                if ($contents) {
                    $this->write($contents);
                    $this->connectionType = 'WEBSOCKET';
                }
            } else { // http request
                [, $reqHeaders,] = $this->handleRequestHeader($buffer);
                //This code block is used to send output for http request.
                //For valid origin we append origin header to the response for preventing ajax http request error.
                //For invalid origin we don't append origin header due to CORS security issue.
                $origin = $reqHeaders[Constants\HttpHeaders::ORIGIN] ?? '';
                $queryPart = parse_url($requestUri, PHP_URL_QUERY);
                parse_str($queryPart, $parts);
                $accountId = Cast::toInt($parts['accountId'] ?? null);
                $isValid = OriginChecker::new()->isValidOrigin($origin, $accountId);
                $origin = $isValid ? $origin : '';
                $requestUri = strtok($requestUri, '?');
                $response = Rtb_HttpCommand::new()->response($requestUri, $origin);
                $this->write($response);
            }
        } elseif ($this->connectionType === 'HIXIE76') {
            log_debug(composeSuffix(['Connection type' => $this->connectionType]));
            $buffer = $this->hixie76headers . "\r\n\r\n" . $buffer;
            $contents = $this->doHandshake($buffer);
            if ($contents) {
                $this->write($contents);
                $this->connectionType = 'WEBSOCKET';
                $this->hixie76headers = '';// Reset
            }
        } else {
            if ($this->connectionType === 'WEBSOCKET'
                && ((int)$this->webSocketVersion === 8
                    || (int)$this->webSocketVersion === 13)
            ) {
                $length = strlen($this->readBuffer);
                // we need at least 3 bytes to decode (see SAM-4929)
                if ($length < 3) {
                    return;
                }
//                // we expect a buffer with at least the expected data length or decoding will fail
//                if ($length < $this->readBuffer[1] & 127) {
//                    return;
//                }

                //log_debug("WebSocketVer:8|13; Decoding buffer");
                $decodedDuffer = $this->hybi10Decode($this->readBuffer);
                if (
                    $decodedDuffer
                    && isset($decodedDuffer['payload'])
                ) {
                    // log_debug("Decoded Result: " . $decodedDuffer['payload']);
                    $this->readBuffer = trim($decodedDuffer['payload']) . "\n\n";
                }
            }

            $maxCmdChain = 3;
            $y = strpos($this->readBuffer, "\n\n");
            $x = strpos($this->readBuffer, "\r\n\r\n");
            while ($y !== false || $x !== false) {
                $pos = $x !== false ? $x : $y;
                $offset = $x !== false ? 4 : 2;
                $buffer = substr($this->readBuffer, 0, $pos);
                $this->readBuffer = substr(
                    $this->readBuffer,
                    $pos + $offset,
                    strlen($this->readBuffer) - $pos - $offset
                );

                $this->handleRequest($buffer);
                $maxCmdChain--;
                if ($maxCmdChain <= 0) {
                    $this->readBuffer = '';
                    break;
                }
                $y = strpos($this->readBuffer, "\n\n");
                $x = strpos($this->readBuffer, "\r\n\r\n");
            }
        }
        $this->readBuffer = '';
    }

    /**
     * @param $buffer
     * @return string
     */
    public function doHandshake($buffer): string
    {
        // parse the HTTP headers
        [$requestUri, $headers, $securityCode] = $this->handleRequestHeader($buffer);

        // you could add logic here based on the resource requested
        //$this->log("client requested resource: $resource");

        if (isset($headers[Constants\HttpHeaders::X_REAL_IP])) {
            $ip = trim($headers[Constants\HttpHeaders::X_REAL_IP]);
            if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                log_info('Proxy X-Real-IP header switching from ' . $this->remoteHost . ' to ' . $ip);
                $this->remoteHost = $ip;
            }
        }
        if (isset($headers[Constants\HttpHeaders::X_REAL_PORT])) {
            $port = trim($headers[Constants\HttpHeaders::X_REAL_PORT]);
            if (filter_var($port, FILTER_VALIDATE_INT) !== false) {
                log_info('Proxy X-Real-Port header switching from ' . $this->remotePort . ' to ' . $port);
                $this->remotePort = (int)$port;
            }
        }

        // answer the security challenge correctly
        $securityResponse = '';
        if (isset($headers[Constants\HttpHeaders::SEC_WEBSOCKET_KEY1])
            && isset($headers[Constants\HttpHeaders::SEC_WEBSOCKET_KEY2])
        ) {
            $securityResponse = $this->getHandshakeSecurityKey(
                $headers[Constants\HttpHeaders::SEC_WEBSOCKET_KEY1],
                $headers[Constants\HttpHeaders::SEC_WEBSOCKET_KEY2],
                $securityCode
            );
        }

        $upgrade = '';

        $host = $headers[Constants\HttpHeaders::HOST] ?? '';
        $origin = $headers[Constants\HttpHeaders::ORIGIN] ?? '';

        if ($securityResponse) {
            /* WebSocket draft version 00-03
             * http://tools.ietf.org/html/draft-ietf-hybi-thewebsocketprotocol-00
             * ..............
             * http://tools.ietf.org/html/draft-ietf-hybi-thewebsocketprotocol-03
             */
            $this->webSocketVersion = 0;
            $upgrade = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
                "Upgrade: WebSocket\r\n" .
                "Connection: Upgrade\r\n" .
                "Sec-WebSocket-Origin: " . $origin . "\r\n" .
                "Sec-WebSocket-Location: ws://" . $host . $requestUri . "\r\n" .
                "\r\n" . $securityResponse;
        } elseif (isset($headers[Constants\HttpHeaders::SEC_WEBSOCKET_VERSION])) {
            $versionNum = (int)$headers[Constants\HttpHeaders::SEC_WEBSOCKET_VERSION];
            $this->webSocketVersion = $versionNum;
            if ($versionNum > 3 && $versionNum < 6) {
                /* WebSocket draft version 4-5
                 * http://tools.ietf.org/html/draft-ietf-hybi-thewebsocketprotocol-04
                 * http://tools.ietf.org/html/draft-ietf-hybi-thewebsocketprotocol-05
                 */
                $key = $headers[Constants\HttpHeaders::SEC_WEBSOCKET_KEY] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
                $secKey = base64_encode(sha1($key, true));

                /** @noinspection SpellCheckingInspection */
                $upgrade = "HTTP/1.1 101 Switching Protocols\r\n" .
                    "Upgrade: WebSocket\r\n" .
                    "Connection: Upgrade\r\n" .
                    "Sec-WebSocket-Accept: " . $secKey . "\r\n" .
                    "Sec-WebSocket-Nonce: AQIDBAUGBwgJCgsMDQ4PEC==\r\n" .
                    "\r\n";
            } elseif ($versionNum > 5 && $versionNum < 18) {
                /* WebSocket draft version 6-17
                 * http://tools.ietf.org/html/draft-ietf-hybi-thewebsocketprotocol-06
                 * http://tools.ietf.org/html/draft-ietf-hybi-thewebsocketprotocol-07
                 * ............
                 * http://tools.ietf.org/html/draft-ietf-hybi-thewebsocketprotocol-17
                 */

                $key = $headers[Constants\HttpHeaders::SEC_WEBSOCKET_KEY] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
                $secKey = base64_encode(sha1($key, true));
                $upgrade = "HTTP/1.1 101 Switching Protocols\r\n" .
                    "Upgrade: WebSocket\r\n" .
                    "Connection: Upgrade\r\n" .
                    "Sec-WebSocket-Accept: " . $secKey . "\r\n";

                if (isset($headers["Sec-WebSocket-Protocol"])) {
                    $upgrade .= "Sec-WebSocket-Protocol: " . $headers["Sec-WebSocket-Protocol"] . "\r\n";
                }

                $upgrade .= "\r\n";
            }
        } else {
            $this->webSocketVersion = 75;
            // WebSocket draft version 75
            // http://tools.ietf.org/html/draft-hixie-thewebsocketprotocol-75
            $upgrade = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
                "Upgrade: WebSocket\r\n" .
                "Connection: Upgrade\r\n" .
                "WebSocket-Origin: " . $origin . "\r\n" .
                "WebSocket-Location: ws://" . $host . $requestUri . "\r\n" .
                "\r\n";
        }

        return $upgrade;
    }

    /**
     * @param string $key
     * @return float|string
     */
    public function handleSecurityKey($key)
    {
        // extract the numeric digits to form a number
        preg_match_all('/\d/', $key, $number);
        // extract the number of spaces
        preg_match_all('/ /', $key, $space);
        if ($number && $space) {
            // make sure there is no division by zero
            if (!count($space[0])) {
                return '';
            }
            // perform the calculation per the websocket spec
            return (int)implode('', $number[0]) / count($space[0]);
        }
        return '';
    }

    /**
     * @param string $key1
     * @param string $key2
     * @param string $code
     * @return string
     */
    public function getHandshakeSecurityKey($key1, $key2, $code): string
    {
        return md5(
            pack('N', $this->handleSecurityKey($key1)) .
            pack('N', $this->handleSecurityKey($key2)) .
            $code,
            true
        );
    }

    /**
     * @param $request
     * @return array
     */
    public function handleRequestHeader($request): array
    {
        $requestUri = $code = null;
        // get the resource requested (part of URL after domain and port)
        preg_match('/GET (.*?) HTTP/', $request, $match) && $requestUri = $match[1];
        // get the security code if present (WebSockets draft 76)
        preg_match("/\r\n(.*?)\$/", $request, $match) && $code = $match[1];

        // remember the rest of the headers
        $headers = [];
        foreach (explode("\r\n", $request) as $line) {
            if (str_contains($line, ': ')) {
                [$key, $value] = explode(': ', $line);
                $headers[strtolower(trim($key))] = trim($value);
            }
        }
        return [$requestUri, $headers, $code];
    }

    public function sendNoop(): void
    {
        //reset noop timer
        $this->lastNoopTime = time();
        if ($this->getRtbCommandController()) {
            $clientFd = SocketHelper::new()->getSocketFd($this->socketResource);
            $rtbDaemon = $this->getRtbDaemon();
            if (isset($rtbDaemon->clientSockets[$clientFd])) {
                $clientSocket = $rtbDaemon->clientSockets[$clientFd];
                $this->createServerResponseSender()->handleResponse(
                    $clientSocket,
                    $this->getRtbCommandController()->getNoopResponse()
                );
            }
        }
    }

    public function onWrite(): void
    {
        if (
            $this->writeBuffer === ''
            && !$this->keepAlive
        ) {
            $this->disconnected = true;
            $this->onDisconnect();
            $this->close();
        }
    }

    public function onConnect(): void
    {
        log_info(composeLogData(["Accepted" => $this->remoteHost . ':' . $this->remotePort]));
        $this->acceptedTime = time();
        $this->lastActionTime = $this->acceptedTime;
        $this->lastNoopTime = $this->acceptedTime; //start noop timer
    }

    public function onDisconnect(): void
    {
        log_info(composeLogData(["Disconnected" => $this->remoteHost . ':' . $this->remotePort]));
        $rtbCommandController = $this->getRtbCommandController();
        if ($rtbCommandController) {
            $this->createBidderInterestDisconnecter()->sendDropInterest($this);
            $rtbCommandController->unregister();
        }

        $this->disconnected = true;
        $this->close();

        // Removing from clients socket to reduce memory consumption
        unset($this->getRtbDaemon()->clientSockets[SocketHelper::new()->getSocketFd($this->socketResource)]);
    }

    public function onTimer(): void
    {
        $idleTime = time() - $this->lastActionTime;
        $totalTime = time() - $this->acceptedTime;
        $noopTimeDiff = time() - $this->lastNoopTime;
        $noopTimeAgainstRefreshTimeout = $this->cfg()->get('core->rtb->autoRefreshTimeout') - $noopTimeDiff;

        if ($noopTimeAgainstRefreshTimeout < 0) {
            $this->sendNoop();
        }

        if ($totalTime > $this->maxTotalTime
            || $idleTime > $this->maxIdleTime
        ) {
            log_info("!!Keep-alive time exceeded {$this->remoteHost}:{$this->remotePort}");
            $this->disconnected = true;
            $this->close();
        }

        $timeout = 150;
        if (!$this->getRtbCommandController()
            && $totalTime > $timeout
        ) {
            log_info("Failed to identify RTB type within {$timeout}s" . composeSuffix([$this->remoteHost => $this->remotePort]));
            $this->disconnected = true;
            $this->close();
        }
    }

    /**
     * @return ControllerBase|null
     */
    public function getRtbCommandController(): ?ControllerBase
    {
        return $this->rtbCommandController;
    }

    /**
     * @param ControllerBase | null $rtbCommandController
     * @return static
     */
    public function setRtbCommandController(?ControllerBase $rtbCommandController): static
    {
        $this->rtbCommandController = $rtbCommandController;
        return $this;
    }

    /**
     * @return array
     */
    public function logData(): array
    {
        $editorUserId = $userType = null;
        $rtbCommandController = $this->rtbCommandController;
        if ($rtbCommandController) {
            $editorUserId = $rtbCommandController->getEditorUserId();
            $userType = $rtbCommandController->userType;
        }
        return parent::logData() + ['u' => $editorUserId, 'ut' => $userType];
    }
}
