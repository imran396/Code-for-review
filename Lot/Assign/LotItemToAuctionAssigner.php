<?php
/**
 * SAM-5634: Lot item to auction assigner
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 09, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Assign;

use Auction;
use AuctionLotItem;
use DateInterval;
use LotItem;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\Dto\TimedAuctionLotDates;
use Sam\AuctionLot\LotNo\Fill\LotNoAutoFillerAwareTrait;
use Sam\AuctionLot\Validate\TimedItemExistenceCheckerAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * This class is responsible for assigning lot item to auction
 *
 * Class LotItemToAuctionAssigner
 * @package Sam\Lot\Assign
 */
class LotItemToAuctionAssigner extends CustomizableClass
{
    use AuctionLotDateAssignorCreateTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use EntityFactoryCreateTrait;
    use LotCategoryLoaderAwareTrait;
    use LotNoAutoFillerAwareTrait;
    use TimedItemExistenceCheckerAwareTrait;
    use TimezoneLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Assign lot item to auction
     * @param LotItem $lotItem
     * @param Auction $auction
     * @param int $editorUserId
     * @return AuctionLotItem
     */
    public function assign(LotItem $lotItem, Auction $auction, int $editorUserId): AuctionLotItem
    {
        $this->makeAuctionLotsUnsold($lotItem, $editorUserId);
        $auctionLot = $this->createEntityFactory()->auctionLotItem();
        $auctionLot->AccountId = $auction->AccountId;
        $auctionLot->AuctionId = $auction->Id;
        $auctionLot->LotItemId = $lotItem->Id;
        $auctionLot->ListingOnly = false;
        $auctionLot->toActive();
        $lastActiveBidId = $this->detectLastActiveBidId($auction, $lotItem);
        $lastActiveBidId
            ? $auctionLot->linkCurrentBid($lastActiveBidId)
            : $auctionLot->unlinkCurrentBid();
        $auctionLot->BuyNowAmount = $this->getCategoryBuyNowAmount($lotItem);
        $auctionLot->TimezoneId = $auction->TimezoneId;
        $auctionLot->StartClosingDate = $auction->StartClosingDate;
        $auctionLot->Quantity = $lotItem->Quantity;
        $auctionLot->QuantityXMoney = $lotItem->QuantityXMoney;
        $auctionLot->HpTaxSchemaId = $lotItem->HpTaxSchemaId;
        $auctionLot->BpTaxSchemaId = $lotItem->BpTaxSchemaId;
        $this->fillAuctionLotNo($auctionLot);

        if ($auction->isTimed()) {
            $this->processTimedAuctionLot($auction, $auctionLot, $editorUserId);
        }
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);

        return $auctionLot;
    }

    /**
     * Mark unsold
     * @param LotItem $lotItem
     * @param int $editorUserId
     */
    private function makeAuctionLotsUnsold(LotItem $lotItem, int $editorUserId): void
    {
        $auctionLots = $this->createAuctionLotItemReadRepository()
            ->filterLotItemId($lotItem->Id)
            ->filterLotStatusId(Constants\Lot::LS_ACTIVE)
            ->loadEntities();
        foreach ($auctionLots as $auctionLot) {
            $auctionLot->toUnsold();
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        }
    }

    /**
     * @param LotItem $lotItem
     * @return float|null
     */
    private function getCategoryBuyNowAmount(LotItem $lotItem): ?float
    {
        $category = $this->getLotCategoryLoader()->loadFirstForLot($lotItem->Id, true);
        return $category->BuyNowAmount ?? null;
    }

    /**
     * @param AuctionLotItem $auctionLot
     */
    private function fillAuctionLotNo(AuctionLotItem $auctionLot): void
    {
        $this->getLotNoAutoFiller()
            ->setAutoIncrementMode(Constants\Lot::LOT_NO_AUTO_INC_AUCTION_OPTION)
            ->fill($auctionLot);
    }

    /**
     * @param Auction $auction
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    private function processTimedAuctionLot(Auction $auction, AuctionLotItem $auctionLot, int $editorUserId): void
    {
        if (!$this->isTimedItemExist($auctionLot)) {
            if (
                !$auction->ExtendAll
                && $auction->EventType
            ) {
                $this->createTimedItem($auctionLot, $editorUserId);
            }
            return;
        }

        $lotEndDate = $auction->ExtendAll ? $auction->EndDate : $auctionLot->EndDate;
        if ($lotEndDate) {
            if ($lotEndDate < $this->getDateHelper()->getCurrentDateUtc()) {
                $auctionLot->toUnsold();
            }
        } else {
            // By some reason there is no end date
            $this->assignNextWeekAsDates($auctionLot, $editorUserId);
        }
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    private function createTimedItem(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $this->assignNextWeekAsDates($auctionLot, $editorUserId);
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return bool
     */
    private function isTimedItemExist(AuctionLotItem $auctionLot): bool
    {
        return $this->getTimedItemExistenceChecker()->exist($auctionLot->LotItemId, $auctionLot->AuctionId);
    }

    /**
     * @param Auction $auction
     * @param LotItem $lotItem
     * @return int|null
     */
    private function detectLastActiveBidId(Auction $auction, LotItem $lotItem): ?int
    {
        $bidTransaction = $this->createBidTransactionLoader()->loadLastActiveBid($lotItem->Id, $auction->Id);
        return $bidTransaction->Id ?? null;
    }

    /**
     * Assign dates of the next week to auction lot item
     * @param AuctionLotItem $auctionLot
     * @param int $editorUserId
     */
    private function assignNextWeekAsDates(AuctionLotItem $auctionLot, int $editorUserId): void
    {
        $startDate = $this->getCurrentDateUtc();
        $startClosingDate = $this->getCurrentDateUtc();
        $startClosingDate->add(new DateInterval('P7D'));

        $auctionLotDates = TimedAuctionLotDates::new()
            ->setStartBiddingDate($startDate)
            ->setStartClosingDate($startClosingDate);
        $this->createAuctionLotDateAssignor()->assignForTimed($auctionLot, $auctionLotDates, $editorUserId);
    }
}
