<?php
/**
 * SAM-11182: Extract timed lot bidding logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\TimedBid\Place;

use Auction;
use AuctionLotItem;
use BidTransaction;
use Exception;
use LotItem;
use QMySqli5Database;
use QMySqliDatabaseException;
use RuntimeException;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Lock\AuctionLotLockerCreateTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\Outstanding\BidderOutstandingHelper;
use Sam\Bidding\AskingBid\AskingBidDetectorCreateTrait;
use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Bidding\BidTransaction\Place\Base\AnyBidSaverCreateTrait;
use Sam\Bidding\BidTransaction\Place\BidDateAwareTrait;
use Sam\Bidding\ReservePrice\ReservePriceSimpleCheckerAwareTrait;
use Sam\Bidding\TimedBid\Notify\TimedBidNotifierAwareTrait;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Bidding\RegularBid\RegularBidPureChecker;
use Sam\Core\Bidding\StartingBid\StartingBidPureChecker;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

class TimedBidSaver extends CustomizableClass
{
    use AnyBidSaverCreateTrait;
    use AskingBidDetectorCreateTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionLotLockerCreateTrait;
    use BidDateAwareTrait;
    use BidIncrementLoaderAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use CurrencyLoaderAwareTrait;
    use DbConnectionTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use MemoryCacheManagerAwareTrait;
    use NumberFormatterAwareTrait;
    use ReservePriceSimpleCheckerAwareTrait;
    use TimedBidNotifierAwareTrait;
    use TranslatorAwareTrait;
    use UserLoaderAwareTrait;

    /** @var bool */
    protected bool $isTranslated = true;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Place bid for timed lot
     *
     * @param User $user Bidder, who place the bid
     * @param Auction $auction
     * @param int $lotItemId
     * @param float $amount Bid amount, it sense depends on bid type
     * @param int $editorUserId
     * @param string|null $bidType Bid type (Regular, BuyNow, Offer, ForceBid), null means regular bid by default.
     * @param float|null $visibleAskingBid Asking bid, which is visible for bidder (may differ to actual asking bid, SAM-1065). null means use actual asking bid.
     * @param string $httpReferrer
     * @param bool $shouldNotifyUsers Set to false for skipping high/outbid users and consignor notifications
     * @return BidTransaction returned object is guaranteed, else we throw exception, when bid amount doesn't meet actual or visible asking bids, or when lot is closed
     * @throws QMySqliDatabaseException
     */
    public function placeTimedBid(
        User $user,
        Auction $auction,
        int $lotItemId,
        float $amount,
        int $editorUserId,
        ?string $bidType = null,
        ?float $visibleAskingBid = null,
        string $httpReferrer = '',
        bool $shouldNotifyUsers = true
    ): BidTransaction {
        if ($bidType === null) {
            $bidType = Constants\BidTransaction::TYPE_REGULAR;
        }
        $forceBidQuantized = null; // quantized force bid
        $forceMaxBid = null;       // force bid amount
        $newMaxBid = null;
        $shouldSendToWinner = true;
        $winnerBidTransaction = null;
        $looserBid = null;
        $looserUser = null;
        $isReverse = $auction->Reverse;
        $lotItem = $this->getLotItemLoader()->load($lotItemId);
        $regularBidPureChecker = RegularBidPureChecker::new();
        if (!$lotItem) {
            throw new RuntimeException(
                "Active lot item not found for timed bid place"
                . composeSuffix(['li' => $lotItemId])
            );
        }
        $hasReservePrice = Floating::gt($lotItem->ReservePrice, 0);
        $db = $this->getDb();

        if ($bidType === Constants\BidTransaction::TYPE_FORCE_BID) {
            $forceMaxBid = $amount;
            $forceBidQuantized = $this->createAskingBidDetector()->detectQuantizedBid(
                $amount,
                false,
                $lotItem->Id,
                $auction->Id,
                $lotItem->StartingBid
            );
        } else {
            $newMaxBid = $amount;
        }

        // Check outstanding limit (SAM-2710)
        $auctionBidder = $this->getAuctionBidderLoader()->load($user->Id, $auction->Id, true);
        if (
            $auctionBidder
            && BidderOutstandingHelper::new()->isLimitExceeded($auctionBidder)
        ) {
            throw new RuntimeException($this->translateOutstandingLimitExceeded($auction->AccountId));
        }

        // start the transaction outside try-catch (makes no sense to rollback a failed begin)
        $db->transactionBegin();

        try {
            // ali record should be locked at beginning of transaction to prevent race condition
            // and we should perform lock check before we select this record inside transaction (See SAM-2001)
            $this->createAuctionLotLocker()->lockInTransaction($lotItemId, $auction->Id);
            // Invalidate memory cached records, that might became stale during locking of the same lot in separate bidding process
            $this->getMemoryCacheManager()->clear();

            $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auction->Id);
            if (
                !$auctionLot
                || !$auctionLot->isActive()
            ) {
                throw new RuntimeException($this->translateLotClosedBidLate($auctionLot));
            }

            // lock auction_lot_item_cache record used for bidding
            \Sam\AuctionLot\Cache\Storage\DataManager::new()->lockInTransaction($auctionLot->Id);

            // lock entries used for lot end date auto-extending
            $lotEndDateExtender = \Sam\AuctionLot\EndDateExtender\Service::new()
                ->setAuction($auction)
                ->setAuctionLot($auctionLot)
                ->setBidDateUtc($this->getBidDateUtc())
                ->setEditorUserId($editorUserId);
            $lotEndDateExtender->lockInTransactionProcessingEntries();

            // SAM-1065, we moved asking bid checking inside this method
            $askingBid = $this->createAskingBidDetector()->detectAskingBid($lotItemId, $auction->Id);
            if ($visibleAskingBid === null) {
                $visibleAskingBid = $askingBid;
            }

            if ($bidType === Constants\BidTransaction::TYPE_FORCE_BID) {    //check the ForceBid Amount instead of the newMaxBid
                $forceMaxBid = (float)$forceMaxBid; // JIC
                $hasBidMetAskingBid = $regularBidPureChecker->checkBidToAskingBid($forceMaxBid, $askingBid, $auction);
                $hasBidMetVisibleAskingBid = $regularBidPureChecker->checkBidToAskingBid($forceMaxBid, $visibleAskingBid, $auction);
            } else {
                $newMaxBid = (float)$newMaxBid; // JIC
                $hasBidMetAskingBid = $regularBidPureChecker->checkBidToAskingBid($newMaxBid, $askingBid, $auction);
                $hasBidMetVisibleAskingBid = $regularBidPureChecker->checkBidToAskingBid($newMaxBid, $visibleAskingBid, $auction);
            }

            if (
                !$hasBidMetAskingBid
                && !$hasBidMetVisibleAskingBid
            ) {    // bid didn't meet actual asking bid
                throw new RuntimeException($this->translateBidTooSmall($auction));
            }

            log_debug(
                'Timed bid place process start'
                . composeSuffix(['li' => $lotItem->Id, 'a' => $auction->Id, 'bt' => $auctionLot->CurrentBidId])
            );

            if ($bidType === Constants\BidTransaction::TYPE_BUY_NOW) {
                $winnerBidTransaction = $this->createAnyBidSaver()
                    ->setAuction($auction)
                    ->setBidAmount($newMaxBid)
                    ->setBidDateUtc($this->getBidDateUtc())
                    ->setBidType(Constants\BidTransaction::TYPE_BUY_NOW)
                    ->setEditorUserId($editorUserId)
                    ->setHttpReferrer($httpReferrer)
                    ->setLotItem($lotItem)
                    ->setMaxBidAmount($newMaxBid)
                    ->setUser($user)
                    ->create();
                log_debug('Timed won: Buy now' . composeSuffix(['bt' => $winnerBidTransaction->Id]));
            }

            if ($bidType === Constants\BidTransaction::TYPE_OFFER) {
                $winnerBidTransaction = $this->createAnyBidSaver()
                    ->setAuction($auction)
                    ->setBidAmount($newMaxBid)
                    ->setBidDateUtc($this->getBidDateUtc())
                    ->setBidType(Constants\BidTransaction::TYPE_OFFER)
                    ->setEditorUserId($editorUserId)
                    ->setHttpReferrer($httpReferrer)
                    ->setLotItem($lotItem)
                    ->setMaxBidAmount($newMaxBid)
                    ->setUser($user)
                    ->create();
                log_debug(
                    'Timed won: Best offer'
                    . composeSuffix(['bt' => $winnerBidTransaction->Id])
                );
            }

            if (in_array($bidType, [Constants\BidTransaction::TYPE_REGULAR, Constants\BidTransaction::TYPE_FORCE_BID], true)) {
                if ($bidType === Constants\BidTransaction::TYPE_FORCE_BID) {
                    log_debug('Placing force bid' . composeSuffix(['Force bid' => $forceMaxBid]));
                } else {
                    log_debug('Placing regular bid' . composeSuffix(['Max bid' => $newMaxBid]));
                }

                //Getting current bid
                log_debug('Loading last active bid transaction' . composeSuffix(['li' => $lotItem->Id, 'a' => $auction->Id]));
                $currentBidTransaction = $this->createBidTransactionLoader()->loadLastActiveBid($lotItem->Id, $auction->Id);
                if (!$currentBidTransaction) {
                    log_debug('Has no current bid');

                    if ($bidType === Constants\BidTransaction::TYPE_FORCE_BID) {
                        $newMaxBid = $forceMaxBid;
                    }

                    /*
                     * There is no current bid so we should:
                     * 1) accept entered bid as max bid
                     * 2) put starting bid as current bid no matter if entered bid amount is equal or higher then starting bid
                     */
                    $reservePriceSimpleChecker = $this->getReservePriceSimpleChecker();
                    $isStartingBidMeetReservePrice = $reservePriceSimpleChecker->meet($lotItem->StartingBid, $lotItem->ReservePrice, $isReverse);
                    $isNewMaxBidMeetReservePrice = $reservePriceSimpleChecker->meet($newMaxBid, $lotItem->ReservePrice, $isReverse);

                    if (
                        $bidType === Constants\BidTransaction::TYPE_REGULAR
                        && $hasReservePrice
                        && !$isStartingBidMeetReservePrice
                        && $isNewMaxBidMeetReservePrice
                    ) {
                        $startingBid = $lotItem->ReservePrice;
                    } elseif ($bidType === Constants\BidTransaction::TYPE_FORCE_BID) {
                        $forceBidQuantized = (float)$forceBidQuantized; // JIC
                        $isMetForceBid = StartingBidPureChecker::new()->meet($forceBidQuantized, (float)$lotItem->StartingBid, $isReverse);
                        $startingBid = $isMetForceBid ? $forceBidQuantized : $lotItem->StartingBid;
                    } elseif ($lotItem->StartingBid) {
                        $startingBid = $lotItem->StartingBid;
                    } else {
                        $startingBid = $this->createAskingBidDetector()->detectAskingBid($lotItemId, $auction->Id);
                    }

                    /*
                     * For undefined starting bid in reverse auction, we set it the same as the first bid
                     */
                    if (
                        $isReverse
                        && $startingBid === null
                    ) {
                        $startingBid = $newMaxBid;
                    }

                    $startingBidWithReserved = $this->detectQuantizedForEnglishReserve($amount, $auction, $lotItem);
                    if ($startingBidWithReserved) {
                        $startingBid = $startingBidWithReserved;
                    }

                    $winnerBidTransaction = $this->createAnyBidSaver()
                        ->setAuction($auction)
                        ->setBidAmount($startingBid)
                        ->setBidDateUtc($this->getBidDateUtc())
                        ->setBidType($bidType)
                        ->setEditorUserId($editorUserId)
                        ->setHttpReferrer($httpReferrer)
                        ->setLotItem($lotItem)
                        ->setMaxBidAmount($newMaxBid)
                        ->setUser($user)
                        ->create();
                    log_debug('Timed won' . composeSuffix(['bt' => $winnerBidTransaction->Id]));
                } else {
                    log_debug('Has current bid');

                    //Max bid for this lot in current auction
                    $lotMaxBid = (float)$currentBidTransaction->MaxBid;

                    if ($hasReservePrice) {
                        log_debug('Has reserve price');

                        /*
                         * we should put reserve price value into the current lot
                         * in case bid placed exceeds or equal to it for the first time
                         */
                        if ($bidType === Constants\BidTransaction::TYPE_REGULAR) {
                            $isNewMaxBidMeetReserve = $this->getReservePriceSimpleChecker()
                                ->meet($newMaxBid, $lotItem->ReservePrice, $isReverse);
                            $isOldMaxBidMeetReserve = $this->getReservePriceSimpleChecker()
                                ->meet($currentBidTransaction->MaxBid, $lotItem->ReservePrice, $isReverse);
                            if (
                                $isNewMaxBidMeetReserve
                                && !$isOldMaxBidMeetReserve
                            ) {
                                $isAskingBidMeetReserve = $this->getReservePriceSimpleChecker()
                                    ->meet($askingBid, $lotItem->ReservePrice, $isReverse);
                                // SAM-5737: For scenario where current bid amount < reserve < asking amount, the system shall take asking, not reserve
                                if ($isAskingBidMeetReserve) {
                                    $newCurrentBid = $askingBid;
                                    log_debug('Setting new current bid from Asking Bid' . composeSuffix(['bid' => $newCurrentBid]));
                                } else {
                                    $newCurrentBid = $lotItem->ReservePrice;
                                    log_debug('Setting new current bid from Reserve Price' . composeSuffix(['bid' => $newCurrentBid]));
                                }
                            }
                        } else {
                            $isForceBidQuantizedMeetReserve = $this->getReservePriceSimpleChecker()
                                ->meet($forceBidQuantized, $lotItem->ReservePrice, $isReverse);
                            $isForceMaxBidMeetReserve = $this->getReservePriceSimpleChecker()
                                ->meet($forceMaxBid, $lotItem->ReservePrice, $isReverse);
                            if (
                                !$isForceBidQuantizedMeetReserve
                                && $isForceMaxBidMeetReserve
                            ) {
                                $forceBidQuantized = $lotItem->ReservePrice;
                                log_debug('Setting new force current bid from Reserve Price' . composeSuffix(['bid' => $forceBidQuantized]));
                            }
                        }
                    }

                    if ($user->Id === $currentBidTransaction->UserId) { // input bid has been placed by current high bidder

                        if ($bidType === Constants\BidTransaction::TYPE_FORCE_BID) {
                            $forceMaxBid = (float)$forceMaxBid; // JIC
                            // own quantized force bid should meet current bid
                            $isSuccess = $regularBidPureChecker->checkBidBeatBidInTimedAuction((float)$forceBidQuantized, (float)$currentBidTransaction->Bid, $isReverse);
                            if (!$isSuccess) {
                                throw new RuntimeException($this->translateBidTooSmall($auction));
                            }

                            $newCurrentBid = $forceBidQuantized;
                            $newMaxBid = $regularBidPureChecker->checkBidBeatBidInTimedAuction($lotMaxBid, $forceMaxBid, $isReverse)
                                ? $lotMaxBid
                                : $forceMaxBid;
                        } else {
                            // own regular bid should meet current max bid
                            $isSuccess = $regularBidPureChecker->checkBidToCurrentMaxBid((float)$newMaxBid, (float)$currentBidTransaction->MaxBid, $auction);
                            if (!$isSuccess) {
                                throw new RuntimeException($this->translateYourMaxBid($currentBidTransaction->MaxBid, $auction));
                            }

                            if (!isset($newCurrentBid)) {
                                $newCurrentBid = $currentBidTransaction->Bid;
                            }
                        }

                        $newCurrentBidReserved = $this->detectQuantizedForEnglishReserve($amount, $auction, $lotItem);
                        if ($newCurrentBidReserved) {
                            $newCurrentBid = $newCurrentBidReserved;
                        }

                        if ($bidType === Constants\BidTransaction::TYPE_FORCE_BID) {
                            log_debug('Force bid from ' . $currentBidTransaction->Bid . ' to ' . $newCurrentBid);
                        } else {
                            log_debug(
                                ($isReverse ? 'Decreasing' : 'Increasing')
                                . ' max bid from ' . $currentBidTransaction->MaxBid . ' to ' . $newMaxBid
                            );
                        }

                        $winnerBidTransaction = $this->createAnyBidSaver()
                            ->setAuction($auction)
                            ->setBidAmount($newCurrentBid)
                            ->setBidDateUtc($this->getBidDateUtc())
                            ->setEditorUserId($editorUserId)
                            ->setHttpReferrer($httpReferrer)
                            ->setLotItem($lotItem)
                            ->setMaxBidAmount($newMaxBid)
                            ->setParentBidTransaction($currentBidTransaction)
                            ->setUser($user)
                            ->create();

                        log_debug('Timed won' . composeSuffix(['bt' => $winnerBidTransaction->Id]));
                    } else { //new bid placed by user that is not current high bidder

                        if ($bidType === Constants\BidTransaction::TYPE_FORCE_BID) {
                            $newMaxBid = $forceMaxBid;
                        }

                        // FIX SAM-713:
                        $tempNewMaxBid = $this->createAskingBidDetector()->detectQuantizedBid(
                            $newMaxBid,
                            false,
                            $lotItem->Id,
                            $auction->Id,
                            $lotItem->StartingBid
                        );

                        //checking if current max bid is less than new placed bid
                        $isTempNewMaxBidOk = $regularBidPureChecker->checkBidBeatBidInTimedAuction($tempNewMaxBid, $lotMaxBid, $isReverse);
                        if ($isTempNewMaxBidOk) { //if ($newMaxBid > $currentBid->MaxBid) {

                            log_debug('Outbid current max bid ' . $lotMaxBid . ' by new max bid ' . $newMaxBid);

                            $looserUser = $this->getUserLoader()->load($currentBidTransaction->UserId);

                            if (!isset($newCurrentBid)) {
                                $lotIncrement = $this->getBidIncrementLoader()->loadAvailable(
                                    $lotMaxBid,
                                    $auction->AccountId,
                                    Constants\Auction::TIMED,
                                    $auction->Id,
                                    $lotItemId
                                );
                                $increment = $lotIncrement->Increment ?? 0.;

                                if ($isReverse) {
                                    $increment *= -1;
                                }
                                $asking = $lotMaxBid + $increment;

                                log_debug(
                                    "DB maxbid: " . $lotMaxBid . " + Bid increment: " . $increment
                                    . " = asking bid: " . $asking
                                );

                                if ($bidType === Constants\BidTransaction::TYPE_FORCE_BID) {
                                    $isNewMaxBidOk = $isReverse ? Floating::lt($forceMaxBid, $asking)
                                        : Floating::gt($forceMaxBid, $asking);
                                } else {
                                    $isNewMaxBidOk = $isReverse ? Floating::lt($newMaxBid, $asking)
                                        : Floating::gt($newMaxBid, $asking);
                                }

                                if ($isNewMaxBidOk) { // new max bid > db maxbid + increment

                                    if ($bidType === Constants\BidTransaction::TYPE_FORCE_BID) {
                                        $newCurrentBid = $forceBidQuantized;
                                    } else {
                                        $newCurrentBid = $this->createAskingBidDetector()->detectQuantizedBid(
                                            $asking,
                                            false,
                                            $lotItemId,
                                            $auction->Id,
                                            $lotItem->StartingBid
                                        );
                                    }

                                    $comparisonOperator = $isReverse ? '<' : '>';
                                    log_debug(
                                        "New max bid {$newMaxBid}{$comparisonOperator}{$asking}"
                                        . " maxbid on db + increment = new quantize current bid: " . $newCurrentBid
                                    );
                                } else { // new max bid < db maxbid + increment

                                    $newCurrentBid = $this->createAskingBidDetector()->detectQuantizedBid(
                                        $newMaxBid,
                                        false,
                                        $lotItemId,
                                        $auction->Id,
                                        $lotItem->StartingBid
                                    );

                                    $comparisonOperator = $isReverse ? '>' : '<';
                                    log_debug(
                                        "New max bid {$newMaxBid}{$comparisonOperator}{$asking}"
                                        . " maxbid on db + increment = new quantize current bid: {$newCurrentBid}"
                                    );
                                }
                            }

                            $newCurrentBidReserved = $this->detectQuantizedForEnglishReserve($amount, $auction, $lotItem);
                            if ($newCurrentBidReserved) {
                                $newCurrentBid = $newCurrentBidReserved;
                            }

                            log_debug('Accepting bid at ' . $newCurrentBid . ' max bid ' . $newMaxBid);

                            $winnerBidTransaction = $this->createAnyBidSaver()
                                ->setAuction($auction)
                                ->setBidAmount($newCurrentBid)
                                ->setBidDateUtc($this->getBidDateUtc())
                                ->setBidType($bidType)
                                ->setEditorUserId($editorUserId)
                                ->setHttpReferrer($httpReferrer)
                                ->setLotItem($lotItem)
                                ->setMaxBidAmount($newMaxBid)
                                ->setOutbidTransaction($currentBidTransaction)
                                ->setUser($user)
                                ->create();

                            log_debug(
                                'Timed won' . composeSuffix(
                                    [
                                        'bt' => $winnerBidTransaction->Id,
                                        'u' => $winnerBidTransaction->UserId,
                                    ]
                                )
                            );
                            $looserBid = $currentBidTransaction->MaxBid;
                        } else {
                            //current Max Bid is equal or higher than bid placed

                            $newCurrentBid = $this->createAskingBidDetector()->detectQuantizedBid(
                                $newMaxBid,
                                false,
                                $lotItem->Id,
                                $auction->Id,
                                $lotItem->StartingBid
                            );

                            if (
                                !$hasBidMetAskingBid
                                && $hasBidMetVisibleAskingBid // @phpstan-ignore-line
                            ) {    // Failed bid = bid meet visible asking bid and didn't meet actual asking bid

                                log_debug(
                                    'New placed bid ' . $newMaxBid . ' less than current max bid ' . $lotMaxBid
                                    . ' and considered as failed'
                                );

                                $this->createAnyBidSaver()
                                    ->enableBidFailed(true)
                                    ->setAuction($auction)
                                    ->setBidAmount($newCurrentBid)
                                    ->setBidDateUtc($this->getBidDateUtc())
                                    ->setBidStatus(Constants\BidTransaction::BS_FAILED)
                                    ->setEditorUserId($editorUserId)
                                    ->setHttpReferrer($httpReferrer)
                                    ->setLotItem($lotItem)
                                    ->setMaxBidAmount($newMaxBid)
                                    ->setUser($user)
                                    ->create();
                                $db->transactionCommit();
                                throw new RuntimeException($this->translateBidFailedTooSmall($auction));
                            }

                            log_debug(
                                'New placed bid ' . $newMaxBid . ' less than current max bid ' . $lotMaxBid
                                . ' and is outbid'
                            );

                            /** we should accept new bid placed as bid that already loosed
                             * because we already have bid higher than it */
                            //getting value of new bid accepted

                            $winnerUser = $this->getUserLoader()->load($currentBidTransaction->UserId);
                            $looserUser = $user;

                            $looserBidTransaction = $this->createAnyBidSaver()
                                ->setAuction($auction)
                                ->setBidAmount($newCurrentBid)
                                ->setBidDateUtc($this->getBidDateUtc())
                                ->setBidStatus(Constants\BidTransaction::BS_LOOSER)
                                ->setEditorUserId($editorUserId)
                                ->setHttpReferrer($httpReferrer)
                                ->setLotItem($lotItem)
                                ->setMaxBidAmount($newMaxBid)
                                ->setOutbidTransaction($currentBidTransaction)
                                ->setUser($user)
                                ->create();
                            log_debug(
                                'Auto placed bid ' . $newMaxBid
                                . '; Loosing bid id ' . $looserBidTransaction->Id
                            );

                            // Reload avoiding memory cache for reducing race condition probability, SAM-4179
                            $auctionLot->Reload();
                            $auctionLot->linkCurrentBid($looserBidTransaction->Id);
                            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);

                            $looserBid = $looserBidTransaction->Bid;
                            $currentBidTransaction = $looserBidTransaction;

                            if (Floating::eq($currentBidTransaction->Bid, $lotMaxBid)) {
                                $newCurrentBid = $currentBidTransaction->Bid;
                            } else {
                                //getting minimal value from current Max bid and new bid with increment
                                //Checking if the new bid is not incremental base and don't reset it if so
                                $asking = $this->createAskingBidDetector()->detectAskingBid($lotItemId, $auction->Id);
                                $isAskingOk = $isReverse
                                    ? Floating::lt($asking, $lotMaxBid)
                                    : Floating::gt($asking, $lotMaxBid);
                                if ($isAskingOk) {
                                    $newCurrentBid = $currentBidTransaction->Bid;
                                } else {
                                    $newCurrentBid = $this->createAskingBidDetector()->detectQuantizedBid(
                                        $asking,
                                        false,
                                        $lotItem->Id,
                                        $auction->Id,
                                        $lotItem->StartingBid
                                    );
                                }
                            }

                            if (!$winnerUser) {
                                log_error(
                                    "Available winner user not found"
                                    . composeSuffix(['u' => $currentBidTransaction->UserId])
                                );
                            } else {
                                //set winner bid
                                $winnerBidTransaction = $this->createAnyBidSaver()
                                    ->setAuction($auction)
                                    ->setBidAmount($newCurrentBid)
                                    ->setBidDateUtc($this->getBidDateUtc())
                                    ->setEditorUserId($editorUserId)
                                    ->setHttpReferrer($httpReferrer)
                                    ->setLotItem($lotItem)
                                    ->setMaxBidAmount($lotMaxBid)
                                    ->setOutbidTransaction($looserBidTransaction)
                                    ->setUser($winnerUser)
                                    ->create();
                                log_debug('Timed won' . composeSuffix(['bt' => $winnerBidTransaction->Id]));
                                $shouldSendToWinner = false;
                            }
                        }
                    }
                }
            }

            // Reload avoiding memory cache for reducing race condition probability, SAM-4179
            $auctionLot->Reload();
            $auctionLot->linkCurrentBid($winnerBidTransaction->Id);
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
            log_debug(
                'Saving new current bid id to auction lot'
                . composeSuffix(
                    [
                        'bt' => $winnerBidTransaction->Id,
                        'li' => $auctionLot->LotItemId,
                        'a' => $auctionLot->AuctionId,
                        'ali' => $auctionLot->Id,
                    ]
                )
            );

            $lotEndDateExtender->extend();    // Should be inside bidding transaction (SAM-2001)

            $db->transactionCommit();

            if ($shouldNotifyUsers) {
                if ($shouldSendToWinner) {
                    if (!isset($winnerUser)) {
                        $winnerUser = $user;
                    }

                    $this->getTimedBidNotifier()->notifyWinnerByEmail(
                        $lotItem,
                        $auction,
                        $winnerBidTransaction,
                        $winnerUser,
                        (float)$newMaxBid,
                        $editorUserId
                    );
                    $this->getTimedBidNotifier()->notifyConsignorWinnerBidByEmail(
                        $lotItem,
                        $auction,
                        $winnerBidTransaction,
                        $winnerUser,
                        (float)$newMaxBid,
                        $editorUserId
                    );
                }

                if ($looserUser) {
                    $this->getTimedBidNotifier()->notifyOutbidBidderByEmail(
                        $lotItem,
                        $auction,
                        $winnerBidTransaction,
                        $looserUser,
                        (float)$looserBid,
                        $editorUserId
                    );
                    $this->getTimedBidNotifier()->notifyOutbidBidderBySms($looserUser, $auctionLot, $editorUserId);
                }

                $this->getTimedBidNotifier()->notifyConsignorByEmail($lotItem, $auction, $winnerBidTransaction, $editorUserId);
            }

            return $winnerBidTransaction;
        } catch (Exception $e) {
            $message = $e->getMessage() . composeSuffix(["Stack trace" => $e->getTraceAsString()]);
            log_error('Caught exception: ' . $message . "\n");
            $db->transactionRollback();

            if ($e instanceof QMySqliDatabaseException) {
                if ($e->ErrorNumber === QMySqli5Database::ER_LOCK_WAIT_TIMEOUT) {
                    // Error: 1205 SQLSTATE: HY000 (ER_LOCK_WAIT_TIMEOUT)
                    // Message: Lock wait timeout exceeded; try restarting transaction
                    throw new RuntimeException($this->translateBidTimeout($auction->AccountId));
                }

                if ($e->ErrorNumber === QMySqli5Database::ER_LOCK_DEADLOCK) {
                    // Error: 1213 SQLSTATE: 40001 (ER_LOCK_DEADLOCK)
                    // Message: Deadlock found when trying to get lock; try restarting transaction
                    throw new RuntimeException($this->translateBidFailed($auction->AccountId));
                }
            }
            throw $e;
        }
    }


    /**
     * This feature affects running "reserve price".
     * It is enabled by Auction->TakeMaxBidsUnderReserve.
     *
     * SAM-3369 English Reserve
     *
     * @param float $amount
     * @param Auction $auction
     * @param LotItem $lotItem
     * @return float|null
     * @link https://bidpath.atlassian.net/browse/SAM-6680
     * @link https://bidpath.atlassian.net/browse/SAM-3369
     */
    protected function detectQuantizedForEnglishReserve(float $amount, Auction $auction, LotItem $lotItem): ?float
    {
        $newCurrentBid = null;
        $hasReservePrice = Floating::gt($lotItem->ReservePrice, 0);
        if (
            !$auction->TakeMaxBidsUnderReserve
            || !$hasReservePrice
        ) {
            return null;
        }
        /* https://bidpath.atlassian.net/browse/SAM-6680
         * According Tom's comment:
         * The rule was always supposed to be, - in this case no matter whether "Take Max bid Under Reserve" enabled,
         * or not -, and even if “Require on increments bid” enabled, if the asking (and as a result the max bid)
         * is below the reserve and the reserve is off-increment and the user places an amount at or above the reserve,
         * it should take the reserve. It’s an exception to “Require on increment bid”.
         */
        $isAmountMetReservePrice = $this->getReservePriceSimpleChecker()
            ->meet($amount, $lotItem->ReservePrice, $auction->Reverse);
        if (!$isAmountMetReservePrice) {
            $amountQuantized = $this->createAskingBidDetector()->detectQuantizedBid(
                $amount,
                false,
                $lotItem->Id,
                $auction->Id,
                $lotItem->StartingBid
            );
            $newCurrentBid = Floating::gt($amountQuantized, 0) ? $amountQuantized : null;
        }
        return $newCurrentBid;
    }

    // --- some special to placeTimedBid() functions that will be extracted to separate class on refactoring ---

    /**
     * Enable/disable translation
     * @param bool $enabled
     * @return $this
     */
    public function enableTranslation(bool $enabled): static
    {
        $this->isTranslated = $enabled;
        return $this;
    }

    /**
     * Return error message when input bid doesn't meet max bid
     * @param float $maxBid
     * @param Auction $auction
     * @return string
     */
    protected function translateYourMaxBid(float $maxBid, Auction $auction): string
    {
        $langYourMaxbidWarn = $this->isTranslated
            ? $this->getTranslator()
                ->translateByAuctionReverse('GENERAL_YOUR_MAXBID', 'general', $auction->Reverse, $auction->AccountId)
            : "Your maximum bid is";
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($auction->Id);
        $maxBidFormatted = $this->getNumberFormatter()->formatMoney($maxBid, $auction->AccountId);
        $output = $langYourMaxbidWarn . ' ' . $currencySign . $maxBidFormatted;
        return $output;
    }

    /**
     * Return translation "Too small amount"
     * @param Auction $auction
     * @return string
     */
    protected function translateBidTooSmall(Auction $auction): string
    {
        $output = $this->isTranslated
            ? $this->getTranslator()
                ->translateByAuctionReverse('CATALOG_BID_TOOSMALL', 'catalog', $auction->Reverse, $auction->AccountId)
            : "Too small amount";
        return $output;
    }

    /**
     * Return translation "Too small amount (bid failed)"
     * @param Auction $auction
     * @return string
     */
    protected function translateBidFailedTooSmall(Auction $auction): string
    {
        $output = $this->isTranslated
            ? $this->getTranslator()
                ->translateByAuctionReverse('CATALOG_BID_FAILED_TOOSMALL', 'catalog', $auction->Reverse, $auction->AccountId)
            : "Too small amount (bid failed)";
        return $output;
    }

    /**
     * Return translation "Sorry, Lot(s) %s already closed, your bid(s) came in too late."
     * @param AuctionLotItem $auctionLot
     * @return string
     */
    protected function translateLotClosedBidLate(AuctionLotItem $auctionLot): string
    {
        $langLotClosed = $this->isTranslated
            ? $this->getTranslator()->translate('GENERAL_LOT_CLOSED_BID_LATE', 'general', $auctionLot->AccountId)
            : "Sorry, Lot(s) %s already closed, your bid(s) came in too late.";
        $output = sprintf($langLotClosed, $this->getLotRenderer()->renderLotNo($auctionLot));
        return $output;
    }

    /**
     * Return translation "Outstanding limit exceeded"
     * @param int $accountId
     * @return string
     */
    protected function translateOutstandingLimitExceeded(int $accountId): string
    {
        $output = $this->isTranslated
            ? $this->getTranslator()->translate('GENERAL_OUTSTANDING_LIMIT_EXCEEDED', 'general', $accountId)
            : "Outstanding limit exceeded";
        return $output;
    }

    /**
     * Return translation "We're sorry! Your bid attempt timed out. Please try again"
     * @param int $accountId
     * @return string
     */
    protected function translateBidTimeout(int $accountId): string
    {
        $output = $this->isTranslated
            ? $this->getTranslator()->translate('GENERAL_BID_TIMEOUT', 'general', $accountId)
            : "We're sorry! Your bid attempt timed out. Please try again";
        return $output;
    }

    /**
     * Return translation "Sorry your bidding attempt failed. Please try again"
     * @param int $accountId
     * @return string
     */
    protected function translateBidFailed(int $accountId): string
    {
        $output = $this->isTranslated
            ? $this->getTranslator()->translate('GENERAL_BID_FAILED', 'general', $accountId)
            : "Sorry your bidding attempt failed. Please try again";
        return $output;
    }
}
