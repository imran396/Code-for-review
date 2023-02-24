<?php

namespace Sam\Rtb\Command\Concrete\Base;

use AuctionLotItem;
use LotItem;
use RtbCurrent;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Constants;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Rtb\Command\Response\Concrete\GameStatusDataProducer;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\State\Reset\RtbStateResetterAwareTrait;
use Sam\Rtb\State\RtbStateUpdaterCreateTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class StartAuction
 * @package Sam\Rtb\Command\Concrete\Base
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class StartAuction extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AuctionCacheLoaderAwareTrait;
    use AuctionWriteRepositoryAwareTrait;
    use LotRendererAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbRendererCreateTrait;
    use RtbStateResetterAwareTrait;
    use RtbStateUpdaterCreateTrait;

    /**
     * true, if auction was paused before
     */
    protected bool $isResumed = false;
    /**
     * true, if auction is successfully started or resumed
     */
    protected bool $isRunning = false;
    protected ?AuctionLotItem $auctionLot = null;

    /**
     * Check if running lot (rtb_current.lot_item_id) can be played
     */
    abstract protected function isPlayableRunningLot(): bool;

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    public function execute(): void
    {
        if (!$this->checkBeforeStart()) {
            return;
        }

        $auction = $this->getAuction();

        $this->isResumed = $auction->isPaused();

        $this->initRtbCurrent();

        $auction->toStarted();
        if (!$this->isResumed) {
            $auction->StartDate = $this->getCurrentDateUtc();
        }
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $this->detectModifierUserId());

        $rtbCurrent = $this->getRtbCurrent();
        $rtbCurrent = $this->createRtbStateUpdater()->update($rtbCurrent, $this->getAuction()->AccountId, $this->getViewLanguageId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $this->isRunning = true;
    }

    protected function createResponses(): void
    {
        if (!$this->isRunning()) {
            $this->setResponses([]);
            return;
        }

        $rtbCurrent = $this->getRtbCurrent();
        $data = $this->getResponseHelper()->getLotData($rtbCurrent);
        $statusInfo = $this->buildAuctionStatusMessage() . '| ' . $this->buildLotStatusMessage();
        $data[Constants\Rtb::RES_STATUS] = $statusInfo;

        $clerkData = $auctioneerData = array_merge(
            $data,
            $this->getResponseDataProducer()->produceAdminSideData($rtbCurrent)
        );

        $responses = $this->makePublicConsoleResponses($data)
            + $this->makeClerkConsoleResponse($clerkData)
            + $this->makeAuctioneerConsoleResponse($auctioneerData);

        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $statusInfo
        );

        $isTextMsgEnabled = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::TEXT_MSG_ENABLED, $this->getAuction()->AccountId);
        $responses = $this->getResponseHelper()
            ->addSmsTextResponse($responses, $this->getAuction(), $rtbCurrent->LotItemId, $isTextMsgEnabled);

        $this->setResponses($responses);
    }

    /**
     * Make viewer/projector/bidder console responses
     * @param array $publicData
     * @return array
     */
    protected function makePublicConsoleResponses(array $publicData): array
    {
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $publicData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        return $responses;
    }

    /**
     * @param array $auctioneerData
     * @return array
     */
    protected function makeAuctioneerConsoleResponse(array $auctioneerData): array
    {
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $auctioneerData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;
        return $responses;
    }

    /**
     * @param array $clerkData
     * @return array
     */
    protected function makeClerkConsoleResponse(array $clerkData): array
    {
        $currentBidAmount = (float)$clerkData[Constants\Rtb::RES_CURRENT_BID];
        $clerkData = array_merge(
            $clerkData,
            $this->getResponseDataProducer()->produceIncrementData($this->getRtbCurrent(), ['currentBid' => $currentBidAmount])
        );
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SYNC_S,
            Constants\Rtb::RES_DATA => $clerkData
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        return $responses;
    }

    /**
     * @return bool
     */
    protected function checkBeforeStart(): bool
    {
        $auctionCache = $this->getAuctionCacheLoader()->load($this->getAuctionId());
        if (!$auctionCache || !$auctionCache->TotalLots) {
            log_warning('Cannot start empty auction' . composeSuffix(['a' => $this->getAuctionId()]));
            $this->stopAuction();
            return false;
        }
        if ($this->getAuction()->isStarted()) {
            log_error('Cannot start already started auction' . composeSuffix(['a' => $this->getAuctionId()]));
            return false;
        }
        return true;
    }

    /**
     * @return RtbCurrent
     */
    protected function initRtbCurrent(): RtbCurrent
    {
        if ($this->rtbCurrent === null) {
            $this->rtbCurrent = $this->getRtbCurrent();
        }
        $this->initRunningLot();
        if ($this->rtbCurrent->LotItemId === null) {
            // Available running lot not found, then stop auction
            $this->stopAuction();
        }
        return $this->rtbCurrent;
    }

    /**
     * Check if currently running lot needs to be changed. Change or keep it.
     */
    protected function initRunningLot(): void
    {
        if (!$this->isPlayableRunningLot()) {
            $this->switchToNextLot();
        }
    }

    /**
     * Change running lot to next available
     * If there are no more available lots, then set rtb_current.lot_item_id
     * @return void
     */
    protected function switchToNextLot(): void
    {
        $rtbCurrent = $this->getRtbCurrent();
        $nextLotItem = $this->getRtbCommandHelper()->findNextLotItem($rtbCurrent);
        if ($nextLotItem) {
            if ($rtbCurrent->LotItemId !== $nextLotItem->Id) {
                // If running lot is changed, then we should re-initialize rtb state
                log_info(
                    'Reset rtb state, because running lot is changed'
                    . composeSuffix(['a' => $rtbCurrent->AuctionId, 'li' => $nextLotItem->Id])
                );
                $rtbCurrent->LotItemId = $nextLotItem->Id;
                $rtbCurrent = $this->getRtbStateResetter()->cleanState($rtbCurrent, $this->detectModifierUserId());
                $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
            }
        } else {
            // There are no more lots available for play
            $rtbCurrent->LotItemId = null;
            $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
        }
    }

    /**
     * Run StopAuction command
     */
    protected function stopAuction(): void
    {
        $this->getRtbCommandHelper()->createCommand('StopAuction')->runInContext($this);
    }

    /**
     * @return bool
     */
    protected function isResumed(): bool
    {
        return $this->isResumed;
    }

    /**
     * Save in static file message
     */
    protected function createStaticMessages(): void
    {
        $message = $this->createRtbRenderer()->renderAuctioneerMessage($this->buildLotStatusMessage(), $this->getAuction())
            . $this->createRtbRenderer()->renderAuctioneerMessage($this->buildAuctionStatusMessage(), $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $message);
    }

    /**
     * @return string
     */
    protected function buildAuctionStatusMessage(): string
    {
        $message = $this->isResumed()
            ? $this->translate('BIDDERCLIENT_MSG_AUCRESUMED')
            : $this->translate('BIDDERCLIENT_MSG_AUCSTARTED');
        return $message;
    }

    /**
     * @return string
     */
    protected function buildLotStatusMessage(): string
    {
        $auctionLot = $this->getAuctionLot();
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $rtbCurrent = $this->getRtbCurrent();
        if ($rtbCurrent->isStartedOrPausedLot()) {
            $lotName = $this->getLotRenderer()->makeName($this->getLotItem()->Name, $this->getAuction()->TestAuction);
            $lotNameNormal = GameStatusDataProducer::new()->normalizeLotName($lotName);
            $langLotActivityStatusTpl = $rtbCurrent->isStartedLot()
                ? $this->translate('BIDDERCLIENT_MSG_LOTSTARTED')
                : $this->translate('BIDDERCLIENT_MSG_LOTPAUSED');
            $output = sprintf($langLotActivityStatusTpl, $lotNo, ee($lotNameNormal));
        } else {
            $langLotNotStarted = $this->translate('BIDDERCLIENT_MSG_LOTNOTSTARTED');
            $output = sprintf($langLotNotStarted, $lotNo);
        }
        return $output;
    }

    /**
     * Log to auction trail
     */
    protected function log(): void
    {
        $output = $this->getLogger()->getUserRoleName($this->getUserType());
        if ($this->isResumed()) {
            $output .= ' resumes';
        } else {
            $output .= ' starts';
        }
        $typeName = AuctionPureRenderer::new()->makeAuctionType($this->getAuction()->AuctionType);
        $output .= ' ' . strtolower($typeName);
        $output .= ' auction' . composeSuffix(['a' => $this->getAuctionId()])
            . ' of account' . composeSuffix(['acc' => $this->getAuction()->AccountId]);
        $this->getLogger()->log($output);
    }
}
