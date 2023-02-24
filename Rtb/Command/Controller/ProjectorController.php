<?php

namespace Sam\Rtb\Command\Controller;

use Sam\Application\RequestParam\ParamFetcherForRtbd;
use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Concrete\Live\SendMessage;
use Sam\Rtb\Command\Concrete\Live\Sync;
use Sam\Rtb\Command\Concrete\Ping\PingCommand;
use Sam\Rtb\Command\Concrete\Ping\PingHandler;
use Sam\Rtb\Command\Concrete\ReversePing\ReversePingCommand;
use Sam\Rtb\Command\Concrete\ReversePing\ReversePingHandler;

/**
 * Class ProjectorController
 */
class ProjectorController extends ControllerBase
{
    /** @var string[] */
    protected array $commandHandlerMethodNames = [
        Constants\Rtb::CMD_SYNC_Q => 'sync',
        Constants\Rtb::CMD_SEND_MESSAGE_Q => 'sendMessage',
        Constants\Rtb::CMD_PING_Q => 'ping',
        Constants\Rtb::CMD_REVERSE_PING_Q => 'reversePing',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param CommandBase $command
     * @param object $data
     * @return CommandBase
     */
    protected function initCommand(CommandBase $command, object $data): CommandBase
    {
        $command = parent::initCommand($command, $data);
        $command->setUserType(Constants\Rtb::UT_PROJECTOR);
        return $command;
    }

    /**
     * @param object $data
     * @return array
     */
    protected function sync(object $data): array
    {
        $command = Sync::new();
        /** @var Sync $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     */
    protected function sendMessage(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = SendMessage::new();
        /** @var SendMessage $command */
        $command = $this->initCommand($command, $data);
        $command->setMessage($paramFetcher->getString(Constants\Rtb::REQ_MESSAGE));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * SAM-5739
     * @param object $data
     * @return array
     */
    public function ping(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = PingCommand::new()->construct(
            $paramFetcher->getString(Constants\Rtb::REQ_PING_TS)
        );
        $handler = PingHandler::new()->construct($command);
        /** @var PingHandler $handler */
        $handler = $this->initCommand($handler, $data);
        $handler->execute();
        $responses = $handler->getResponses();
        return $responses;
    }

    /**
     * SAM-5739
     * @param object $data
     * @return array
     */
    public function reversePing(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = ReversePingCommand::new()->construct(
            $paramFetcher->getFloat(Constants\Rtb::REQ_REVERSE_PING_TS)
        );
        $handler = ReversePingHandler::new()->construct($command);
        /** @var ReversePingHandler $handler */
        $handler = $this->initCommand($handler, $data);
        $handler->execute();
        $responses = $handler->getResponses();
        return $responses;
    }
}
