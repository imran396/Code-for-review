<?php

namespace Sam\Rtb\Command\Controller;

use Sam\Application\RequestParam\ParamFetcherForRtbd;
use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Concrete\Hybrid\BuyNow;
use Sam\Rtb\Command\Concrete\Hybrid\LinkRtbd;
use Sam\Rtb\Command\Concrete\Hybrid\Resync;

/**
 * Class ClientHybridController
 */
class ClientHybridController extends ControllerBase
{
    /** @var string[] */
    protected array $commandHandlerMethodNames = [
        Constants\Rtb::CMD_BUY_NOW_Q => 'buyNow',
        Constants\Rtb::CMD_RESYNC_Q => 'resync',
        Constants\Rtb::CMD_LINK_RTBD_Q => 'linkRtbd',
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
        $command->setUserType(Constants\Rtb::UT_BIDDER);
        return $command;
    }

    /**
     * @param object $data
     * @return array
     */
    public function buyNow(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = BuyNow::new();
        /** @var BuyNow $command */
        $command = $this->initCommand($command, $data);
        $command->setBuyAmount($paramFetcher->getFloatPositive(Constants\Rtb::REQ_HAMMER_PRICE));
        $command->setBuyLotItemId($paramFetcher->getIntPositive(Constants\Rtb::REQ_BUY_NOW_LOT_ITEM_ID));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    public function resync(object $data): array
    {
        $command = Resync::new();
        /** @var Resync $command */
        $command = $this->initCommand($command, $data);
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }

    /**
     * @param object $data
     * @return array
     * @noinspection PhpUnused
     */
    public function linkRtbd(object $data): array
    {
        $paramFetcher = ParamFetcherForRtbd::new()->construct($data);
        $command = LinkRtbd::new();
        /** @var LinkRtbd $command */
        $command = $this->initCommand($command, $data);
        $command->setRtbdName($paramFetcher->getString(Constants\Rtb::REQ_NEW_RTBD_NAME));
        $command->execute();
        $responses = $command->getResponses();
        return $responses;
    }
}
