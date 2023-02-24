<?php
/**
 * CMD_SYNC_INTEREST_Q command is produced by rtbd daemon per each connection,
 * hence we respond to current console connection only (Single)
 */

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;

/**
 * Class SyncInterest
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class SyncInterest extends CommandBase
{
    public function execute(): void
    {
    }

    protected function createResponses(): void
    {
        $responses = [];
        if ($this->cfg()->get('core->rtb->biddingInterest->enabled')) {
            $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_INTEREST_S, Constants\Rtb::RES_DATA => []];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_SINGLE] = $responseJson;
        }
        $this->setResponses($responses);
    }
}
