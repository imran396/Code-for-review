<?php
/**
 * SAM-10452: Decouple HelpersAwareTrait to rtb modules for v3-7
 */

namespace Sam\Rtb\Command\Helper\Live;

use AuctionLotItem;
use RtbCurrent;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\RtbCurrent\Status\RtbCurrentStatusPureChecker;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Helper\Base\AbstractRtbCommandHelper;
use Sam\Rtb\Live\HelpersAwareTrait;

class LiveRtbCommandHelper extends AbstractRtbCommandHelper
{
    use HelpersAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Is overridden in Hybrid\CommandHelper
     * @param RtbCurrent $rtbCurrent
     * @param int $lotActivity
     * @param int $editorUserId
     * @return RtbCurrent
     */
    public function activateLot(RtbCurrent $rtbCurrent, int $lotActivity, int $editorUserId): RtbCurrent
    {
        if ($rtbCurrent->LotItemId) {
            if ($lotActivity === Constants\Rtb::LA_BY_AUTO_START) {
                $lotActivity = $rtbCurrent->AutoStart
                    ? Constants\Rtb::LA_STARTED
                    : Constants\Rtb::LA_IDLE;
            }
            $rtbCurrentStatusPureChecker = RtbCurrentStatusPureChecker::new();
            if ($rtbCurrentStatusPureChecker->isIdleLot($lotActivity)) {
                $rtbCurrent->toIdleLot();
            } elseif ($rtbCurrentStatusPureChecker->isStartedLot($lotActivity)) {
                $rtbCurrent->toStartedLot();
            } elseif ($rtbCurrentStatusPureChecker->isPausedLot($lotActivity)) {
                $rtbCurrent->toPausedLot();
            }
            if ($rtbCurrent->isStartedOrPausedLot()) {
                $this->initAuctionLotStartDate($rtbCurrent->LotItemId, $rtbCurrent->AuctionId, $editorUserId);
            }
        } else { // JIC
            $rtbCurrent->toIdleLot();
        }
        return $rtbCurrent;
    }

    /**
     * Return Live command instance by name
     * @param string $name
     * @return CommandBase
     */
    public function createCommand(string $name): CommandBase
    {
        $instance = $this->createCommandByTypeAndName(Constants\Auction::LIVE, $name);
        return $instance;
    }

    /**
     * Is overridden in Hybrid\CommandHelper
     * @param RtbCurrent $rtbCurrent
     * @param bool $onlyActive
     * @return AuctionLotItem|null
     */
    public function findNextAuctionLot(
        RtbCurrent $rtbCurrent,
        bool $onlyActive = false
    ): ?AuctionLotItem {
        $lotItemId = $rtbCurrent->LotItemId;
        $auctionId = $rtbCurrent->AuctionId;
        $lotStatuses = $onlyActive
            ? [Constants\Lot::LS_ACTIVE]
            : [Constants\Lot::LS_ACTIVE, Constants\Lot::LS_UNSOLD];
        $auctionLot = $this->getPositionalAuctionLotLoader()->loadNextLot(
            $auctionId,
            $lotItemId,
            true,
            ['lotStatuses' => $lotStatuses]
        );
        if (!$auctionLot) {
            $lotStatuses = $onlyActive
                ? [Constants\Lot::LS_ACTIVE]
                : [Constants\Lot::LS_ACTIVE, Constants\Lot::LS_UNSOLD];
            $auctionLot = $this->getPositionalAuctionLotLoader()
                ->loadFirstLot($auctionId, $lotStatuses);
        }
        return $auctionLot;
    }
}
