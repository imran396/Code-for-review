<?php
/**
 * Closing functionality for timed lots
 *
 * Related tickets:
 * SAM-2706: AuctionHQ - extend all with offset
 * SAM-3224: Refactoring of auction_closer.php
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Mar 10, 2015
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Closer;

use AuctionLotItem;
use Exception;
use QCallerException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Validate\AuctionStatusCheckerAwareTrait;
use Sam\AuctionLot\BulkGroup\Load\LotBulkGroupLoaderAwareTrait;
use Sam\AuctionLot\BulkGroup\Save\LotBulkGroupLotStatusUpdaterCreateTrait;
use Sam\AuctionLot\Closer\BulkGroup\LotBulkGroupCloserCreateTrait;
use Sam\AuctionLot\Closer\BulkGroup\PiecemealLot\MultiplePiecemealLotCurrentBidUpdaterCreateTrait;
use Sam\AuctionLot\Closer\BulkGroup\PiecemealLot\SinglePiecemealLotCurrentBidUpdaterCreateTrait;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\Dto\TimedAuctionLotDates;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\AuctionLot\Storage\DataManager;
use Sam\Bidding\AskingBid\AskingBidDetectorCreateTrait;
use Sam\Bidding\BidTransaction\Place\Base\AnyBidSaverCreateTrait;
use Sam\Bidding\BidTransaction\Place\BidDateAwareTrait;
use Sam\Bidding\CurrentBid\Actual\ActualCurrentBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Legacy\Generate\Produce\AutoInvoice\LegacyAutoInvoiceProducerCreateTrait;
use Sam\Invoice\StackedTax\Generate\Produce\AutoInvoice\StackedTaxAutoInvoiceProducerCreateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Feature\StackedTaxFeatureAvailabilityCheckerCreateTrait;
use Sam\Translation\TranslationLanguageProviderCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;

/**
 * Class TimedCloser
 * @package Sam\AuctionLot\Closer
 */
class TimedCloser extends CustomizableClass
{
    use ActualCurrentBidDetectorCreateTrait;
    use AnyBidSaverCreateTrait;
    use AskingBidDetectorCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotDateAssignorCreateTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionStatusCheckerAwareTrait;
    use BidDateAwareTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;
    use FilterAuctionAwareTrait;
    use LegacyAutoInvoiceProducerCreateTrait;
    use LotBulkGroupCloserCreateTrait;
    use LotBulkGroupLoaderAwareTrait;
    use LotBulkGroupLotStatusUpdaterCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use MultiplePiecemealLotCurrentBidUpdaterCreateTrait;
    use SettingsManagerAwareTrait;
    use SinglePiecemealLotCurrentBidUpdaterCreateTrait;
    use StackedTaxAutoInvoiceProducerCreateTrait;
    use StackedTaxFeatureAvailabilityCheckerCreateTrait;
    use TimedItemLoaderAwareTrait;
    use TranslationLanguageProviderCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * @var Storage\DataManager|null
     */
    protected ?Storage\DataManager $dataManager = null;

    /**
     * @var DataManager|null
     */
    protected ?DataManager $auctionLotDataManager = null;

    /**
     * @var int lot count, that should be closed per one loop iteration in self::close()
     */
    protected int $closingLotsPerIteration = 10;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Init instance with defaults, inject dependencies
     * @return static
     */
    public function initInstance(): static
    {
        $this->setDataManager(Storage\DataManager::new());
        $this->setAuctionLotDataManager(DataManager::new());
        return $this;
    }

    /**
     * Setter for main data manager specific for this module
     * @param Storage\DataManager $dataManager
     * @return static
     */
    public function setDataManager(Storage\DataManager $dataManager): static
    {
        $this->dataManager = $dataManager;
        return $this;
    }

    /**
     * Setter for auction lot item data manager
     * @param DataManager $dataManager
     * @return static
     */
    public function setAuctionLotDataManager(DataManager $dataManager): static
    {
        $this->auctionLotDataManager = $dataManager;
        return $this;
    }

    /**
     * Close auction lot
     *
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    public function closeLot(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $isAutoInvoice = (bool)$this->getSettingsManager()->get(Constants\Setting::AUTO_INVOICE, $auctionLot->AccountId);
        $autoInvoiceStatus = $isAutoInvoice ? 'on' : 'off';
        $logSuffix = composeSuffix(
            [
                'li' => $auctionLot->LotItemId,
                'a' => $auctionLot->AuctionId,
                'ali' => $auctionLot->Id,
                'lot#' => $lotNo,
                'auto-invoice' => $autoInvoiceStatus,
            ]
        );
        log_debug("Closing timed lot{$logSuffix}");
        try {
            $this->closeAuctionLot(
                $auctionLot,
                Constants\BidTransaction::TYPE_REGULAR,
                $editorUserId
            );
            $auctionLot->Reload();

            if (
                $isAutoInvoice
                && $auctionLot->isSold()
                && in_array(Constants\Invoice::AIC_REGULAR, $this->cfg()->get('core->invoice->autoInvoicing')->toArray(), true)
            ) {
                log_debug("Creating auto invoice for{$logSuffix}");
                $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
                if (!$lotItem) {
                    log_error(
                        "Available lot item not found, when closing timed lot"
                        . composeSuffix(['li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id])
                    );
                    return;
                }

                if ($this->createStackedTaxFeatureAvailabilityChecker()->isStackedTaxDesignationForInvoice($lotItem->AccountId)) {
                    $language = $this->createTranslationLanguageProvider()->detectLanguage($lotItem->AccountId);
                    $this->createStackedTaxAutoInvoiceProducer()->createAutoInvoice($lotItem, $editorUserId, $language);
                } else {
                    $this->createLegacyAutoInvoiceProducer()->createAutoInvoice($lotItem, $editorUserId);
                }
            }
        } catch (QCallerException $e) {
            // On Load balancing environment due to optimistic locking individual transactions may fail
            log_warning("Failed to close timed lot{$logSuffix}: " . $e->getMessage());
        }
    }

    /**
     * Close an timed auction lot
     *
     * @param AuctionLotItem $auctionLot
     * @param string $closeBidType Constants\TimedLot::Regular, Constants\TimedLot::BuyNow, Constants\TimedLot::Offer
     * @param int $editorUserId
     * @param float|null $price default null
     * @param int|null $userId user.id
     * @throws Exception
     */
    public function closeAuctionLot(
        AuctionLotItem $auctionLot,
        string $closeBidType,
        int $editorUserId,
        ?float $price = null,
        ?int $userId = null
    ): void {
        if (
            $closeBidType !== Constants\BidTransaction::TYPE_OFFER
            && !$auctionLot->isActive()
        ) {
            // Process only if the lot status is active
            return;
        }

        $logData = [
            'li' => $auctionLot->LotItemId,
            'a' => $auctionLot->AuctionId,
            'ali' => $auctionLot->Id,
            'close sign' => $closeBidType,
        ];
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            log_error("Available auction not found, when closing timed lot" . composeSuffix($logData));
            return;
        }
        if (
            $this->getAuctionStatusChecker()->isLotBulkGroupingAvailable($auction)
            && $auctionLot->hasPiecemealRole()
        ) {
            $masterAuctionLot = $this->getAuctionLotLoader()->loadById($auctionLot->BulkMasterId);
            if (!$masterAuctionLot) {
                log_error(
                    "Available auction lot bulk master not found, when closing timed lot"
                    . composeSuffix(['master ali' => $auctionLot->BulkMasterId] + $logData)
                );
                return;
            }
            if ($masterAuctionLot->isActive()) {
                log_debug(
                    'Auction status is a bulk lot. Status shall only be determined when the bulk master lot closed'
                    . composeSuffix($logData)
                );
                return;
            }
        }

        $timedItem = $this->getTimedItemLoader()->loadOrCreate($auctionLot->LotItemId, $auctionLot->AuctionId);
        switch ($closeBidType) {
            //auction closed by time end
            case Constants\BidTransaction::TYPE_REGULAR:
                if (!$timedItem->NoBidding) {
                    $currentBid = $this->createActualCurrentBidDetector()->findForTimed($auctionLot);
                    // Has current bid
                    if ($currentBid) {
                        $isAllowedBulkGroups = $this->getAuctionStatusChecker()->isLotBulkGroupingAvailable($auction);
                        if (
                            $isAllowedBulkGroups
                            && $auctionLot->hasMasterRole()
                            && !$this->isReserveMeet($auctionLot->LotItemId, $currentBid->Bid)
                        ) {
                            /**
                             * If reserve price of bulk group master lot is not met, then close master lot as unsold,
                             * and close piecemeal lots as sold, if reserve price is met, to each lot's individual high bidder,
                             * but if reserve price of piecemeal lot isn't met, then close it as unsold (SAM-6235).
                             */
                            $auctionLot = $this->closeUnsold($closeBidType, $auctionLot, $editorUserId);
                            $this->createLotBulkGroupCloser()->closeBulkGroupPiecemealLotsByMasterAuctionLot($auctionLot, $editorUserId);
                        } elseif (
                            $isAllowedBulkGroups
                            && $auctionLot->hasMasterRole()
                        ) {
                            log_debug('CLOSING BULK MASTER' . composeSuffix(['ali' => $auctionLot->Id]));
                            $totalWinningMaxBid = $this->getLotBulkGroupLoader()->loadBulkGroupTotalWinningMaxBid($auctionLot->Id);
                            $totalWinningBid = $this->getLotBulkGroupLoader()->loadBulkGroupTotalWinningBid($auctionLot->Id);
                            log_debug(
                                composeSuffix(
                                    [
                                        'Bid' => $currentBid->Bid,
                                        'MaxBid' => $currentBid->MaxBid,
                                        'total winning bid' => $totalWinningBid,
                                        'total winning max bid' => $totalWinningMaxBid,
                                    ]
                                )
                            );

                            /*
                             * 1. bulk current bid > sum of piecemeal max bids (excluding piecemeal lots not meeting the reserves)
                             * => master wins at current bid
                             */
                            if (Floating::gt($currentBid->Bid, $totalWinningMaxBid)) {
                                log_debug('BULKMASTER WON at current bid');
                                $this->createLotBulkGroupCloser()->closeLotBulkGroupBy($currentBid, $auctionLot, $editorUserId);
                                $hasPrivilegeForHouseBidder = $this->getBidderPrivilegeChecker()
                                    ->enableReadOnlyDb(true)
                                    ->initByUserId($currentBid->UserId)
                                    ->hasPrivilegeForHouse();
                                $hasPrivilegeForHouseBidder
                                    ? $auctionLot->toUnsold()
                                    : $auctionLot->toSold();    // SAM-2991
                                $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                                log_debug('BULKMASTER status' . composeSuffix(['lot status' => $auctionLot->LotStatusId]));
                                $this->createMultiplePiecemealLotCurrentBidUpdater()->updateByMasterAuctionLotId($auctionLot->Id, $editorUserId);
                                $this->createLotBulkGroupLotStatusUpdater()->updateByMasterAuctionLot($auctionLot, $editorUserId);
                                /*
                                 * 2. bulk max bid > sum of piecemeal max bids (excluding piecemeal lots not meeting the reserves)
                                 * => master wins at sum of piecemeal max bids (excluding piecemeal lots not meeting the reserves) + 1 increment
                                 * */
                            } elseif (Floating::gt($currentBid->MaxBid, $totalWinningMaxBid)) {
                                log_debug('BULKMASTER WON at sum of piecemeal max bids');
                                $bulkBidAmount = $this->createAskingBidDetector()->detectAskingBid(
                                    $auctionLot->LotItemId,
                                    $auctionLot->AuctionId,
                                    $totalWinningMaxBid
                                );
                                try {
                                    $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
                                    if (!$lotItem) {
                                        log_error("Available lot item not found, when closing timed lot" . composeSuffix($logData));
                                        return;
                                    }
                                    $currentBidUser = $this->getUserLoader()->load($currentBid->UserId);
                                    if (!$currentBidUser) {
                                        log_error(
                                            "Available current bid user not found, when closing timed lot"
                                            . composeSuffix(['u' => $currentBid->UserId] + $logData)
                                        );
                                        return;
                                    }
                                    $masterBid = $this->createAnyBidSaver()
                                        ->setAuction($auction)
                                        ->setBidAmount($bulkBidAmount)
                                        ->setBidDateUtc($this->getBidDateUtc())
                                        ->setEditorUserId($editorUserId)
                                        ->setLotItem($lotItem)
                                        ->setMaxBidAmount($currentBid->MaxBid)
                                        ->setUser($currentBidUser)
                                        ->create();

                                    $auctionLot->linkCurrentBid($masterBid->Id);
                                    $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                                    $this->createLotBulkGroupCloser()->closeLotBulkGroupBy($masterBid, $auctionLot, $editorUserId);
                                    $hasPrivilegeForHouseBidder = $this->getBidderPrivilegeChecker()
                                        ->enableReadOnlyDb(true)
                                        ->initByUserId($masterBid->UserId)
                                        ->hasPrivilegeForHouse();
                                    $hasPrivilegeForHouseBidder
                                        ? $auctionLot->toUnsold()
                                        : $auctionLot->toSold();    // SAM-2991
                                    $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                                } catch (Exception) {
                                    log_error('Failed to add bid record for bulk master in order to outbid');
                                    return;
                                }
                                log_debug(
                                    'BULKMASTER status'
                                    . composeSuffix(['lot status' => $auctionLot->LotStatusId])
                                );
                                $this->createMultiplePiecemealLotCurrentBidUpdater()->updateByMasterAuctionLotId($auctionLot->Id, $editorUserId);
                                $this->createLotBulkGroupLotStatusUpdater()->updateByMasterAuctionLot($auctionLot, $editorUserId);
                                /*
                                 * 3. bulk current bid <= bulk max bid <= sum of piecemeal max bids (excluding piecemeal lots not meeting the reserves)
                                 * piecemeal wins at max bid
                                 * */
                            } elseif (
                                Floating::lteq($currentBid->Bid, $currentBid->MaxBid)
                                && Floating::lteq($currentBid->MaxBid, $totalWinningMaxBid)
                            ) {
                                log_debug('PIECEMEAL WON at max bid');
                                $auctionLot = $this->closeUnsold($closeBidType, $auctionLot, $editorUserId);
                                log_debug(
                                    'BULKMASTER status'
                                    . composeSuffix(['lot status' => $auctionLot->LotStatusId])
                                );
                                $this->createSinglePiecemealLotCurrentBidUpdater()
                                    ->setUserId($currentBid->UserId)
                                    ->setAuctionLotId($auctionLot->Id)
                                    ->setMaxBid($currentBid->MaxBid)
                                    ->update($editorUserId);
                                $this->createMultiplePiecemealLotCurrentBidUpdater()->updateByMasterAuctionLotId($auctionLot->Id, $editorUserId);
                                $this->createLotBulkGroupCloser()->closeBulkGroupPiecemealLotsByMasterAuctionLot($auctionLot, $editorUserId);
                            }
                        } else {
                            $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
                            if (!$lotItem) {
                                $logData = ['li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id];
                                log_error("Available lot item not found, when closing timed lot" . composeSuffix($logData));
                                return;
                            }
                            if ((
                                    $lotItem->ReservePrice
                                    && Floating::lteq($lotItem->ReservePrice, $currentBid->Bid)
                                )
                                || !$lotItem->ReservePrice
                            ) {
                                SingleTimedLotCloser::new()->closeSingleLotBy(
                                    $currentBid,
                                    $auctionLot,
                                    null,
                                    Constants\BidTransaction::TYPE_REGULAR,
                                    $editorUserId
                                );
                                $hasPrivilegeForHouseBidder = $this->getBidderPrivilegeChecker()
                                    ->enableReadOnlyDb(true)
                                    ->initByUserId($currentBid->UserId)
                                    ->hasPrivilegeForHouse();
                                $hasPrivilegeForHouseBidder
                                    ? $auctionLot->toUnsold()
                                    : $auctionLot->toSold();    // SAM-2991
                                $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                            } else {
                                $this->closeUnsold($closeBidType, $auctionLot, $editorUserId);
                            }
                        }
                        // No Bid unsold
                    } else {
                        $auctionLot = $this->closeUnsold($closeBidType, $auctionLot, $editorUserId);
                        if (
                            $this->getAuctionStatusChecker()->isLotBulkGroupingAvailable($auction)
                            && $auctionLot->hasMasterRole()
                        ) {
                            //log_debug('BULK LOTS WON');
                            $this->createLotBulkGroupCloser()->closeBulkGroupPiecemealLotsByMasterAuctionLot($auctionLot, $editorUserId);
                        }
                    }
                } else {
                    /*
                     * This is for no bidding items such as buy now and best offer
                     * */
                    $this->closeUnsold($closeBidType, $auctionLot, $editorUserId);
                    return;
                }
                break;

            case Constants\BidTransaction::TYPE_BUY_NOW:
                $hasPrivilegeForHouseBidder = $this->getBidderPrivilegeChecker()
                    ->enableReadOnlyDb(true)
                    ->initByUserId($userId)
                    ->hasPrivilegeForHouse();

                $buyNowBid = null;

                // We don't log house bidder wins
                if (!$hasPrivilegeForHouseBidder) {
                    $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId, true);
                    $user = $this->getUserLoader()->load($userId);
                    $buyNowBid = $this->createAnyBidSaver()
                        ->setAuction($auction)
                        ->setBidAmount($price)
                        ->setBidDateUtc($this->getBidDateUtc())
                        ->setBidType(Constants\BidTransaction::TYPE_BUY_NOW)
                        ->setEditorUserId($editorUserId)
                        ->setLotItem($lotItem)
                        ->setUser($user)
                        ->create();
                }

                SingleTimedLotCloser::new()->closeSingleLotBy(
                    $buyNowBid,
                    $auctionLot,
                    $price,
                    Constants\BidTransaction::TYPE_BUY_NOW,
                    $editorUserId,
                    $userId
                );

                $hasPrivilegeForHouseBidder
                    ? $auctionLot->toUnsold()
                    : $auctionLot->toSold();    // SAM-2991
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                break;

            case Constants\BidTransaction::TYPE_OFFER:
                SingleTimedLotCloser::new()->closeSingleLotBy(
                    null,
                    $auctionLot,
                    $price,
                    Constants\BidTransaction::TYPE_OFFER,
                    $editorUserId,
                    $userId
                );
                $hasPrivilegeForHouseBidder = $this->getBidderPrivilegeChecker()
                    ->enableReadOnlyDb(true)
                    ->initByUserId($userId)
                    ->hasPrivilegeForHouse();
                $hasPrivilegeForHouseBidder
                    ? $auctionLot->toUnsold()
                    : $auctionLot->toSold();    // SAM-2991
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                break;
            default:
                break;
        }
    }

    /**
     * Check current bid meet the reserve or not
     * @param int $lotItemId
     * @param float $currentBid
     * @return bool
     */
    protected function isReserveMeet(int $lotItemId, float $currentBid): bool
    {
        $lotItem = $this->getLotItemLoader()->load($lotItemId, true);
        if (!$lotItem) {
            log_error("Available lot item not found, when checking reserve meet" . composeSuffix(['ali' => $lotItemId]));
            return false;
        }
        if (!$lotItem->ReservePrice) {
            return true;
        }
        return $lotItem->ReservePrice <= $currentBid;
    }

    /**
     * Perform lot closing actions, when lot is closed with Unsold status:
     * - assign Unsold status;
     * - calculate and assign Lot End date;
     * @param string $closeBidType
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     * @return AuctionLotItem
     */
    protected function closeUnsold(string $closeBidType, AuctionLotItem $auctionLot, int $editorUserId): AuctionLotItem
    {
        $endDateDetector = EndDateDetector::new()
            ->setCloseBidType($closeBidType)
            ->setAuctionLot($auctionLot);
        $auctionLotDates = TimedAuctionLotDates::new()->setEndDate($endDateDetector->detectLotEndDate());
        $this->createAuctionLotDateAssignor()->assignForTimed($auctionLot, $auctionLotDates, $editorUserId);
        $auctionLot->toUnsold();
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        return $auctionLot;
    }

    /**
     * Close lots considering execution time limit
     *
     * @param int $editorUserId
     * @param int|null $maxExecTime execution time limit in seconds, null - no limit
     */
    public function close(int $editorUserId, ?int $maxExecTime = null): void
    {
        $execStartTime = time();
        do {
            $iterationStartTime = time();
            $auctionLots = $this->dataManager
                ->setBidDateUtc($this->getBidDateUtc())
                ->getUnclosedTimedLots([$this->getFilterAuctionId()], $this->closingLotsPerIteration);
            if (empty($auctionLots)) {
                break;
            }
            $this->transactionBegin();
            $auctionLotIds = $lotInfo = [];
            foreach ($auctionLots as $auctionLot) {
                $auctionLotIds[] = $auctionLot->Id;
                $lotInfo[] = ['ali' => $auctionLot->Id, 'li' => $auctionLot->LotItemId, 'a' => $auctionLot->AuctionId];
            }
            $logData = ['count' => count($auctionLots), 'lots' => $lotInfo];
            log_info('Timed lots ready for close' . composeSuffix($logData));
            $this->auctionLotDataManager->lockInTransactionByIds($auctionLotIds);
            foreach ($auctionLots as $auctionLot) {
                $this->closeLot($auctionLot, $editorUserId);
            }
            $this->transactionCommit();
            $lastIterationTime = time() - $iterationStartTime;

            $expirationTime = $execStartTime + $maxExecTime - $lastIterationTime;
            $shouldContinueLoop = $maxExecTime === null
                || time() < $expirationTime;
        } while ($shouldContinueLoop);
    }

    /**
     * Set lot count, that should be closed per one loop iteration in self::close()
     *
     * @param int $count
     * @return static
     * @noinspection PhpUnused
     */
    public function setClosingLotsPerIteration(int $count): static
    {
        $this->closingLotsPerIteration = $count;
        return $this;
    }
}
