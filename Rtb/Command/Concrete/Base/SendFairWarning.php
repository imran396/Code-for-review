<?php

namespace Sam\Rtb\Command\Concrete\Base;

use Sam\Core\Constants;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Command\Render\RtbRendererCreateTrait;

/**
 * Class SendFairWarning
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class SendFairWarning extends CommandBase implements RtbCommandHelperAwareInterface
{
    use RtbRendererCreateTrait;

    protected ?string $message = null;

    /**
     * @param string $message
     * @return static
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    public function execute(): void
    {
        if (!$this->checkRunningLot()) {
            $this->getRtbCommandHelper()->createCommand('Sync')->runInContext($this);
            return;
        }

        if ($this->message === null) {
            $langFairWarn = $this->translate('BIDDERCLIENT_MSG_FAIRWARN');
            $this->setMessage($langFairWarn);
        }
        $userRoleName = $this->getLogger()->getUserRoleName($this->getUserType());
        $this->getLogger()->log($userRoleName . " sends fair warning ({$this->message})");

        // Save in static file

        $messageHtml = $this->createRtbRenderer()->renderAuctioneerWarningMessage($this->message, $this->getAuction());
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml, true);
        $this->getMessenger()->createStaticHistoryMessage($this->getAuctionId(), $messageHtml);

        $data = [Constants\Rtb::RES_STATUS => $this->message];
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_FAIR_S,
            Constants\Rtb::RES_DATA => $data
        ];
        $responseJson = json_encode($response);
        if ($this->getUserType() !== Constants\Rtb::UT_CLERK) {
            $responses[Constants\Rtb::RT_CLERK] = $responseJson;
        }
        $responses[Constants\Rtb::RT_BIDDER] = $responseJson;
        $responses[Constants\Rtb::RT_VIEWER] = $responseJson;
        $responses[Constants\Rtb::RT_PROJECTOR] = $responseJson;
        $responses[Constants\Rtb::RT_AUCTIONEER] = $responseJson;

        $userRoleName = '<span style=\'text-decoration:blink;color:#ff0000;\'>' . $this->message . '</span>';
        $responses = $this->getResponseHelper()->addForSimultaneousAuction(
            $responses,
            $this->getSimultaneousAuctionId(),
            $userRoleName
        );

        $this->setResponses($responses);
    }
}
