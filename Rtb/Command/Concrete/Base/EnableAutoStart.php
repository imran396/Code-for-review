<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class EnableAutoStart
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class EnableAutoStart extends CommandBase
{
    use RtbCurrentWriteRepositoryAwareTrait;

    protected bool $isAutoStart = false;

    /**
     * @param bool $isAutoStart
     * @return static
     */
    public function enableAutoStart(bool $isAutoStart): static
    {
        $this->isAutoStart = $isAutoStart;
        return $this;
    }

    public function execute(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $rtbCurrent->AutoStart = $this->isAutoStart;
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
        log_info(
            'Lot Auto Start ' . ($rtbCurrent->AutoStart ? 'Enabled' : 'Disabled')
            . composeSuffix(['a' => $rtbCurrent->AuctionId])
        );
    }

    protected function createResponses(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $data = [
            Constants\Rtb::RES_LOT_AUTO_START => (int)$rtbCurrent->AutoStart,
        ];
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_ENABLE_AUTO_START_S,
            Constants\Rtb::RES_DATA => $data,
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        $this->setResponses($responses);
    }
}
