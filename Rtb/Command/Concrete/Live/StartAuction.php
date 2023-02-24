<?php

namespace Sam\Rtb\Command\Concrete\Live;

use Sam\Rtb\Live\HelpersAwareTrait;
use Sam\Core\Constants;
use Sam\Rtb\Command\Helper\Live\LiveRtbCommandHelperAwareTrait;

/**
 * Class StartAuction
 * @package Sam\Rtb\Command\Concrete\Live
 */
class StartAuction extends \Sam\Rtb\Command\Concrete\Base\StartAuction
{
    use HelpersAwareTrait;
    use LiveRtbCommandHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     */
    public function execute(): void
    {
        parent::execute();

        if ($this->isRunning()) {
            $rtbCurrent = $this->getRtbCurrent();
            $rtbCurrent = $this->getRtbCommandHelper()->activateLot($rtbCurrent, Constants\Rtb::LA_BY_AUTO_START, $this->detectModifierUserId());
            $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $this->detectModifierUserId());
        }

        $this->createStaticMessages();
        $this->log();
    }

    /**
     * Check if running lot (rtb_current.lot_item_id) can be played
     * @return bool
     */
    protected function isPlayableRunningLot(): bool
    {
        $rtbCurrent = $this->getRtbCurrent();
        $isPlayable = true;
        if (!$rtbCurrent->LotItemId) {
            $isPlayable = false;
        }
        if ($rtbCurrent->LotItemId) {
            $auctionLot = $this->getAuctionLot();
            $isPlayable = !$auctionLot->isAmongClosedStatuses();
        }
        return $isPlayable;
    }
}
