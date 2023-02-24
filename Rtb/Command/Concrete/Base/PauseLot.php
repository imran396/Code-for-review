<?php

/**
 * SAM-4036: Pause lot in clerking console for live and hybrid auctions
 * TODO: SAM-6055: Rename rtb_current.lot_active to rtb_current.lot_run_status
 */

namespace Sam\Rtb\Command\Concrete\Base;

use LotItem;
use Sam\Bidding\BidTransaction\Validate\BidTransactionExistenceCheckerAwareTrait;
use Sam\Core\Constants;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\Concrete\GameStatusDataProducer;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class PauseLot
 * @package Sam\Rtb\Command\Concrete\Base
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class PauseLot extends CommandBase implements RtbCommandHelperAwareInterface
{
    use BidTransactionExistenceCheckerAwareTrait;
    use LotRendererAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbRendererCreateTrait;

    public function execute(): void
    {
        if (!$this->checkRunningLot()) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, Constants\Rtb::LA_PAUSED, $this->detectModifierUserId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $lotItem = $this->getLotItem();

        $lotNo = $this->getLotRenderer()->renderLotNo($this->getAuctionLot());
        $this->getLogger()->log("Admin clerk pauses lot {$lotNo} ({$lotItem->Id})");

        $lotName = $this->getLotRenderer()->makeName($lotItem->Name, $this->getAuction()->TestAuction);
        $lotNameNormal = GameStatusDataProducer::new()->normalizeLotName($lotName);
        $langLotPausedTpl = $this->translate('BIDDERCLIENT_MSG_LOTPAUSED');
        $statusInfo = sprintf($langLotPausedTpl, $lotNo, ee($lotNameNormal));

        $hasCurrentTransaction = $this->createBidTransactionExistenceChecker()->exist(null, $lotItem->Id, $this->getAuctionId());

        $message = $this->createRtbRenderer()->renderAuctioneerMessage($statusInfo, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message);

        $data = [
            Constants\Rtb::RES_LOT_ITEM_ID => $lotItem->Id,
            Constants\Rtb::RES_LOT_ACTIVITY => $rtbCurrent->LotActive,
            Constants\Rtb::RES_IS_CURRENT_BID => $hasCurrentTransaction,
            Constants\Rtb::RES_STATUS => $statusInfo,
        ];

        $data = array_merge(
            $data,
            $this->getResponseDataProducer()->produceUndoButtonData($rtbCurrent)
        );

        $response = [Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_PAUSE_LOT_S, Constants\Rtb::RES_DATA => $data];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        $this->setResponses($responses);
    }
}
