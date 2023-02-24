<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;

/**
 * Class PauseAuction
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class PauseAuction extends CommandBase implements RtbCommandHelperAwareInterface
{
    use AuctionWriteRepositoryAwareTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbRendererCreateTrait;

    protected string $langAuctionPaused = '';

    /**
     * @param int $accountId
     */
    protected function initTranslations(int $accountId): void
    {
        $this->getTranslator()->setAccountId($accountId);
        $this->langAuctionPaused = $this->translate('BIDDERCLIENT_MSG_AUCPAUSED');
    }

    /**
     */
    public function execute(): void
    {
        if (!$this->checkRunningLot()) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        $this->initTranslations($this->getAuction()->AccountId);
        $this->getAuction()->toPaused();
        $this->getAuctionWriteRepository()->saveWithModifier($this->getAuction(), $this->detectModifierUserId());

        $rtbCurrent = $this->getRtbCurrent();

        /**
         * If we pause auction with already paused lot, then lot should be kept paused (SAM-7651).
         * Otherwise, we should switch lot to the "Idle" state.
         */
        $lotActivity = $rtbCurrent->isPausedLot() ? Constants\Rtb::LA_PAUSED : Constants\Rtb::LA_IDLE;
        $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, $lotActivity, $this->detectModifierUserId());
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());

        $this->createStaticMessages();
        $this->log();
    }

    protected function createResponses(): void
    {
        $responses = [];
        $message = $this->langAuctionPaused;
        $data = [
            Constants\Rtb::RES_STATUS => $message,
            Constants\Rtb::RES_LOT_ACTIVITY => Constants\Rtb::LA_IDLE,
        ];
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_PAUSE_S,
            Constants\Rtb::RES_DATA => $data,
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
            $message
        );

        $this->setResponses($responses);
    }

    protected function createStaticMessages(): void
    {
        $messageHtml = $this->createRtbRenderer()->renderAuctioneerMessage($this->langAuctionPaused, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml);
    }

    /**
     * Log to auction trail
     */
    protected function log(): void
    {
        $message = $this->getLogger()->getUserRoleName($this->getUserType());
        $message .= ' pauses auction' . composeSuffix(['a' => $this->getAuctionId()]);
        $this->getLogger()->log($message);
    }
}
