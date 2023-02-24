<?php

namespace Sam\Rtb\Command\Concrete\Hybrid;

use LotItem;
use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\SellLots\Base\SellLotsCommand;
use Sam\Rtb\Command\Concrete\SellLots\Hybrid\SellLotsHybridClerkHandler;
use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class SellLot
 * @package Sam\Rtb\Command\Concrete\Hybrid
 */
class SellLot extends \Sam\Rtb\Command\Concrete\Base\SellLot
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

    /**
     * @return void
     */
    public function execute(): void
    {
        parent::execute();
        $this->getTimeoutHelper()->updateLotEndDate($this->getRtbCurrent(), $this->detectModifierUserId());
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }

    /**
     * Implements next lot detection for hybrid auction.
     * Return null if no lots were found.
     * @return LotItem|null
     */
    protected function findNextLot(): ?LotItem
    {
        $rtbCurrent = $this->getRtbCurrent();
        $lotItem = $this->getRtbCommandHelper()->findNextLotItem($rtbCurrent);
        $this->isLastLot = $lotItem === null;
        return $lotItem;
    }

    /**
     * Prepare rtb state waiting from bidder (Agent) to select buyer.
     * Define respective responses.
     * Send respective response to consoles
     * @return bool
     */
    protected function continueToSelectBuyerByAgent(): bool
    {
        $rtbCurrent = $this->getRtbCurrent();
        $isContinued = false;
        $auctionId = $rtbCurrent->AuctionId;
        $userId = $this->currentBidTransaction->UserId;
        $sockets = $this->getRtbCommandHelper()->getConnectedUserSockets(
            $this->getRtbDaemon()->clientSockets,
            $userId,
            $auctionId,
            [Constants\Rtb::UT_BIDDER]
        );
        if ($sockets) {
            log_debug("Connected Buyer's Agent found" . composeSuffix(['a' => $auctionId, 'u' => $userId]));
            $isContinued = parent::continueToSelectBuyerByAgent();
        }
        return $isContinued;
    }

    /**
     * Prepare rtb state for choosing grouped lots.
     * Define respective responses.
     * Checks, if necessary user console is connected. Else sell the running lot.
     * @return bool
     */
    protected function continueToSelectGroupedLots(): bool
    {
        $rtbCurrent = $this->getRtbCurrent();
        $auctionId = $rtbCurrent->AuctionId;
        $userId = $this->currentBidTransaction->UserId;
        $isContinued = false;
        $logInfo = composeSuffix(['a' => $rtbCurrent->AuctionId, 'li' => $rtbCurrent->LotItemId, 'u' => $userId]);
        if ($this->isFloor) {
            $sockets = $this->getRtbCommandHelper()->getConnectedUserSockets(
                $this->getRtbDaemon()->clientSockets,
                null,
                $auctionId,
                [Constants\Rtb::UT_CLERK]
            );
            if ($sockets) {
                // Send to Admin console "Select Additional Lots" response
                $isContinued = parent::continueToSelectGroupedLots();
                log_debug("Continue to select grouped lots by admin for floor bidder" . $logInfo);
            }
        } else {
            $sockets = $this->getRtbCommandHelper()->getConnectedUserSockets(
                $this->getRtbDaemon()->clientSockets,
                $userId,
                $auctionId,
                [Constants\Rtb::UT_BIDDER]
            );
            if ($sockets) {
                // Send to Winner Bidder console "Select Additional Lots" response
                $isContinued = parent::continueToSelectGroupedLots();
                log_debug("Continue to select grouped lots by winning bidder" . $logInfo);
            }
        }

        if (!$isContinued) {
            /**
             * We didn't find console, that is responsible for grouped lots choosing.
             * Then sell 1st lot to winner bidder (like Admin clicks "Continue sale").
             * TB: If the bidder is not there, we cannot assume he wants all of the item,
             * but we can be sure he wants at least one of them (since he was placing bids)
             */
            log_debug(
                "Console, that is responsible for grouped lots choosing was not found. "
                . "Continue to sell running lot" . $logInfo
            );
            $handler = SellLotsHybridClerkHandler::new()->construct(
                SellLotsCommand::new()->constructToOnlySellRunningLot()
            );
            $handler->setAuction($this->getAuction());
            $handler->setCurrency($this->getCurrency());
            $handler->setLogger($this->getLogger());
            $handler->setRtbCurrent($this->getRtbCurrent());
            $handler->setRtbDaemon($this->getRtbDaemon());
            $handler->setUserType(Constants\Rtb::UT_CLERK);
            $handler->execute();
            $this->responses = $handler->getResponses();
            unset($handler);

            $isContinued = true;
        }
        return $isContinued;
    }
}
