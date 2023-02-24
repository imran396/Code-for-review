<?php
/**
 * Helping methods for BidIncrement saving
 *
 * SAM-4241 Auction Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 1, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidIncrement\Save;

use BidIncrement;
use Sam\AuctionLot\Cache\Save\AuctionLotCacheUpdaterCreateTrait;
use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\Copy\EntityClonerCreateTrait;
use Sam\Storage\WriteRepository\Entity\BidIncrement\BidIncrementWriteRepositoryAwareTrait;

/**
 * Class BidIncrementProducer
 * @package Sam\Bidding\BidIncrement\Save
 */
class BidIncrementProducer extends CustomizableClass
{
    use AuctionLotCacheUpdaterCreateTrait;
    use BidIncrementLoaderAwareTrait;
    use BidIncrementWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use EntityClonerCreateTrait;
    use EntityFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add to db increment ranges and amounts
     * @param int $accountId
     * @param string $auctionType
     * @param int|null $createdByUserId
     */
    public function addDefault(
        int $accountId,
        string $auctionType = Constants\Auction::LIVE,
        ?int $createdByUserId = null
    ): void {
        $bidIncrements = [
            [0, 1],
            [100, 10],
            [1000, 100],
            [10000, 1000],
            [100000, 10000],
        ];
        $this->bulkCreate($bidIncrements, $accountId, $createdByUserId, null, null, $auctionType);
    }

    /**
     * Bulk create increments
     * @param array|null $increments [[amount, increment]]
     * @param int $accountId
     * @param int $editorUserId
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @param string|null $auctionType
     */
    public function bulkCreate(
        ?array $increments,
        int $accountId,
        int $editorUserId,
        ?int $auctionId = null,
        ?int $lotItemId = null,
        ?string $auctionType = null
    ): void {
        if ($increments) {
            foreach ($increments as $increment) {
                $this->create(
                    (float)$increment[0],
                    (float)$increment[1],
                    $editorUserId,
                    $accountId,
                    $auctionType,
                    $auctionId,
                    $lotItemId
                );
            }
        }
    }

    /**
     * Clone bidIncrements from provided account
     * @param int $sourceAccountId
     * @param int $targetAccountId
     * @param int $editorUserId
     */
    public function cloneFromAccount(int $sourceAccountId, int $targetAccountId, int $editorUserId): void
    {
        $sourceBidIncrements = $this->getBidIncrementLoader()
            ->loadAll($sourceAccountId, Constants\Auction::AUCTION_TYPES);
        foreach ($sourceBidIncrements as $source) {
            /** @var BidIncrement $bidIncrement */
            $bidIncrement = $this->createEntityCloner()->cloneRecord($source);
            $bidIncrement->AccountId = $targetAccountId;
            $this->getBidIncrementWriteRepository()->saveWithModifier($bidIncrement, $editorUserId);
        }
    }

    /**
     * Create auction's, lotItem's or account's increment
     * @param float|null $amount
     * @param float|null $increment
     * @param int $editorUserId
     * @param int|null $accountId
     * @param string|null $auctionType
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @return BidIncrement
     */
    public function create(
        ?float $amount,
        ?float $increment,
        int $editorUserId,
        ?int $accountId = null,
        ?string $auctionType = null,
        ?int $auctionId = null,
        ?int $lotItemId = null
    ): BidIncrement {
        $bidIncrement = $this->createEntityFactory()->bidIncrement();
        $bidIncrement->AccountId = $accountId;
        $bidIncrement->Amount = $amount;
        $bidIncrement->AuctionId = $auctionId;
        $bidIncrement->AuctionType = $auctionType;
        $bidIncrement->Increment = $increment;
        $bidIncrement->LotItemId = $lotItemId;
        $this->getBidIncrementWriteRepository()->saveWithModifier($bidIncrement, $editorUserId);
        return $bidIncrement;
    }

    /**
     * Bulk update auction's or lotItem's increments
     * Works in 3 steps: update modified records, remove unused records, add new records.
     * It's faster than just 2 steps algorithm: remove old records, add new records.
     * @param array $newIncrements [[amount, increment]]
     * @param int $accountId
     * @param int $userId
     * @param int $editorUserId
     * @param int|null $auctionId
     * @param int|null $lotItemId
     */
    public function bulkUpdate(
        array $newIncrements,
        int $accountId,
        int $userId,
        int $editorUserId,
        ?int $auctionId = null,
        ?int $lotItemId = null
    ): void {
        $oldIncrements = $this->getOldIncrements($auctionId, $lotItemId);

        // Exclude duplicate records
        foreach ($oldIncrements as $oldKey => $oldIncrement) {
            foreach ($newIncrements as $newKey => $newIncrement) {
                if ((
                        (
                            $lotItemId
                            && $oldIncrement->LotItemId
                        ) || (
                            $auctionId
                            && $oldIncrement->AuctionId
                        )
                    )
                    && trim((string)$oldIncrement->Amount) === trim($newIncrement[0])
                    && trim((string)$oldIncrement->Increment) === trim($newIncrement[1])
                ) {
                    unset($oldIncrements[$oldKey], $newIncrements[$newKey]);
                }
                if (!$newIncrement) {
                    unset($newIncrements[$newKey]);
                }
            }
        }

        // Order keys ascending
        $oldIncrements = array_values($oldIncrements);

        // Update modified records
        $counter = 0;
        foreach ($newIncrements as $key => $newIncrement) {
            if (!isset($oldIncrements[$counter])) {
                break;
            }
            $oldIncrements[$counter]->AccountId = $accountId;
            $oldIncrements[$counter]->Amount = (float)$newIncrement[0];
            $oldIncrements[$counter]->AuctionId = $auctionId;
            $oldIncrements[$counter]->Increment = (float)$newIncrement[1];
            $oldIncrements[$counter]->LotItemId = $lotItemId;
            $this->getBidIncrementWriteRepository()->saveWithModifier($oldIncrements[$counter], $editorUserId);
            unset($newIncrements[$key], $oldIncrements[$counter]);
            $counter++;
        }

        // Remove unused records
        foreach ($oldIncrements as $oldIncrement) {
            $this->getBidIncrementWriteRepository()->deleteWithModifier($oldIncrement, $editorUserId);
        }

        // Add new records
        $this->bulkCreate($newIncrements, $accountId, $userId, $auctionId, $lotItemId);

        $this->refreshLots($lotItemId, $auctionId, $editorUserId);
    }

    /**
     * Get old increments
     * @param int|null $auctionId
     * @param int|null $lotItemId
     * @return BidIncrement[]
     */
    private function getOldIncrements(?int $auctionId, ?int $lotItemId): array
    {
        $bidIncrements = $auctionId
            ? $this->getBidIncrementLoader()->loadForAuction($auctionId)
            : $this->getBidIncrementLoader()->loadForLot($lotItemId);
        return $bidIncrements;
    }

    /**
     * Refresh lots
     * @param int|null $lotItemId
     * @param int|null $auctionId
     * @param int $editorUserId
     */
    private function refreshLots(?int $lotItemId, ?int $auctionId, int $editorUserId): void
    {
        if ($auctionId) {
            $this->createAuctionLotCacheUpdater()->refreshForAuction($auctionId, $editorUserId);
            return;
        }
        if ($lotItemId) {
            $this->createAuctionLotCacheUpdater()->refreshForLotItem($lotItemId, $editorUserId);
        }
    }
}
