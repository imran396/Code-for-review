<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Auth;

use EpiCurlManager;
use Sam\Core\Ip\Validate\CidrChecker;
use Sam\Core\Service\CustomizableClass;
use EpiCurl;
use Exception;
use Sam\Application\RequestParam\ParamFetcherForRtbd;
use Sam\Application\RequestParam\ParamFetcherForRtbdAwareTrait;
use Sam\Application\Url\UrlAdvisorAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\Command\Controller\AdminHybridController;
use Sam\Rtb\Command\Controller\AdminLiveController;
use Sam\Rtb\Command\Controller\AuctioneerController;
use Sam\Rtb\Command\Controller\BidderHybridController;
use Sam\Rtb\Command\Controller\BidderLiveController;
use Sam\Rtb\Command\Controller\ClientHybridController;
use Sam\Rtb\Command\Controller\ClientLiveController;
use Sam\Rtb\Command\Controller\ControllerBase;
use Sam\Rtb\Command\Controller\ProjectorController;
use Sam\Rtb\Command\Controller\ViewerHybridController;
use Sam\Rtb\Command\Controller\ViewerLiveController;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Rtb\Pool\Auction\Load\AuctionRtbdLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Rtb\Pool\Feature\RtbdPoolFeatureAvailabilityValidatorAwareTrait;
use Sam\Rtb\RtbFactory;
use Sam\Rtb\Server\SocketBase\AwareTrait\RtbWebSocketClientAwareTrait;
use Sam\Rtb\Server\Daemon\RtbDaemonAwareTrait;
use Sam\Rtb\Server\Daemon\RtbDaemonEvent;
use Sam\Rtb\Server\Daemon\RtbDaemonLegacy;
use Sam\Rtb\Server\ServerResponseSenderCreateTrait;
use Sam\Rtb\Server\SocketBase\Event\EventClient;
use Sam\Rtb\Server\SocketBase\Legacy\LegacyClient;

/**
 * Class Authenticator
 * @package
 */
class Authenticator extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionRtbdLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use ConfigRepositoryAwareTrait;
    use RtbWebSocketClientAwareTrait;
    use ParamFetcherForRtbdAwareTrait;
    use RtbDaemonAwareTrait;
    use RtbdPoolFeatureAvailabilityValidatorAwareTrait;
    use ServerResponseSenderCreateTrait;
    use UrlAdvisorAwareTrait;
    use UrlParserAwareTrait;

    private string $requestJson;
    private array $ts = [];
    protected ?ControllerBase $rtbCommandController = null;
    private bool $isRtbdPool = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->isRtbdPool = $this->getRtbdPoolFeatureAvailabilityValidator()->isAvailable();
        return $this;
    }

    /**
     * @param EventClient|LegacyClient $client
     * @param RtbDaemonEvent|RtbDaemonLegacy $daemon
     * @param ParamFetcherForRtbd $paramFetcher
     * @param string $requestJson
     * @param array $ts
     * @return $this
     */
    public function construct(
        EventClient|LegacyClient $client,
        RtbDaemonEvent|RtbDaemonLegacy $daemon,
        ParamFetcherForRtbd $paramFetcher,
        string $requestJson,
        array $ts
    ): static {
        $this->setRtbWebSocketClient($client);
        $this->setRtbDaemon($daemon);
        $this->setRequestJson($requestJson);
        $this->setTs($ts);
        $this->setParamFetcherForRtbd($paramFetcher);
        return $this;
    }

    public function auth(): void
    {
        $client = $this->getRtbWebSocketClient();
        $requestJson = $this->getRequestJson();
        $ts = $this->getTs();
        $responseSender = $this->createServerResponseSender();

        $uKey = $this->getParamFetcherForRtbd()->getString(Constants\Rtb::REQ_UKEY);
        if (!$this->validateAuthenticationKey($uKey)) {
            $responseSender->logTs($ts, Constants\Rtb::CMD_AUTH_Q . ' invalid auth key');
            $responseSender->handleInvalid($client, 'Invalid authentication key', $requestJson);
            return;
        }

        [$editorUserId, $userType, $sessionId, $password] = $this->parseAndNormalizeAuthenticationKey($uKey);

        if (!$userType) {
            log_error("Console user type incorrect, when parsing auth key" . composeSuffix(['ukey' => $uKey]));
            return;
        }

        $auctionId = (int)$this->getParamFetcherForRtbd()->getIntPositive(Constants\Rtb::REQ_AUCTION_ID);
        if (!$this->validateConnectedAuction($auctionId)) {
            return;
        }

        $remoteAddress = (string)$this->getRtbWebSocketClient()->remoteHost;

        $userTypeName = null;
        switch ($userType) {
            case Constants\Rtb::UT_CLERK:
                $userTypeName = 'admin clerk';
                break;
            case Constants\Rtb::UT_BIDDER:
                $userTypeName = 'bidder';
                $auctionBidder = $this->getAuctionBidderLoader()->load($editorUserId, $auctionId, true);
                if ($auctionBidder) {
                    $bidderNo = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
                    $userTypeName .= ' ' . $bidderNo;
                    unset($auctionBidder, $bidderNo);
                }
                break;
            case Constants\Rtb::UT_VIEWER:
                $userTypeName = 'viewer';
                break;
            case Constants\Rtb::UT_PROJECTOR:
                $userTypeName = 'projector';
                break;
            case Constants\Rtb::UT_AUCTIONEER:
                $userTypeName = 'auctioneer screen';
                break;
            case Constants\Rtb::UT_CLIENT:
                if (!$password) {
                    $message = sprintf('Access denied (%s) no security password supplied', $remoteAddress);
                    $responseSender->logTs($this->getTs(), Constants\Rtb::CMD_AUTH_Q . ' denied missing pw');
                    $responseSender->handleInvalid($client, $message, $this->getRequestJson());
                    return;
                }

                if ($password !== $this->cfg()->get('core->rtb->client->password')) {
                    $message = sprintf('Access denied (%s) password supplied do not match the core->rtb->client->password defined', $remoteAddress);
                    $responseSender->logTs($this->getTs(), Constants\Rtb::CMD_AUTH_Q . ' denied pw mismatch');
                    $responseSender->handleInvalid($client, $message, $this->getRequestJson());
                    return;
                }

                $isAllowed = CidrChecker::new()
                    ->setSubnets($this->cfg()->get('core->rtb->client->ipAllow')->toArray())
                    ->isInCidrList($remoteAddress);
                if (!$isAllowed) {
                    $message = sprintf('Access denied for IP %s terminating connection', $remoteAddress);
                    $responseSender->logTs($this->getTs(), Constants\Rtb::CMD_AUTH_Q . ' denied IP not allowed');
                    $responseSender->handleInvalid($client, $message, $this->getRequestJson());
                    return;
                }

                $isDenied = CidrChecker::new()
                    ->setSubnets($this->cfg()->get('core->rtb->client->ipDeny')->toArray())
                    ->isInCidrList($remoteAddress);
                if ($isDenied) {
                    $message = sprintf('Access blocked for IP %s terminating connection', $remoteAddress);
                    $responseSender->logTs($this->getTs(), Constants\Rtb::CMD_AUTH_Q . ' denied IP blocked');
                    $responseSender->handleInvalid($client, $message, $this->getRequestJson());
                    return;
                }

                $userTypeName = 'client';
                break;
        }

        $rtbCommandController = RtbFactory::new()->createCommandController(
            $editorUserId,
            $auctionId,
            $userType,
            $sessionId,
            $remoteAddress,
            (int)$this->getRtbWebSocketClient()->remotePort,
            $this->getRtbDaemon()
        );

        if (!$rtbCommandController) {
            $responseSender->logTs($ts, Constants\Rtb::CMD_AUTH_Q . ' undefined cmd');
            $responseSender->handleInvalid($client, 'Undefined command object terminate connection ', $requestJson);
            return;
        }

        $this->setRtbCommandController($rtbCommandController);

        $rtbCommandController->log("Client " . $sessionId . " requests authentication as " . $userTypeName);

        if (
            $this->cfg()->get('core->rtb->server->shouldAuth')
            && $editorUserId
        ) {
            $params = [
                session_name() => $sessionId,
                RtbAuthChecker::PARAM_USER_ID => $editorUserId,
                RtbAuthChecker::PARAM_USER_TYPE => $userType,
            ];
            $url = $this->getUrlParser()->replaceParams("http://" . $this->cfg()->get('core->app->httpHost') . "/authenticate.php", $params);
            log_debug($url);

            $ch = 'usr' . $editorUserId;
            ${$ch} = curl_init($url);
            curl_setopt(${$ch}, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt(${$ch}, CURLOPT_TIMEOUT, 5);
            curl_setopt(${$ch}, CURLOPT_HEADER, 0);
            curl_setopt(${$ch}, CURLOPT_RETURNTRANSFER, 1);
            /** @noinspection CurlSslServerSpoofingInspection */
            curl_setopt(${$ch}, CURLOPT_SSL_VERIFYPEER, 0);
            /** @noinspection CurlSslServerSpoofingInspection */
            curl_setopt(${$ch}, CURLOPT_SSL_VERIFYHOST, 0);
            $mc = EpiCurl::getInstance();
            $curl = $mc->addCurl(${$ch});

            if (is_a($curl, EpiCurlManager::class)) {
                $this->getRtbDaemon()
                    ->getAuthQueueManager()
                    ->add($editorUserId, $this->getRtbWebSocketClient(), $curl);
            } else {
                $this->authenticate(false);
            }
        } else {
            // Bypass remote authentication
            $this->authenticate(true);
        }
    }

    /**
     * @param bool $isAuth
     */
    public function authenticate(bool $isAuth): void
    {
        $responseSender = $this->createServerResponseSender();
        $rtbCommandController = $this->getRtbCommandController();
        $remoteAddr = $this->getRtbWebSocketClient()->remoteHost;
        if ($isAuth) {
            log_info(composeLogData(["Auth Passed" => $remoteAddr . ':' . $this->getRtbWebSocketClient()->remotePort]));
            $userType = null;
            if (
                $rtbCommandController instanceof AdminLiveController
                || $rtbCommandController instanceof AdminHybridController
            ) {
                $userType = Constants\Rtb::UT_CLERK;
            } elseif ($rtbCommandController instanceof AuctioneerController) {
                $userType = Constants\Rtb::UT_AUCTIONEER;
            } elseif ($rtbCommandController instanceof ProjectorController) {
                $userType = Constants\Rtb::UT_PROJECTOR;
            } elseif (
                $rtbCommandController instanceof BidderLiveController
                || $rtbCommandController instanceof BidderHybridController
            ) {
                $userType = Constants\Rtb::UT_BIDDER;
            } elseif (
                $rtbCommandController instanceof ViewerLiveController
                || $rtbCommandController instanceof ViewerHybridController
            ) {
                $userType = Constants\Rtb::UT_VIEWER;
            } elseif (
                $rtbCommandController instanceof ClientLiveController
                || $rtbCommandController instanceof ClientHybridController
            ) {
                $userType = Constants\Rtb::UT_CLIENT;
            }
            if ($userType !== Constants\Rtb::UT_CLIENT) {
                // No need to register client type since it just a temporary connection
                try {
                    $rtbCommandController->register($userType);
                } catch (Exception $e) {
                    log_error(
                        "Cannot register user session for client"
                        . composeSuffix([$remoteAddr => $this->getRtbWebSocketClient()->remotePort])
                        . " " . $e->getMessage()
                    );
                    return;
                }
            }
            $resp = $rtbCommandController->response(Constants\Rtb::CMD_AUTH_Q, (object)[true]);
            $responseSender->handleResponse(
                $this->getRtbWebSocketClient(),
                (string)$resp[Constants\Rtb::RT_SINGLE]
            );
        } else {
            log_info(composeLogData(["Auth Failed" => $remoteAddr . ':' . $this->getRtbWebSocketClient()->remotePort]));
            $resp = $rtbCommandController->response(Constants\Rtb::CMD_AUTH_Q, (object)[false]);
            $responseSender->handleResponse(
                $this->getRtbWebSocketClient(),
                (string)$resp[Constants\Rtb::RT_SINGLE]
            );
        }
    }

    /**
     * @return string|null
     */
    public function getRequestJson(): ?string
    {
        return $this->requestJson;
    }

    /**
     * @param string $requestJson
     * @return static
     */
    public function setRequestJson(string $requestJson): static
    {
        $this->requestJson = $requestJson;
        return $this;
    }

    /**
     * @return ControllerBase|null
     */
    public function getRtbCommandController(): ?ControllerBase
    {
        return $this->rtbCommandController;
    }

    /**
     * @param ControllerBase|null $rtbCommandController
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
    public function getTs(): array
    {
        return $this->ts;
    }

    /**
     * @param array $ts
     * @return static
     */
    public function setTs(array $ts): static
    {
        $this->ts = $ts;
        return $this;
    }

    /**
     * @param string $key
     * @return array{0: int|null, 1: int|null, 2: string, 3: string}
     */
    protected function parseAndNormalizeAuthenticationKey(string $key): array
    {
        $parts = explode('|', $key);
        $editorUserId = Cast::toInt($parts[0] ?? null, Constants\Type::F_INT_POSITIVE);
        $userType = Cast::toInt($parts[1] ?? null, Constants\Rtb::$rtbConsoleUserTypes);
        $sessionId = trim($parts[2] ?? '');
        $password = trim($parts[3] ?? '');
        return [$editorUserId, $userType, $sessionId, $password];
    }

    /**
     * @param string $key
     * @return bool
     */
    protected function validateAuthenticationKey(string $key): bool
    {
        $success = true;
        $parts = explode('|', $key);
        if (!in_array(count($parts), [3, 4], true)) {
            $success = false;
        } else {
            // list($userId, $userType, $sessionId, $accountId, $password) = $this->parseAuthenticationKey($key);
            // if ($userId) {
            //     $isFound = $this->getUserExistenceChecker()->existById($userId);
            //     if (!$isFound) {
            //         $success = false;
            //     }
            // }
            // TODO: validate parts, $userId - should be available user, $userType - available type,
            // $sessionId - correct session format, $accountId - active account
            // produce respective error messages, extract to Validator class with ResultStatusCollector
            // it could be some Rtb (Auth) Request Validator
        }
        return $success;
    }

    /**
     * Check if auction available and linked to rtbd instance in pool
     * @param int $auctionId
     * @return bool
     */
    protected function validateConnectedAuction(int $auctionId): bool
    {
        $client = $this->getRtbWebSocketClient();
        $responseSender = $this->createServerResponseSender();
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            $responseSender->logTs($this->getTs(), Constants\Rtb::CMD_AUTH_Q . ' unknown auction');
            $message = "Available auction not found" . composeSuffix(['a' => $auctionId]);
            $responseSender->handleInvalid($client, $message, $this->getRequestJson());
            return false;
        }

        if ($this->isRtbdPool) {
            $rtbdName = $this->getRtbDaemon()->getName();
            $auctionRtbd = $this->getAuctionRtbdLoader()->load($auctionId);
            if (!$auctionRtbd) {
                $responseSender->logTs($this->getTs(), Constants\Rtb::CMD_AUTH_Q . ' auction not linked to rtbd');
                $message = "Auction is not linked to any rtbd instance in pool" . composeSuffix(['a' => $auctionId]);
                $responseSender->handleInvalid($client, $message, $this->getRequestJson());
                return false;
            }

            if ($rtbdName !== $auctionRtbd->RtbdName) {
                $responseSender->logTs($this->getTs(), Constants\Rtb::CMD_AUTH_Q . ' not match auction and rtbd instance in pool');
                $message = "Auction is not linked to this rtbd instance in pool" . composeSuffix(['a' => $auctionId, 'rtbd' => $rtbdName]);
                $responseSender->handleInvalid($client, $message, $this->getRequestJson());
                return false;
            }
        }

        return true;
    }
}
