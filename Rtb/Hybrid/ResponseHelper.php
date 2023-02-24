<?php

namespace Sam\Rtb\Hybrid;

use AuctionLotItem;
use RtbCurrent;
use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\Base\CommandBase;

/**
 * Class ResponseHelper
 * @package Sam\Rtb\Hybrid
 */
class ResponseHelper extends \Sam\Rtb\Base\ResponseHelper
{
    use TimeoutHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Adjust Apply additional (Hybrid) info to command's response
     * @param CommandBase $command
     * @return CommandBase
     */
    public function applyAdditionalInfo(CommandBase $command): CommandBase
    {
        $data = $this->getAdditionalResponseData($command->getRtbCurrent(), $command->getAuctionLot());
        $responses = $command->getResponses();
        unset($responses[Constants\Rtb::RT_AUCTIONEER]);
        $responses = $this->addData($responses, $data);
        $command->setResponses($responses);
        return $command;
    }

    /**
     * Return response data special for Hybrid auctions
     * @param RtbCurrent $rtbCurrent
     * @param AuctionLotItem|null $auctionLot
     * @return array
     *
     * TODO: separate data by console type
     * 'Order', 'NowTs': Bidder, Viewer. No need for Admin
     */
    public function getAdditionalResponseData(RtbCurrent $rtbCurrent, AuctionLotItem $auctionLot = null): array
    {
        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when building additional hybrid response"
                . composeSuffix(['a' => $rtbCurrent->AuctionId])
            );
            return [];
        }
        $secondsLeft = $this->getTimeoutHelper()->calcSecondsBeforeLotEnd($rtbCurrent);
        $data = [];
        if ($secondsLeft !== null) {
            $orderNum = $auctionLot->Order ?? 1;
            $data = [
                Constants\Rtb::RES_RUNNING_LOT_SECOND_LEFT => $secondsLeft,
                Constants\Rtb::RES_EXTEND_TIME => $rtbCurrent->ExtendTime,
                Constants\Rtb::RES_LOT_START_GAP_TIME => $rtbCurrent->LotStartGapTime,
                Constants\Rtb::RES_IS_ALLOW_BIDDING_DURING_START_GAP => $auction->AllowBiddingDuringStartGap,
                Constants\Rtb::RES_RUNNING_INTERVAL => $rtbCurrent->RunningInterval,
                Constants\Rtb::RES_LOT_ACTIVITY => $rtbCurrent->LotActive,
                Constants\Rtb::RES_RUNNING_LOT_ORDER_NUM => $orderNum,    // Running Lot Order Number
                Constants\Rtb::RES_NOW_TS => $this->getCurrentDateUtc()->getTimestamp(),
            ];
        }
        return $data;
    }
}
