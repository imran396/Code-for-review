<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use Sam\Core\Constants;
use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class PlaceBid
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class PlaceBid extends \Sam\Rtb\Command\Concrete\Base\PlaceBid
{
    use HelpersAwareTrait;
    use HybridRtbCommandHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function execute(): void
    {
        // Reject bidding, when auction.allow_bidding_during_start_gap is false
        if (!$this->getAuction()->AllowBiddingDuringStartGap) {
            $rtbCurrent = $this->getRtbCurrent();
            $isInGapTime = $this->getTimeoutHelper()->isInGapTime($rtbCurrent);
            if ($isInGapTime) {
                $message = "Bidding disabled during start gap time";
                $response = [
                    Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_SEND_MESSAGE_S,
                    Constants\Rtb::RES_DATA => [
                        Constants\Rtb::RES_MESSAGE => $this->getRtbGeneralHelper()
                            ->clean('<span class="bid-denied">' . $message . '</span>'),
                    ],
                ];
                $responses[Constants\Rtb::RT_SINGLE] = json_encode($response);
                $this->setResponses($responses);
                $this->getResponseHelper()->applyAdditionalInfo($this);
                return;
            }
        }

        parent::execute();
    }
}
