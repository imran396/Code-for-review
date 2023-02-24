<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\State\PendingAction\PendingActionUpdaterCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class EnterBidderNumber
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class EnterBidderNumber extends CommandBase implements RtbCommandHelperAwareInterface
{
    use LotRendererAwareTrait;
    use PendingActionUpdaterCreateTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbRendererCreateTrait;

    protected ?string $langStatus = null;

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
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, Constants\Rtb::LA_IDLE, $this->detectModifierUserId()); // SAM-970
        $rtbCurrent = $this->createPendingActionUpdater()->update($rtbCurrent, Constants\Rtb::PA_ENTER_BIDDER_NUM);
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
        $auctionLot = $this->getAuctionLot();
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $this->langStatus = sprintf($this->translate('BIDDERCLIENT_LOTSOLDTOFLOOR'), $lotNo);

        //$this->getLogger()->log("Admin sold lot to floor bidder");

        // Save in static file
        $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($this->langStatus, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml);
    }

    protected function createResponses(): void
    {
        $data = [Constants\Rtb::RES_STATUS => $this->langStatus];
        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_ENTER_BIDDER_NUM_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $this->langStatus
        );

        $this->setResponses($responses);
    }
}
