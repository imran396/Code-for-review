<?php
/**
 * SAM-10452: Decouple HelpersAwareTrait to rtb modules for v3-7
 */

namespace Sam\Rtb\Command\Helper\Hybrid;

use AuctionLotItem;
use RtbCurrent;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\RtbCurrent\Status\RtbCurrentStatusPureChecker;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Helper\Base\AbstractRtbCommandHelper;
use Sam\Rtb\Hybrid\HelpersAwareTrait;

class HybridRtbCommandHelper extends AbstractRtbCommandHelper
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
            if (
                array_key_exists('LotActive', $rtbCurrent->__Modified)
                || array_key_exists('LotItemId', $rtbCurrent->__Modified)
            ) {
                if ($rtbCurrent->isStartedLot()) {
                    $rtbCurrent->LotEndDate = $this->getTimeoutHelper()->calcLotEndDate($rtbCurrent);
                    $rtbCurrent->PauseDate = null;
                } else {
                    $rtbCurrent->PauseDate = $this->getCurrentDateUtc();
                }
            }
            if ($rtbCurrent->isStartedOrPausedLot()) {
                $this->initAuctionLotStartDate($rtbCurrent->LotItemId, $rtbCurrent->AuctionId, $editorUserId);
            }
        } else {    // JIC
            $rtbCurrent->toIdleLot();
        }
        return $rtbCurrent;
    }

    /**
     * Return Hybrid command instance by name
     * @param string $name
     * @return CommandBase
     */
    public function createCommand(string $name): CommandBase
    {
        $instance = $this->createCommandByTypeAndName(Constants\Auction::HYBRID, $name);
        return $instance;
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @param bool $isOkNextUnsold
     * @param bool $onlyActive
     * @return AuctionLotItem|null
     */
    public function findNextAuctionLot(
        RtbCurrent $rtbCurrent,
        bool $isOkNextUnsold = true,
        bool $onlyActive = false
    ): ?AuctionLotItem {
        $lotItemId = $rtbCurrent->LotItemId;
        $auctionId = $rtbCurrent->AuctionId;
        $lotStatuses = [Constants\Lot::LS_ACTIVE];

        $auctionLots = $this->getPositionalAuctionLotLoader()->loadNextLots(
            $auctionId,
            $lotItemId,
            ['limit' => 1, 'lotStatuses' => $lotStatuses]
        );

        $auctionLot = current($auctionLots);
        if (!$auctionLot) {
            $auctionLot = $this->getPositionalAuctionLotLoader()->loadFirstLot($auctionId, $lotStatuses);
        }
        return $auctionLot;
    }
}
