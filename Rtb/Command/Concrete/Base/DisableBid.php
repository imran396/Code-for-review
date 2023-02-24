<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class DisableBid
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class DisableBid extends CommandBase implements RtbCommandHelperAwareInterface
{
    use RtbCurrentWriteRepositoryAwareTrait;

    public function execute(): void
    {
        if (
            !$this->checkConsoleSync()
            || !$this->checkRunningLot()
        ) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, Constants\Rtb::LA_IDLE, $this->detectModifierUserId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
        log_info('Bidding Disabled' . composeSuffix(['a' => $rtbCurrent->AuctionId]));
    }

    protected function createResponses(): void
    {
        $responses = [];
        $auctionLot = $this->getAuctionLot();
        $currentBidTransaction = $auctionLot ? $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId) : null;

        // is a floor bid if the current bid_transaction does not have a user_id
        $isFloor = $currentBidTransaction
            ? $currentBidTransaction->UserId === null || !is_int($currentBidTransaction->UserId)
            : true;
        if (!$isFloor) { // Not floor Bidder
            $data = [];
            $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_ENTER_BIDDER_NUM_S, Constants\Rtb::RES_DATA => $data];
            $responseJson = json_encode($response);
            $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        }
        $this->setResponses($responses);
    }
}
