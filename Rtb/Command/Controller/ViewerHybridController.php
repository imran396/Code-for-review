<?php
/**
 * Custom methods can be used there or in customized class (SAM-1570)
 *
 * Optional method called when rendering the custom lot item field value
 * param LotItemCustField $lotCustomField the custom lot item field object
 * param LotItemCustData $lotCustomData the custom lot item field data
 * param integer $auctionId auction.id to which lot is assigned
 * return string the rendered value
 * public function LotCustomField_{Field name}_Render(LotItemCustField $lotCustomField, LotItemCustData $lotCustomData, $auctionId)
 *
 * {Field name} - Camel cased name of custom field (see TextTransformer::toCamelCase())
 */

namespace Sam\Rtb\Command\Controller;

use Sam\Application\RequestParam\ParamFetcherForRtbd;
use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Concrete\Hybrid\SendMessage;
use Sam\Rtb\Command\Concrete\Hybrid\Sync;
use Sam\Rtb\Command\Concrete\Ping\PingCommand;
use Sam\Rtb\Command\Concrete\Ping\PingHandler;
use Sam\Rtb\Command\Concrete\ReversePing\ReversePingCommand;
use Sam\Rtb\Command\Concrete\ReversePing\ReversePingHandler;

/**
 * Class ViewerHybridController
 */
class ViewerHybridController extends ControllerBase
{
    /** @var string[] */
    protected array $commandHandlerMethodNames = [
        Constants\Rtb::CMD_SYNC_Q => 'sync',
        Constants\Rtb::CMD_SEND_MESSAGE_Q => 'sendMessage',
        Constants\Rtb::CMD_PING_Q => 'ping',
        Constants\Rtb::CMD_REVERSE_PING_Q => 'reversePing',
    ];

    /**
     * Get a ViewerHybridController instance
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
        $command->setUserType(Constants\Rtb::UT_VIEWER);
        return $command;
    }

    /**
     * @param object $data
     * @return array
     */
    protected function sync(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $viewerResourceId = $paramFetcher->getInt(Constants\Rtb::REQ_VIEWER_RESOURCE_ID); // viewer-repeater case
        $command = Sync::new();
        /** @var Sync $command */
        $command = $this->initCommand($command, $data);
        $command->setViewerResourceId($viewerResourceId);
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
