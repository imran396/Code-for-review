<?php

namespace Sam\Rtb\Command\Controller;

use Sam\Application\RequestParam\ParamFetcherForRtbd;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Simultaneous\Load\SimultaneousAuctionLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\Log\Logger;
use Sam\Rtb\Server\Daemon\RtbDaemonAwareTrait;
use Sam\Rtb\Server\Daemon\RtbDaemonEvent;
use Sam\Rtb\Server\Daemon\RtbDaemonLegacy;
use Sam\Rtb\Session\RtbSessionManagerCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class ControllerBase
 */
class ControllerBase extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use CurrencyLoaderAwareTrait;
    use RtbDaemonAwareTrait;
    use NumberFormatterAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbSessionManagerCreateTrait;
    use SimultaneousAuctionLoaderAwareTrait;

    protected ?int $accountId = null;
    protected ?int $auctionId = null;
    protected ?int $editorUserId = null;
    public ?int $userType = null;
    public ?string $sessionId = null;
    public string $remoteHost = '';
    public ?int $remotePort = null;
    public ?int $simultaneousAuctionId = null;
    public ?string $currencySign = null;
    protected array $commandHandlerMethodNames = [];
    protected ?Logger $logger = null;

    /**
     * Get a ControllerBase instance
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int|null $editorUserId
     * @param int $userType
     * @param string $sessionId
     * @param string $remoteHost
     * @param int $remotePort
     * @param RtbDaemonLegacy|RtbDaemonEvent $daemon
     */
    public function init(
        int $auctionId,
        ?int $editorUserId,
        int $userType,
        string $sessionId,
        string $remoteHost,
        int $remotePort,
        RtbDaemonLegacy|RtbDaemonEvent $daemon
    ): void {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            $logData = [
                'a' => $auctionId,
                'ut' => $userType,
                'editor u' => $editorUserId,
                'remote' => $remoteHost . ':' . $remotePort,
            ];
            log_error("Available auction not found for rtb command initialization" . composeSuffix($logData));
            return;
        }

        $this->auctionId = $auctionId;
        $this->editorUserId = $editorUserId;
        $this->userType = $userType;
        $this->sessionId = $sessionId;
        $this->accountId = $auction->AccountId;
        // TODO: add and apply \Sam\Rtb\Trait\RemoteAddressAwareTrait for $this->remoteAddress and $this->remotePort
        $this->remoteHost = $remoteHost;
        $this->remotePort = $remotePort;
        // TODO: rename and apply \Sam\Rtb\Log\RtbLogger, \Sam\Rtb\Log\RtbLoggerAwareTrait
        $this->logger = Logger::new()
            ->setAuctionId($auctionId)
            ->setUserId($editorUserId)
            ->setRemoteAddress($remoteHost)
            ->setRemotePort($remotePort);

        $this->getNumberFormatter()->construct($this->accountId);
        $this->currencySign = $this->getCurrencyLoader()->detectDefaultSign($auctionId);
        $this->setRtbDaemon($daemon);
    }

    /**
     * @param int $userType
     */
    public function register(int $userType): void
    {
        $this->createRtbSessionManager()->register(
            $this->getAuctionId(),
            $this->getEditorUserId(),
            $this->sessionId,
            $userType,
            $this->remoteHost,
            $this->remotePort
        );
    }

    public function unregister(): void
    {
        $this->createRtbSessionManager()->unregister(
            $this->getAuctionId(),
            $this->getEditorUserId(),
            $this->userType,
            $this->remoteHost,
            $this->remotePort
        );
    }

    /**
     * @param string $cmd
     * @param object $data
     * @return array
     */
    public function response(string $cmd, object $data): array
    {
        if (isset($this->commandHandlerMethodNames[$cmd])) {
            $methodName = $this->commandHandlerMethodNames[$cmd];
            $responses = $this->$methodName($data);
        } elseif ($cmd === Constants\Rtb::CMD_AUTH_Q) {
            $responses = $this->getAuthResponse($data);
        } else {
            log_error(composeLogData(["Unexpected command" => $cmd, "Data" => serialize($data)]));
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_ERR_S,
                Constants\Rtb::RES_DATA => [
                    Constants\Rtb::RES_MESSAGE => 'Failed to process the command!',
                ],
            ];
            $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
        }
        return $responses;
    }

    /**
     * @param object $dataObj
     * @return array
     */
    public function getAuthResponse(object $dataObj): array
    {
        $metaData = $this->getResponseDataProducer()->produceMetaData();
        $data = (array)$dataObj;
        $responseData = array_merge([Constants\Rtb::RES_CONFIRM => $data[0]], $metaData);
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_AUTH_S,
            Constants\Rtb::RES_DATA => $responseData,
        ];
        $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
        return $responses;
    }

    /**
     * @return string
     */
    public function getNoopResponse(): string
    {
        $metaData = $this->getResponseDataProducer()->produceMetaData();
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_NOOP,
            Constants\Rtb::RES_DATA => $metaData,
        ];
        $responseJson = json_encode($response);
        return $responseJson;
    }

    /**
     * @param CommandBase $command
     * @param object $data
     * @return CommandBase
     */
    protected function initCommand(CommandBase $command, object $data): CommandBase
    {
        $command->setAuctionId($this->getAuctionId());
        $command->setCurrency($this->currencySign);
        $command->setRtbDaemon($this->getRtbDaemon());
        $command->setLogger($this->logger);
        $command->setSimultaneousAuctionId($this->getSimultaneousAuctionId());
        $command->setEditorUserId($this->getEditorUserId());
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        if ($paramFetcher->has(Constants\Rtb::REQ_RUNNING_LOT_ITEM_ID)) {
            $command->setLotItemId($paramFetcher->getIntPositive(Constants\Rtb::REQ_RUNNING_LOT_ITEM_ID));
        }
        return $command;
    }

    /**
     * @param string $msg
     */
    public function log(string $msg): void
    {
        $this->logger->log($msg);
    }

    /**
     * @return int|null
     */
    public function getSimultaneousAuctionId(): ?int
    {
        if ($this->simultaneousAuctionId === null) {
            $auction = $this->getAuctionLoader()->load($this->getAuctionId());
            if ($auction) {
                $this->simultaneousAuctionId = $this->getSimultaneousAuctionLoader()
                    ->findSimultaneousAuctionId($auction, true);
            }
        }
        return $this->simultaneousAuctionId;
    }

    /**
     * @param int|null $simultaneousAuctionId
     * @return static
     * @noinspection PhpUnused
     */
    public function setSimultaneousAuctionId(?int $simultaneousAuctionId): static
    {
        $this->simultaneousAuctionId = $simultaneousAuctionId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getEditorUserId(): ?int
    {
        return $this->editorUserId;
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * @return int
     */
    public function getAuctionId(): int
    {
        return $this->auctionId;
    }
}
