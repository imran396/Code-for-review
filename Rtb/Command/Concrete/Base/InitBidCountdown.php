<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class InitBidCountdown
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class InitBidCountdown extends CommandBase
{
    use RtbCurrentWriteRepositoryAwareTrait;

    protected string $countdownLabel = '';

    public function execute(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $rtbCurrent->BidCountdown = $this->countdownLabel;
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
    }

    protected function createResponses(): void
    {
        $data = [Constants\Rtb::RES_BID_COUNTDOWN => $this->countdownLabel];
        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_INIT_BID_COUNTDOWN_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;

        $this->setResponses($responses);
    }

    /**
     * @param string $countdownLabel
     * @return static
     */
    public function setCountdownLabel(string $countdownLabel): static
    {
        $this->countdownLabel = $countdownLabel;
        return $this;
    }
}
