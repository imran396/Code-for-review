<?php
/**
 * SAM-6431: Refactor Auction_Lots_DbCacheManager for 2020 year version
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Cache\Save;

use Auction;
use AuctionLotItem;
use AuctionLotItemCache;
use DateTime;
use Exception;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use QMySqli5DatabaseResult;
use QMySqliDatabaseException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\BulkGroup\Load\LotBulkGroupLoaderAwareTrait;
use Sam\AuctionLot\Cache\Query\LotBidQueryBuildPartner;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidExistenceCheckerAwareTrait;
use Sam\Bidding\AskingBid\AskingBidDetectorCreateTrait;
use Sam\Bidding\AskingBid\LiveAbsenteeBidCalculatorCreateTrait;
use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Bidding\BidTransaction\Validate\BidTransactionExistenceCheckerAwareTrait;
use Sam\Bidding\CurrentAbsenteeBid\CurrentAbsenteeBidCalculatorCreateTrait;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Lot\SeoUrl\Build\LotSeoUrlBuilderCreateTrait;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItemCache\AuctionLotItemCacheReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItemCache\AuctionLotItemCacheWriteRepositoryAwareTrait;

/**
 * Class AuctionLotCacheUpdater
 * @package Sam\AuctionLot\Cache
 */
class AuctionLotCacheUpdater extends CustomizableClass
{
    use AbsenteeBidExistenceCheckerAwareTrait;
    use AskingBidDetectorCreateTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotItemCacheReadRepositoryCreateTrait;
    use AuctionLotItemCacheWriteRepositoryAwareTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use BidIncrementLoaderAwareTrait;
    use BidTransactionExistenceCheckerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentAbsenteeBidCalculatorCreateTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use LiveAbsenteeBidCalculatorCreateTrait;
    use LotBulkGroupLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotSeoUrlBuilderCreateTrait;
    use OutputBufferCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Refresh cached values for auction lot item
     *
     * @param AuctionLotItem|null $auctionLot
     * @param int $editorUserId
     * @return AuctionLotItemCache|null
     */
    public function refreshForAuctionLot(?AuctionLotItem $auctionLot, int $editorUserId): ?AuctionLotItemCache
    {
        if ($auctionLot) {
            $values = array_merge(
                $this->getLotValues($auctionLot),
                $this->getDateValues($auctionLot)
            );
            return $this->update($auctionLot->Id, $values, $editorUserId);
        }
        return null;
    }

    /**
     * Refresh cached values for lot item among all related to it auction lot items
     *
     * @param int $lotItemId lot_item.id
     * @param int $editorUserId
     */
    public function refreshForLotItem(int $lotItemId, int $editorUserId): void
    {
        $auctionLots = $this->createAuctionLotItemReadRepository()
            ->filterLotItemId($lotItemId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAccountFilterActive(true)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->loadEntities();
        foreach ($auctionLots as $auctionLot) {
            $this->refreshForAuctionLot($auctionLot, $editorUserId);
        }
    }

    /**
     * Refresh cached values for all lots in auction
     *
     * @param int $auctionId auction.id
     * @param int $editorUserId
     */
    public function refreshForAuction(int $auctionId, int $editorUserId): void
    {
        if (!$auctionId) {
            log_errorBackTrace("Cannot refresh lots of absent auction");
            return;
        }
        log_debug('Started refreshing db cached values for lots of auction' . composeSuffix(['a' => $auctionId]));
        $repo = $this->createAuctionLotItemReadRepository()
            ->filterAuctionId($auctionId)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->setChunkSize(100);
        while ($auctionLots = $repo->loadEntities()) {
            foreach ($auctionLots as $auctionLot) {
                $this->refreshForAuctionLot($auctionLot, $editorUserId);
            }
        }
    }

    /**
     * Refresh bid values (current/starting/asking bid, etc.) in cache for auction lot item
     *
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     * @return AuctionLotItemCache
     */
    public function refreshBidInfo(AuctionLotItem $auctionLot, int $editorUserId): AuctionLotItemCache
    {
        $values = $this->getLotValues($auctionLot);
        return $this->update($auctionLot->Id, $values, $editorUserId);
    }

    /**
     * Refresh date values in cache for auction lot item
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    public function refreshDateInfo(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            $logData = ['a' => $auctionLot->AuctionId, 'li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id];
            log_error("Available auction not found for updating lot cached date info" . composeSuffix($logData));
            return;
        }
        if ($auction->isTimed()) {
            $this->refreshDateInfoForTimed($auctionLot, $editorUserId);
        } else {
            $this->refreshDateInfoForLive($auctionLot, $auction, $editorUserId);
        }
    }

    /**
     * Refresh date values in cache for timed auction lot item
     *
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     * @return AuctionLotItemCache
     */
    public function refreshDateInfoForTimed(AuctionLotItem $auctionLot, int $editorUserId): AuctionLotItemCache
    {
        $values = $this->getDateValuesForTimed($auctionLot);
        return $this->update($auctionLot->Id, $values, $editorUserId);
    }

    /**
     * Refresh date values in cache for live auction lot item
     *
     * @param AuctionLotItem $auctionLot
     * @param Auction $auction
     * @param int $editorUserId
     * @return AuctionLotItemCache
     */
    public function refreshDateInfoForLive(AuctionLotItem $auctionLot, Auction $auction, int $editorUserId): AuctionLotItemCache
    {
        $values = $this->getDateValuesForLive($auction);
        return $this->update($auctionLot->Id, $values, $editorUserId);
    }

    /**
     * Refresh cached values for all lots
     *
     * @param int[] $accountIds
     * @param int $editorUserId
     * @param bool $isEcho output processing info
     */
    public function refreshAll(array $accountIds, int $editorUserId, bool $isEcho = false): void
    {
        $this->refreshAllForAuctionType(null, $accountIds, $editorUserId, $isEcho);
    }

    /**
     * Refresh cached values for all timed lots
     *
     * @param int[] $accountIds
     * @param int $editorUserId
     * @param bool $isEcho output processing info
     */
    public function refreshAllTimed(array $accountIds, int $editorUserId, bool $isEcho = false): void
    {
        $this->refreshAllForAuctionType(Constants\Auction::TIMED, $accountIds, $editorUserId, $isEcho);
    }

    /**
     * Refresh cached values for all live lots
     *
     * @param int[] $accountIds
     * @param int $editorUserId
     * @param bool $isEcho output processing info
     */
    public function refreshAllLive(array $accountIds, int $editorUserId, bool $isEcho = false): void
    {
        $this->refreshAllForAuctionType(Constants\Auction::LIVE, $accountIds, $editorUserId, $isEcho);
    }

    /**
     * Refresh cached values for timed lots, which use global bid increment settings
     *
     * @param int[] $accountIds [] for main account only
     * @param int $editorUserId
     * @throws QMySqliDatabaseException
     */
    public function refreshTimedForGlobalIncrements(array $accountIds, int $editorUserId): void
    {
        if (!$accountIds) {
            $accountIds[] = $this->cfg()->get('core->portal->mainAccountId');
        }
        $this->refreshForAuctionTypeUsingGlobalIncrements(Constants\Auction::TIMED, $accountIds, $editorUserId);
    }

    /**
     * Refresh cached values for live lots, which use global bid increment settings
     *
     * @param int[] $accountIds
     * @param int $editorUserId
     */
    public function refreshLiveForGlobalIncrements(array $accountIds, int $editorUserId): void
    {
        if (!$accountIds) {
            $accountIds[] = $this->cfg()->get('core->portal->mainAccountId');
        }
        $this->refreshForAuctionTypeUsingGlobalIncrements(Constants\Auction::LIVE, $accountIds, $editorUserId);
    }

    // --- Protected / Private methods --------------------------------------

    /**
     * Update values in cache
     *
     * @param int $auctionLotId
     * @param array $values
     * - float 'current_bid'
     * - float 'current_max_bid'
     * - integer 'current_bidder_id'
     * - \DateTime 'current_bid_placed'
     * - float 'second_max_bid'
     * - integer 'second_bidder_id'
     * - float 'asking_bid'
     * - float 'starting_bid_normalized'
     * - integer 'bid_count'
     * - \DateTime 'start_date'
     * - \DateTime 'end_date'
     * @param int $editorUserId
     * @return AuctionLotItemCache - cache entry
     */
    protected function update(int $auctionLotId, array $values, int $editorUserId): AuctionLotItemCache
    {
        $auctionLotCache = $this->createAuctionLotItemCacheReadRepository()
            ->filterAuctionLotItemId($auctionLotId)
            ->loadEntity();
        if (!$auctionLotCache) {
            $auctionLotCache = $this->createEntityFactory()->auctionLotItemCache();
            $auctionLotCache->AuctionLotItemId = $auctionLotId;
        }

        $underscoreToCamelCase = new UnderscoreToCamelCase();
        foreach ($values as $key => $value) {
            $property = $underscoreToCamelCase->filter($key);
            $auctionLotCache->$property = $value;
        }

        $this->getAuctionLotItemCacheWriteRepository()->saveWithModifier($auctionLotCache, $editorUserId);
        $valuesToLog = [];
        foreach ($values as $key => $value) {
            $valuesToLog[$key] = $value;
        }
        log_debug(
            'Auction lot item' . composeSuffix(['id' => $auctionLotCache->AuctionLotItemId])
            . ' values in db cache updated' . composeSuffix($valuesToLog)
        );
        return $auctionLotCache;
    }

    /**
     * Refresh lots cached values
     *
     * @param string|null $auctionType null - for all lots
     * @param int[] $accountIds
     * @param int $editorUserId
     * @param bool $isEcho output processing info
     */
    protected function refreshAllForAuctionType(
        ?string $auctionType,
        array $accountIds,
        int $editorUserId,
        bool $isEcho = false
    ): void {
        $repo = $this->createAuctionLotItemReadRepository()
            ->filterAccountId($accountIds)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            ->joinLotItemFilterActive(true)
            ->setChunkSize(100);
        if ($auctionType) {
            $repo->joinAuctionFilterAuctionType($auctionType);
        }

        if ($isEcho) {
            $this->createOutputBuffer()->endFlush();
        }

        $total = $repo->count();
        $typeName = $auctionType ? AuctionPureRenderer::new()->makeAuctionType($auctionType) : 'all';
        log_debug(
            'Started refreshing db cached values for ' . $typeName . ' lots.'
            . composeSuffix(['Lot count' => $total])
        );
        while ($auctionLots = $repo->loadEntities()) {
            foreach ($auctionLots as $auctionLot) {
                $this->refreshForAuctionLot($auctionLot, $editorUserId);
            }
            if ($isEcho) {
                $offset = ($repo->getChunkNum() - 1) * $repo->getChunkSize();
                echo 'Lots cached: ' . ($offset + count($auctionLots)) . ' (of ' . $total . ")\n";
            }
        }

        log_debug(
            'Finished refreshing db cached values for ' . $typeName . ' lots.'
            . composeSuffix(['Lot count' => $total])
        );
    }

    /**
     * Refresh lots cached values, which use global increment settings
     *
     * @param string $auctionType null - for any type
     * @param int[] $accountIds
     * @param int $editorUserId
     */
    protected function refreshForAuctionTypeUsingGlobalIncrements(string $auctionType, array $accountIds, int $editorUserId): void
    {
        $auctionTypeCondition = $accountCondition = '';
        if ($auctionType) {
            $auctionTypeCondition = " AND auction_type = " . $this->escape($auctionType);
        }
        $accountIds = ArrayCast::castInt($accountIds, Constants\Type::F_INT_POSITIVE);
        if ($accountIds) {
            $accountCondition = " AND account_id IN ('" . implode("','", $accountIds) . "') ";
        }
        $query = "SELECT id FROM auction WHERE id NOT IN " .
            "(SELECT DISTINCT(`auction_id`) FROM `bid_increment` WHERE auction_id > 0)";
        $query .= $auctionTypeCondition . $accountCondition;
        $dbResult = $this->query($query);
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $this->refreshForAuction((int)$row['id'], $editorUserId);
        }
    }

    /**
     * Calculate and return lot values
     *
     * @param AuctionLotItem $auctionLot
     * @return array
     */
    protected function getLotValues(AuctionLotItem $auctionLot): array
    {
        $values = [
            'current_bid' => null,
            'current_max_bid' => null,
            'current_bidder_id' => null,
            'second_max_bid' => null,
            'second_bidder_id' => null,
            'starting_bid_normalized' => null,
            'asking_bid' => null,
            'bulk_master_asking_bid' => null,
            'bulk_master_suggested_reserve' => null,
            'bid_count' => 0,
            'seo_url' => '',
        ];
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found for auction lot cache calculation"
                . composeSuffix(
                    [
                        'a' => $auctionLot->AuctionId,
                        'li' => $auctionLot->LotItemId,
                        'ali' => $auctionLot->Id,
                    ]
                )
            );
            return [];
        }
        if ($auction->isTimed()) {
            $values = array_merge($values, $this->getBidValuesForTimed($auctionLot->Id));

            // $currentBid = $this->getBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
            if ($auctionLot->CurrentBidId) {
                $values['bid_count'] = $this->createBidTransactionExistenceChecker()
                    ->count($auctionLot->LotItemId, $auctionLot->AuctionId);
            }
            if ($auctionLot->hasMasterRole()) {
                $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
                if (!$lotItem) {
                    log_error(
                        "Available lot item not found for auction lot cache calculation"
                        . composeSuffix(['li' => $auctionLot->LotItemId, 'a' => $auctionLot->AuctionId, 'ali' => $auctionLot->Id])
                    );
                    return [];
                }
                $totalWinningMaxBid = $this->getLotBulkGroupLoader()->loadBulkGroupTotalWinningMaxBid($auctionLot->Id);
                $bulkAskingBid = (float)$this->createAskingBidDetector()->detectAskingBid(
                    $auctionLot->LotItemId,
                    $auctionLot->AuctionId,
                    $totalWinningMaxBid
                );
                $startingBid = (float)$lotItem->StartingBid;
                $values['bulk_master_asking_bid'] = Floating::lt($bulkAskingBid, $startingBid)
                    ? $startingBid : $bulkAskingBid;
                $values['bulk_master_suggested_reserve'] = $this->getLotBulkGroupLoader()
                    ->loadBulkGroupSuggestedReserve($auctionLot->Id);
            }
        } else {
            $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
            if (!$lotItem) {
                log_error(
                    "Available lot item not found for auction lot cache calculation"
                    . composeSuffix(['li' => $auctionLot->LotItemId, 'a' => $auctionLot->AuctionId, 'ali' => $auctionLot->Id])
                );
                return [];
            }
            $currentAbsenteeBidAmount = $this->createCurrentAbsenteeBidCalculator()
                ->setLotItem($lotItem)
                ->setAuction($auction)
                ->calculate();
            $values['current_bid'] = $currentAbsenteeBidAmount;
            $bidIncrement = $this->getBidIncrementLoader()->loadAvailable(
                $currentAbsenteeBidAmount,
                $auctionLot->AccountId,
                $auction->AuctionType,
                $auction->Id,
                $auctionLot->LotItemId
            );
            $increment = $bidIncrement->Increment ?? 0.;
            [$highAbsentee, $secondAbsentee] = $this->createHighAbsenteeBidDetector()
                ->detectTwoHighest($auctionLot->LotItemId, $auctionLot->AuctionId);
            if ($highAbsentee) {
                $values['current_max_bid'] = $highAbsentee->MaxBid;
                $values['current_bidder_id'] = $highAbsentee->UserId;
                $values['current_bid_placed'] = $highAbsentee->PlacedOn;
                $values['bid_count'] = $this->getAbsenteeBidExistenceChecker()
                    ->countForLot($auctionLot->LotItemId, $auctionLot->AuctionId);
            }
            if ($secondAbsentee) {
                $values['second_max_bid'] = $secondAbsentee->MaxBid;
                $values['second_bidder_id'] = $secondAbsentee->UserId;
            }
            $values['starting_bid_normalized'] = $lotItem->StartingBid;
            $values['asking_bid'] = $this->createLiveAbsenteeBidCalculator()
                ->calculate(
                    (float)$values['current_max_bid'],
                    (float)$values['second_max_bid'],
                    (float)$lotItem->StartingBid,
                    $increment,
                    (float)$values['current_bid']
                );
        }
        $seoUrls = $this->createLotSeoUrlBuilder()
            ->construct($auctionLot->AccountId, [$auctionLot->Id])
            ->render();
        // it may be un-assigned lot, then we don't produce 'seo_url'
        $values['seo_url'] = $seoUrls[$auctionLot->Id] ?? null;
        return $values;
    }

    /**
     * Return bid values for timed auction lot
     *
     * @param int $auctionLotId auction_lot_item.id
     * @return array
     */
    public function getBidValuesForTimed(int $auctionLotId): array
    {
        $bt = 'bt_cb';
        $lotBidQuery = LotBidQueryBuildPartner::new()
            ->setBidTransactionTableAlias($bt);
        $selects = [
            "{$bt}.bid AS current_bid",
            "{$bt}.max_bid AS current_max_bid",
            "{$bt}.user_id AS current_bidder_id",
            "{$bt}.created_on AS current_bid_placed",
            $lotBidQuery->getStartingAndAskingBidSelectClause()
        ];
        $row = $this->createAuctionLotItemReadRepository()
            ->filterId($auctionLotId)
            ->joinAuction()
            ->joinBidTransactionByCurrentBidFilterDeleted([null, false])
            ->joinLotItem()
            ->select($selects)
            ->loadRow();
        $values = [
            'current_bid' => null,
            'current_max_bid' => null,
            'current_bidder_id' => null,
            'asking_bid' => null,
            'starting_bid_normalized' => null,
        ];
        if ($row) {
            $values['current_bid'] = is_numeric($row['current_bid']) ? (float)$row['current_bid'] : null;
            $values['current_max_bid'] = is_numeric($row['current_max_bid']) ? (float)$row['current_max_bid'] : null;
            $values['current_bidder_id'] = $row['current_bidder_id'];
            $currentBidPlacedDate = null;
            if (!empty($row['current_bid_placed'])) {
                try {
                    $currentBidPlacedDate = new DateTime($row['current_bid_placed']);
                } catch (Exception) {
                    log_errorBackTrace('Cannot create DateTime by ' . $row['current_bid_placed']);
                }
            }
            $values['current_bid_placed'] = $currentBidPlacedDate;
            $values['starting_bid_normalized'] = (float)$row['starting_bid_normalized'];
            $values['asking_bid'] = Floating::gt($row['asking_bid'], 0)
                ? (float)$row['asking_bid']
                : null;    // actual for reverse bidding
        }
        return $values;
    }

    /**
     * Return date values in UTC
     *
     * @param AuctionLotItem $auctionLot
     * @return array
     */
    protected function getDateValues(AuctionLotItem $auctionLot): array
    {
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found for auction lot cache calculation"
                . composeSuffix(['a' => $auctionLot->AuctionId, 'li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id])
            );
            return [];
        }
        if ($auction->isTimed()) {
            $values = $this->getDateValuesForTimed($auctionLot);
        } else {
            $values = $this->getDateValuesForLive($auction);
        }
        return $values;
    }

    /**
     * Return date values in UTC for timed lot
     *
     * @param AuctionLotItem $auctionLot
     * @return array
     */
    protected function getDateValuesForTimed(AuctionLotItem $auctionLot): array
    {
        if (
            $auctionLot->EndDate === null
            || $auctionLot->StartDate === null
        ) {
            $startDate = null;
            $endDate = null;
        } else {
            $startDate = clone $auctionLot->StartDate;
            $endDate = clone $auctionLot->EndDate;
        }
        $values = [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        return $values;
    }

    /**
     * Return date values in UTC for live lot
     *
     * @param Auction $auction
     * @return array
     */
    protected function getDateValuesForLive(Auction $auction): array
    {
        $values = [
            'start_date' => clone $auction->StartClosingDate,
            'end_date' => $auction->EndDate
                ? clone $auction->EndDate
                : clone $auction->StartClosingDate,
        ];
        return $values;
    }

}
