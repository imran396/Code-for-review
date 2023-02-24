<?php
/**
 * Specific for Clerk console handler of SellLots command
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

use AuctionLotItem;
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

/**
 * Handle confirming additional lots sold in group.
 * When we sold grouped of lots (Choice, Qty), system shows "Confirm Additional Lots" dialog.
 * Method marks checked lots as sold and assign winning info to them. It updates rtb group info and current state.
 * @method AuctionLotItem getAuctionLot() - existence checked in checkRunningLot()
 * @method LotItem getLotItem() - existence checked in checkRunningLot()
 */
abstract class AbstractClerkHandler extends CommandBase implements RtbCommandHelperAwareInterface
{
    use DataLoaderCreateTrait;
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
     * Finds next lot. Returns current lot, if there is no next lots.
     * This method should be overridden in each final handler implementation,
     * because its implementation depends on specific to auction type.
     * @param AuctionLotItem $currentAuctionLot
     * @return LotItem|null
     */
    abstract protected function findNextLotItem(AuctionLotItem $currentAuctionLot): ?LotItem;

    /**
     * @param SellLotsCommand $command
     * @return $this
     */
    public function construct(SellLotsCommand $command): AbstractClerkHandler
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
            log_error("Available current bid not found, when selecting grouped lots by clerk console for sell" . composeSuffix($logData));
            return false;
        }

        /**
         * Collect lots that must be sold
         */
        $auctionLots = $this->createDataLoader()->collectAuctionLotsForSale($this->command, $currentAuctionLot);
        if (empty($auctionLots)) {
            log_error(
                'No lots were collected for selling from Clerk console'
                . composeSuffix(['li' => $this->getRtbCurrent()->LotItemId, 'a' => $this->getRtbCurrent()->AuctionId])
            );
            /**
             * Don't exit here. No lots - is "cancel" button click case.
             * "cancel" button presents at Clerk console only.
             */
        }

        /**
         * Winning user is always defined from current bid - at clerk's command side
         */
        $winnerBidUserId = $currentBidTransaction->UserId;
        /**
         * Determine winner user
         */
        $winnerBidUser = $this->getUserLoader()->load($winnerBidUserId);

        $hammerPrice = $this->createValueDetector()->determineHammerPrice($currentAuctionLot, $currentBidTransaction);

        /**
         * Winner user and high bid user is always the same auction bidder in Clerk console case.
         */
        $auctionBidder = $this->getAuctionBidderLoader()->load($winnerBidUserId, $this->getAuctionId(), true);
        $isInternetBid = $this->createValueDetector()->determineIsInternetBid($auctionBidder, $currentBidTransaction);

        /**
         * Update sold lots
         */
        $rtbUpdater = $this->createRtbUpdater();
        $auctionLots = $rtbUpdater->updateLots(
            $this->getRtbCurrent(),
            $auctionLots,
            $winnerBidUser,
            $winnerBidUserId,
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
         * Determine winner info dto
         */
        $winnerInfoDto = $this->createWinnerInfoBuilder()->buildWinnerInfoDto(
            $auctionBidder,
            $currentBidTransaction,
            $winnerBidUserId,
            $this->getAuction()
        );

        /**
         * Log for clerk console side
         */
        $this->log($auctionLots, $hammerPrice, $winnerInfoDto->name);

        /**
         * Perform createResponse() functionality
         */
        $responses = $this->makeResponses($auctionLots, $hammerPrice, $winnerInfoDto);
        $this->setResponses($responses);

        return true;
    }

    /**
     * Performs support log by command performed from clerk console
     * @param array $auctionLots
     * @param float $hammerPrice
     * @param string $bidderStatus
     */
    protected function log(array $auctionLots, float $hammerPrice, string $bidderStatus): void
    {
        $lotNoList = $this->createValueDetector()->buildLotNoList($auctionLots);
        $price = $this->getCurrency() . $hammerPrice;
        $logMessage = $this->getLogger()->getUserRoleName($this->getUserType())
            . " sells group lots {$lotNoList} to {$bidderStatus} for {$price}";
        $this->getLogger()->log($logMessage);
    }

    /**
     * Validate request command availability and its data correctness
     * @return bool
     */
    protected function validate(): bool
    {
        $success = true;
        if (
            !(
                $this->checkConsoleSync()
                && $this->checkRunningLot()
            )
        ) {
            $success = false;
        }

        if (!$success) {
            $rtbCurrent = $this->getRtbCurrent();
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
