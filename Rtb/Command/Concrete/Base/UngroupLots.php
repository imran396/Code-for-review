<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class UngroupLots
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class UngroupLots extends CommandBase implements RtbCommandHelperAwareInterface
{
    use GroupingHelperAwareTrait;
    use LotRendererAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbRendererCreateTrait;

    protected ?string $statusMessage = null;

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
        $auctionLot = $this->getAuctionLot();
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $rtbCurrent->LotGroup = '';
        if (!$rtbCurrent->AbsenteeBid) {
            $rtbCurrent->NewBidBy = null;
        }
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $this->getLogger()->log("Admin clerk ungroup lots to lot {$lotNo} ");

        $this->getGroupingHelper()->clearGroup($this->getAuctionId());

        $this->statusMessage = 'Lot ' . $lotNo . ' is now sold by itself again';
        $this->statusMessage = $this->getRtbGeneralHelper()->clean($this->statusMessage);

        $message = $this->createRtbRenderer()->renderAuctioneerMessage($this->statusMessage, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message);
    }

    protected function createResponses(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $data = [
            Constants\Rtb::RES_GROUP_MESSAGE => $this->statusMessage,
            Constants\Rtb::RES_IS_ABSENTEE_BID => $rtbCurrent->AbsenteeBid,
            Constants\Rtb::RES_LOT_ITEM_ID => $rtbCurrent->LotItemId,
        ];

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UNGROUP_LOT_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $this->statusMessage
        );

        $this->setResponses($responses);
    }
}
