<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;

/**
 * Class DropInterest
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class DropInterest extends CommandBase
{
    public function execute(): void
    {
        if ($this->cfg()->get('core->rtb->biddingInterest->enabled')) {
            $editorUserId = $this->getEditorUserId();
            if (!$editorUserId) {
                return;
            }

            $interestManager = $this->getRtbDaemon()->getBidderInterestManager();
            if ($interestManager->hasInterested($this->getAuctionId(), $editorUserId)) {
                $interestManager->removeInterested($this->getAuctionId(), $editorUserId);
            }

            $data = [
                Constants\Rtb::RES_USER_ID => $editorUserId,
            ];
            $response = [
                Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_DROP_INTEREST_S,
                Constants\Rtb::RES_DATA => $data
            ];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_CLERK] = $responseJson;
            $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
            $this->setResponses($responses);
        }
    }
}
