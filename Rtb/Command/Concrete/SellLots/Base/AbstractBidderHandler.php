<?php
/**
 * Specific for Bidder console handler of SellLots command.
 * Bidder's console has more logic and validations than Clerk's console.
 * Because we need to consider agent/buyer sale of grouped lots.
 *
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Base;

use AuctionBidder;
use AuctionLotItem;
use BidTransaction;
use LotItem;
use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Command\Concrete\SellLots\Base\Internal\Load\DataLoaderCreateTrait;
use Sam\Rtb\Command\Concrete\SellLots\Base\Internal\Response\ResponseBuilderCreateTrait;
use Sam\Rtb\Command\Concrete\SellLots\Base\Internal\Save\RtbUpdaterCreateTrait;
use Sam\Rtb\Command\Concrete\SellLots\Base\Internal\Detect\ValueDetectorCreateTrait;
use Sam\Rtb\Command\Concrete\SellLots\Base\Internal\WinnerInfo\WinnerInfoBuilderCreateTrait;
use Sam\Rtb\Command\Concrete\SellLots\Base\Internal\WinnerInfo\WinnerInfoDto;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperAwareInterface;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeChecker;

/**
 * Handle confirming additional lots sold in group.
 * When we sold grouped of lots (Choice, Qty), system shows "Confirm Additional Lots" dialog.
 * Method marks checked lots as sold and assign winning info to them. It updates rtb group info and current state.
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class AbstractBidderHandler extends CommandBase implements RtbCommandHelperAwareInterface
{
    use DataLoaderCreateTrait;
    use GroupingHelperAwareTrait;
    use ResponseBuilderCreateTrait;
    use RtbUpdaterCreateTrait;
    use ValueDetectorCreateTrait;
    use WinnerInfoBuilderCreateTrait;

    // Externally defined properties

    /**
     * Describes command options and values defined by request data.
     */
    protected SellLotsCommand $command;

    // Internally defined properties

    /**
     * When this handler completes selling of selected grouped lots is complete, we detects new running lot (see, impl. of findNextLotItem()).
     * Btw, currently running lot may be kept running, if it wasn't selected in Choice-grouping.
     * @var bool true - when new running lot is the last active in auction.
     */
    protected bool $isLastLot = false;

    /**
     * Finds next lot
     * @param AuctionLotItem $currentAuctionLot
     * @return LotItem|null
     */
    abstract protected function findNextLotItem(AuctionLotItem $currentAuctionLot): ?LotItem;

    /**
     * @param SellLotsCommand $command
     * @return $this
     */
    public function construct(SellLotsCommand $command): AbstractBidderHandler
    {
        $this->command = $command;
        return $this;
    }

    public function execute(): void
    {
        if (!$this->validate()) {
            $this->getRtbCommandHelper()
                ->createCommand('Sync')
                ->runInContext($this);
            return;
        }

        $this->sell();
    }

    /**
     * @return bool
     */
    protected function sell(): bool
    {
        $currentAuctionLot = $this->getAuctionLot();
        $currentBidTransaction = $this->createBidTransactionLoader()->loadById($currentAuctionLot->CurrentBidId);
        if (!$currentBidTransaction) {
            $logData = [
                'bt' => $currentAuctionLot->CurrentBidId,
                'li' => $currentAuctionLot->LotItemId,
                'a' => $currentAuctionLot->AuctionId,
                'ali' => $currentAuctionLot->Id,
            ];
            log_error("Available current bid not found, when selecting grouped lots by bidder console for sell" . composeSuffix($logData));
            return false;
        }

        /**
         * Collect lots that must be sold
         */
        $auctionLots = $this->createDataLoader()->collectAuctionLotsForSale($this->command, $currentAuctionLot);
        if (empty($auctionLots)) {
            /**
             * Exit, because we shouldn't perform any action if there are no lots to sell.
             * There is no "cancel" button at Bidder console.
             */
            log_error('No lots were collected for selling from Bidder console');
            return false;
        }

        /**
         * Winning user may be redefined to RtbCurrent->GroupUser, when it is pending action - at bidder's command side.
         * Winning user not necessary placed the bid. He may be buyer who was selected by agent user who actually has placed the winning bid.
         */
        $winnerUserId = $this->detectWinnerUserId($currentBidTransaction);
        /**
         * Determine winner user
         */
        $winnerUser = $this->getUserLoader()->load($winnerUserId);
        if (!$winnerUser) {
            $logData = [
                'u' => $winnerUserId,
                'cb' => $currentBidTransaction->Id,
                'GU' => $this->getRtbCurrent()->GroupUser
            ];
            log_error('Available winner user not found, when selling selected lots from bidder console' . composeSuffix($logData));
            return false;
        }

        $auctionBidderLoader = $this->getAuctionBidderLoader();
        /**
         * AuctionBidder for current high bid placed by agent or simple bidder
         */
        $highBidAuctionBidder = $auctionBidderLoader->load($currentBidTransaction->UserId, $this->getAuctionId(), true);
        if (!$highBidAuctionBidder) {
            log_error(
                'Available auction bidder for winning bid user not found, when selling selected lots from bidder console'
                . composeSuffix(['u' => $currentBidTransaction->UserId, 'a' => $this->getAuction()])
            );
            return false;
        }

        /**
         * Drop winnerUserId when current bid transaction is from floor bidder
         */
        $winnerUserIdSanitized = $this->sanitizeWinnerUserId($winnerUserId, $highBidAuctionBidder, $currentBidTransaction);

        $hammerPrice = $this->createValueDetector()->determineHammerPrice($currentAuctionLot, $currentBidTransaction);

        $isInternetBid = $this->createValueDetector()->determineIsInternetBid($highBidAuctionBidder, $currentBidTransaction);

        /**
         * Update sold lots
         */
        $rtbUpdater = $this->createRtbUpdater();
        $auctionLots = $rtbUpdater->updateLots(
            $this->getRtbCurrent(),
            $auctionLots,
            $winnerUser,
            $winnerUserIdSanitized,
            $hammerPrice,
            $isInternetBid,
            $this->detectModifierUserId(),
            $this->getAuction()->AccountId,
            $this->getViewLanguageId()
        );

        /**
         * Update rtb state
         */
        $currentAuctionLot->Reload();
        $rtbUpdater->prepareGroupedLots($this->getRtbCurrent(), $currentAuctionLot, $this->detectModifierUserId());
        // Next method is overridden in each final handler implementation
        $nextLotItem = $this->findNextLotItem($currentAuctionLot);
        if ($nextLotItem) {
            $rtbUpdater->updateRtbState(
                $this->getRtbCurrent(),
                $nextLotItem,
                $this->getRtbCommandHelper(),
                $this->detectModifierUserId(),
                $this->getAuction()->AccountId,
                $this->getViewLanguageId()
            );
        }

        /**
         * Determine winner info dto and winner's AuctionBidder for it (buyer)
         */
        $isWinnerOwnerOfHighBid = $winnerUserId === $currentBidTransaction->UserId;
        $winnerAuctionBidder = $highBidAuctionBidder;
        if (!$isWinnerOwnerOfHighBid) {
            $winnerAuctionBidder = $auctionBidderLoader->load($winnerUserId, $this->getAuctionId(), true);
        }
        $winnerInfoDto = $this->createWinnerInfoBuilder()->buildWinnerInfoDto(
            $winnerAuctionBidder,
            $currentBidTransaction,
            $winnerUserId,
            $this->getAuction()
        );

        /**
         * Log for bidder console side, determines seller's name (agent)
         */
        $sellerName = $winnerInfoDto->name;
        if (!$isWinnerOwnerOfHighBid) {
            /**
             * When winner differs from owner of actual winning bid, this means,
             * that seller is agent user who own winning bid.
             */
            $sellerName = $this->createWinnerInfoBuilder()->makeBidderName($highBidAuctionBidder, $this->getAuction());
        }
        $this->log($auctionLots, $hammerPrice, $winnerInfoDto->name, $sellerName);

        /**
         * Perform createResponse() functionality
         */
        $responses = $this->makeResponses($auctionLots, $hammerPrice, $winnerInfoDto);
        $this->setResponses($responses);

        return true;
    }

    /**
     * Performs support log by command performed from bidder console
     * @param array $auctionLots
     * @param float $hammerPrice
     * @param string $winnerStatus winner
     * @param string $sellerStatus
     */
    protected function log(array $auctionLots, float $hammerPrice, string $winnerStatus, string $sellerStatus): void
    {
        $lotNoList = $this->createValueDetector()->buildLotNoList($auctionLots);
        $price = $this->getCurrency() . $hammerPrice;
        $logMessage = "Bidder {$sellerStatus} sells group lots {$lotNoList} to {$winnerStatus} for {$price}";
        $this->getLogger()->log($logMessage);
        $this->getLogger()->log("Bidder {$winnerStatus} buys group lots {$lotNoList} for price: {$price}");
    }

    /**
     * 1. In RtbCurrent->GroupUser we pass winning user id, who should select lots from the group.
     * 2. Also it handles the case, when GroupUser reference was redefined from agent to his buyer.
     * Then "Select Additional Lots" dialog will be displayed at buyer's console.
     * Agent's console loses "Select Additional Lots" dialog on reload.
     * @param BidTransaction $currentBidTransaction
     * @return int|null
     */
    protected function detectWinnerUserId(BidTransaction $currentBidTransaction): ?int
    {
        $currentBidUserId = $currentBidTransaction->UserId;
        $rtbCurrent = $this->getRtbCurrent();
        if (
            $rtbCurrent->LotGroup
            && $rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_GROUPED_LOTS
            && $rtbCurrent->GroupUser
        ) {
            $currentBidUserId = $rtbCurrent->GroupUser;
        }
        return $currentBidUserId;
    }

    /**
     * It filters out input of $winnerBidUserId, if it is floor bid or there is no bid amount, or auction bidder is lost.
     * @param int|null $winnerBidUserId
     * @param AuctionBidder|null $auctionBidder
     * @param BidTransaction $currentBidTransaction
     * @return int|null
     */
    protected function sanitizeWinnerUserId(
        ?int $winnerBidUserId,
        ?AuctionBidder $auctionBidder,
        BidTransaction $currentBidTransaction
    ): ?int {
        if (
            $auctionBidder
            && !$currentBidTransaction->FloorBidder
            && $currentBidTransaction->Bid > 0
        ) {
            return $winnerBidUserId;
        }
        // Idk, how we could get into this case
        return null;
    }

    /**
     * Validate request command availability and its data correctness
     * @return bool
     */
    protected function validate(): bool
    {
        $success = false;
        $rtbCurrent = $this->getRtbCurrent();
        if (
            $rtbCurrent->PendingAction === Constants\Rtb::PA_SELECT_GROUPED_LOTS
            && $rtbCurrent->PendingActionDate
            && $rtbCurrent->GroupUser
        ) {
            if ($rtbCurrent->GroupUser === $this->getEditorUserId()) {      // I'm winning bidder
                $success = true;
            } else {
                $bidderPrivilegeChecker = BidderPrivilegeChecker::new()
                    ->enableReadOnlyDb(true)
                    ->initByUserId($this->getEditorUserId());
                $isAgentOfBuyer = $bidderPrivilegeChecker->isAgentOfBuyer($rtbCurrent->GroupUser);
                if ($isAgentOfBuyer) {
                    $success = true;
                }
            }
        }

        if (
            $success
            && !(
                $this->checkConsoleSync()
                && $this->checkRunningLot()
            )
        ) {
            $success = false;
        }

        if (!$success) {
            $pendingActionDate = $rtbCurrent->PendingActionDate
                ? $rtbCurrent->PendingActionDate->format(Constants\Date::ISO) : '';
            $logInfo = composeSuffix(
                [
                    'a' => $rtbCurrent->AuctionId,
                    'li' => $rtbCurrent->LotItemId,
                    'ut' => $this->getUserType(),
                    'u' => $this->getEditorUserId(),
                    'gu' => $rtbCurrent->GroupUser,
                    'pa' => $rtbCurrent->PendingAction,
                    'pad' => $pendingActionDate,
                ]
            );
            log_warning("Unexpected command {$logInfo}");
        }

        return $success;
    }
    /**
     * --- Responses --- ----------------------------------------------------------------------------------
     */

    /**
     * Responses are the same for command initiated from bidder and clerk consoles
     * @param array $auctionLots
     * @param float $hammerPrice
     * @param WinnerInfoDto $winnerInfoDto
     * @return array
     */
    protected function makeResponses(
        array $auctionLots,
        float $hammerPrice,
        WinnerInfoDto $winnerInfoDto
    ): array {
        $responseBuilder = $this->createResponseBuilder()->construct(
            $this->getAuction(),
            $this->getRtbCurrent(),
            $this->getAuction()->AccountId,
            $this->getResponseHelper()
        );

        $responses = $responseBuilder->makeResponses(
            $auctionLots,
            $hammerPrice,
            $winnerInfoDto,
            $this->isLastLot,
            $this->getSimultaneousAuctionId()
        );

        return $responses;
    }
}
