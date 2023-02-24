<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Lot\Reset\LiveLotResetter;
use Sam\Rtb\Command\Bid\BidUpdaterAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\Rtb\State\RtbStateUpdaterCreateTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class ResetLot
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class ResetLot extends CommandBase implements RtbCommandHelperAwareInterface
{
    use BidUpdaterAwareTrait;
    use LotRendererAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbStateResetterAwareTrait;
    use RtbStateUpdaterCreateTrait;

    /**
     */
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

        LiveLotResetter::new()->reset(
            $rtbCurrent->LotItemId,
            $this->getAuctionId(),
            $this->detectModifierUserId()
        );

        $lotNo = $this->getLotRenderer()->renderLotNo($this->getAuctionLot());

        $this->getLogger()->log("Admin clerk resets lot {$lotNo} ({$rtbCurrent->LotItemId})");

        $rtbCurrent = $this->getBidUpdater()
            ->setRtbCurrent($rtbCurrent)
            ->setLotItem($this->getLotItem())
            ->setAuction($this->getAuction())
            ->update($this->detectModifierUserId());

        $rtbCurrent = $this->getRtbStateResetter()->cleanState($rtbCurrent, $this->detectModifierUserId());
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, Constants\Rtb::LA_BY_AUTO_START, $this->detectModifierUserId());
        $rtbCurrent = $this->createRtbStateUpdater()->update($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
    }

    protected function createResponses(): void
    {
        $rtbCurrent = $this->getRtbCurrent();

        // Make clerk console response
        $data = $this->getBidUpdater()->getData();
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;

        // Make auctioneer console response
        $data = array_replace(
            $data,
            $this->getResponseDataProducer()->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_AUCTIONEER)
        );
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        // Make public consoles response
        $data = $this->getResponseHelper()->removeSensitiveData($data);
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_UPDATE_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;

        $this->setResponses($responses);
    }
}
