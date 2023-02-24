<?php
/**
 * Lot bulk group feature is available for timed auction lot only
 *
 * SAM-3224: Refactoring of auction_closer.php
 * https://bidpath.atlassian.net/browse/SAM-3224
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 23, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Closer\BulkGroup;

use AuctionLotItem;
use BidTransaction;
use Sam\AuctionLot\BulkGroup\Calculate\LotBulkGroupPiecemealHpCalculatorCreateTrait;
use Sam\AuctionLot\BulkGroup\Load\LotBulkGroupLoaderAwareTrait;
use Sam\AuctionLot\Closer\LotCloseNotifier;
use Sam\AuctionLot\Closer\SingleTimedLotCloser;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeChecker;

/**
 * Class LotBulkGroupCloser
 * @package Sam\AuctionLot\Closer
 */
class LotBulkGroupCloser extends CustomizableClass
{
    use AuctionLotAwareTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use CurrentDateTrait;
    use LotBulkGroupLoaderAwareTrait;
    use LotBulkGroupPiecemealHpCalculatorCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use TimedItemLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @var BidTransaction|null $winnerBid
     */
    protected ?BidTransaction $winnerBid = null;
    /**
     * @var bool
     */
    protected bool $forceUnsold = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return BidTransaction|null
     */
    public function getWinnerBid(): ?BidTransaction
    {
        return $this->winnerBid;
    }

    /**
     * @param BidTransaction $winnerBid
     * @return static
     */
    public function setWinnerBid(BidTransaction $winnerBid): static
    {
        $this->winnerBid = $winnerBid;
        return $this;
    }

    /**
     * @return bool
     */
    public function isForceUnsold(): bool
    {
        return $this->forceUnsold;
    }

    /**
     * @param bool $forceUnsold
     * @return static
     */
    public function enabledForceUnsold(bool $forceUnsold): static
    {
        $this->forceUnsold = $forceUnsold;
        return $this;
    }

    /**
     * Close lot bulk group with master lot
     * @param BidTransaction $winnerBid
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     * @return void
     */
    public function closeLotBulkGroupBy(BidTransaction $winnerBid, AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $this->setAuctionLot($auctionLot)
            ->setWinnerBid($winnerBid)
            ->closeLotBulkGroup($editorUserId);
    }

    /**
     * @param bool $isForceUnsold
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     * @return void
     */
    public function closeBulkGroupPiecemealLotsByMasterAuctionLot(
        AuctionLotItem $auctionLot,
        int $editorUserId,
        bool $isForceUnsold = false
    ): void {
        $this->setAuctionLot($auctionLot)
            ->enabledForceUnsold($isForceUnsold)
            ->closeBulkGroupPiecemealLots($editorUserId);
    }

    /**
     * @param int $editorUserId
     * @return void
     */
    public function closeBulkGroupPiecemealLots(int $editorUserId): void
    {
        $piecemealAuctionLots = $this->getLotBulkGroupLoader()->loadPiecemealAuctionLots($this->getAuctionLotId());
        foreach ($piecemealAuctionLots as $piecemealAuctionLot) {
            $piecemealLotItem = $this->getLotItemLoader()->load($piecemealAuctionLot->LotItemId);
            if (!$piecemealLotItem) {
                $logData = [
                    'li' => $piecemealAuctionLot->LotItemId,
                    'a' => $this->getAuctionLot()->AuctionId,
                    'master ali' => $this->getAuctionLotId(),
                ];
                log_error('Available lot item member not found, when closing bulk group items' . composeSuffix($logData));
                continue;
            }
            $piecemealCurrentBid = $this->createBidTransactionLoader()->loadById($piecemealAuctionLot->CurrentBidId);
            if ((
                    $piecemealCurrentBid
                    && ((
                            $piecemealLotItem->ReservePrice
                            && Floating::lteq($piecemealLotItem->ReservePrice, $piecemealCurrentBid->Bid)
                        )
                        || !$piecemealLotItem->ReservePrice
                    )
                )
                && !$this->isForceUnsold()
            ) {
                SingleTimedLotCloser::new()->closeSingleLotBy(
                    $piecemealCurrentBid,
                    $piecemealAuctionLot,
                    null,
                    Constants\BidTransaction::TYPE_REGULAR,
                    $editorUserId
                );

                $hasPrivilegeForHouseBidder = BidderPrivilegeChecker::new()
                    ->enableReadOnlyDb(true)
                    ->initByUserId($piecemealCurrentBid->UserId)
                    ->hasPrivilegeForHouse();
                $hasPrivilegeForHouseBidder
                    ? $piecemealAuctionLot->toUnsold()
                    : $piecemealAuctionLot->toSold();    // SAM-2991
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($piecemealAuctionLot, $editorUserId);
            } else {
                $piecemealAuctionLot->toUnsold(); // Immediately set the status to unsold
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($piecemealAuctionLot, $editorUserId);
            }
        }
    }

    /**
     * @param int $editorUserId
     * @return void
     */
    public function closeLotBulkGroup(int $editorUserId): void
    {
        $auctionLot = $this->getAuctionLot();
        if (!$auctionLot) {
            log_error(
                "Available auction lot not found, when closing lot bulk group"
                . composeSuffix(['ali' => $this->getAuctionLotId()])
            );
            return;
        }
        log_debug('BULKMASTER status' . composeSuffix(['ls' => $auctionLot->LotStatusId]));

        // Reload this first to make sure the lot item has not yet been closed
        $auctionLot->Reload();
        if (!$auctionLot->isActive()) {
            return;
        }

        $bulkMasterLotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
        $logData = ['li' => $auctionLot->LotItemId, 'a' => $auctionLot->AuctionId, 'ali' => $this->getAuctionLotId()];
        if (!$bulkMasterLotItem) {
            log_error('Available lot item not found, when closing bulk lot data' . composeSuffix($logData));
            return;
        }

        $winnerBid = $this->getWinnerBid();
        if (!$winnerBid) {
            log_error("Winner bid transaction absent, when closing bulk lot data" . composeSuffix($logData));
            return;
        }

        $bulkLotItemIds = [$bulkMasterLotItem->Id];
        $winningUser = $this->getUserLoader()->load($winnerBid->UserId, true);
        if (!$winningUser) {
            $logData = ['u' => $winnerBid->UserId] + $logData;
            log_error('Available winning bidder user not found, when closing bulk lot data' . composeSuffix($logData));
            return;
        }
        $dateSold = $this->getCurrentDateUtc();

        $bulkMasterHp = $bulkPieceHp = null;
        $bulkPieceLots = $this->getLotBulkGroupLoader()->loadPiecemealAuctionLots($this->getAuctionLotId());

        $hasPrivilegeForHouseBidder = BidderPrivilegeChecker::new()
            ->enableReadOnlyDb(true)
            ->initByUserId($winningUser->Id)
            ->hasPrivilegeForHouse();

        log_debug(
            'BULKMASTER win bid distribution'
            . composeSuffix(['bwbd' => $auctionLot->BulkMasterWinBidDistribution])
        );
        switch ($auctionLot->BulkMasterWinBidDistribution) {
            case Constants\LotBulkGroup::BMWBD_MASTER:
                $bulkMasterHp = $winnerBid->Bid;
                $bulkPieceHp = 0;
                break;

            case Constants\LotBulkGroup::BMWBD_WINNING:
            case Constants\LotBulkGroup::BMWBD_EQUALLY:
                $bulkMasterHp = 0;
                break;
        }

        $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
        $bulkMasterLotItem->assignSoldInfo(
            $auctionLot->AuctionId, // Should also update sale/auction where it has been sold
            $dateSold,
            $auctionLot->multiplyQuantityEffectively($bulkMasterHp, $quantityScale),
            true,
            $winningUser->Id
        );

        // SAM-2991: wipe out sold info of bulk master lot, if winner is House Bidder
        if ($hasPrivilegeForHouseBidder) {
            $bulkMasterLotItem->wipeOutSoldInfo();
        }
        $this->getLotItemWriteRepository()->saveWithModifier($bulkMasterLotItem, $editorUserId);

        log_debug('BULKMASTER' . composeSuffix($bulkMasterLotItem->logDataForSellInfo()));

        if (!$hasPrivilegeForHouseBidder) {
            LotCloseNotifier::new()
                ->setAuctionLot($this->getAuctionLot())
                ->setLotItem($bulkMasterLotItem)
                ->setUser($winningUser)
                ->notifyLotWon($editorUserId);
        }

        $piecemealLotHpCalculator = $this->createLotBulkGroupPiecemealHpCalculator()
            ->construct($auctionLot, $winnerBid->Bid, $bulkPieceLots);
        $piecemealLotHpCalculator->calculate();

        foreach ($bulkPieceLots as $bulkPieceLot) {
            $bulkPieceLotItem = $this->getLotItemLoader()->load($bulkPieceLot->LotItemId, true);
            if (!$bulkPieceLotItem) {
                $logData = [
                    'li' => $bulkPieceLot->LotItemId,
                    'a' => $this->getAuctionLot()->AuctionId,
                    'master ali' => $this->getAuctionLotId(),
                ];
                log_error('Available lot item member not found, when closing bulk lot data' . composeSuffix($logData));
                return;
            }
            $resetPieceHp = false;
            if (in_array(
                $this->getAuctionLot()->BulkMasterWinBidDistribution,
                [Constants\LotBulkGroup::BMWBD_WINNING, Constants\LotBulkGroup::BMWBD_EQUALLY],
                true
            )
            ) {
                $bulkPieceHp = $piecemealLotHpCalculator->getHammerPriceByLotItemId($bulkPieceLot->LotItemId);
            }

            if (
                $bulkPieceHp === null
                && $bulkPieceLot->CurrentBidId
            ) {
                $bulkPieceCurrentBid = $this->createBidTransactionLoader()->loadById($bulkPieceLot->CurrentBidId);
                if (!$bulkPieceCurrentBid) {
                    $logData = ['cb' => $bulkPieceLot->CurrentBidId, 'li' => $bulkPieceLot->LotItemId, 'a' => $bulkPieceLot->AuctionId, 'ali' => $bulkPieceLot->Id];
                    log_error('Available BidTransaction cannot be found by current bid id of bulk piece lot' . composeSuffix($logData));
                    continue;
                }
                $bulkPieceHp = $bulkPieceCurrentBid->Bid;
                $resetPieceHp = true;
            }

            $bulkPieceLotItem->assignSoldInfo(
                $this->getAuctionLot()->AuctionId, // Should also update sale/auction where it has been sold
                $dateSold,
                $bulkPieceHp,
                true,
                $winningUser->Id
            );

            // SAM-2991: wipe out sold info of bulk group item, if winner is House Bidder
            if ($hasPrivilegeForHouseBidder) {
                $bulkPieceLotItem->wipeOutSoldInfo();
            }
            $this->getLotItemWriteRepository()->saveWithModifier($bulkPieceLotItem, $editorUserId);
            if ($resetPieceHp) {
                $bulkPieceHp = null;
            }

            log_debug('BULKPIECE' . composeSuffix($bulkPieceLotItem->logDataForSellInfo()));

            if (!$hasPrivilegeForHouseBidder) {
                LotCloseNotifier::new()
                    ->setAuctionLot($bulkPieceLot)
                    ->setLotItem($bulkMasterLotItem)
                    ->setUser($winningUser)
                    ->notifyLotWon($editorUserId);
            }

            $bulkLotItemIds[] = $bulkPieceLotItem->Id;
        }

        if ($hasPrivilegeForHouseBidder) {
            $logData = [
                'u' => $winningUser->Id,
                'master ali' => $this->getAuctionLotId(),
                'master li' => $bulkMasterLotItem->Id,
                'a' => $this->getAuctionLot()->AuctionId,
                'bulk li' => $bulkLotItemIds,
            ];
            log_info('House Bidder wins bulk master lot. Bulk master and group items marked unsold' . composeSuffix($logData));
        }
    }
}
