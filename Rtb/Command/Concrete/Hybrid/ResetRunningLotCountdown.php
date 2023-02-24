<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Hybrid\HelpersAwareTrait;

/**
 * Class ResetRunningLotCountdown
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class ResetRunningLotCountdown extends CommandBase
{
    use HelpersAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function execute(): void
    {
        $this->getTimeoutHelper()->updateLotEndDate($this->getRtbCurrent(), $this->detectModifierUserId());
    }

    protected function createResponses(): void
    {
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_RESET_RUNNING_LOT_COUNTDOWN_S,
            Constants\Rtb::RES_DATA => [],
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        $this->setResponses($responses);
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }
}
