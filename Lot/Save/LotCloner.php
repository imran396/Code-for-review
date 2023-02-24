<?php
/**
 * SAM-4401: Clone lot manager class
 * https://bidpath.atlassian.net/browse/SAM-4401
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Oct 6, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Lot\Save;

use Auction;
use DateInterval;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use LotItem;
use Sam\Auction\Date\Calculate\AuctionDateFromLotsDetectorCreateTrait;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\AuctionLotDatesUpdaterCreateTrait;
use Sam\AuctionLot\Date\Dto\TimedAuctionLotDates;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\AuctionLot\LotNo\Fill\LotNoAutoFillerAwareTrait;
use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Bidding\BidIncrement\Save\BidIncrementProducerAwareTrait;
use Sam\BuyersPremium\Load\BuyersPremiumRangeLoaderCreateTrait;
use Sam\BuyersPremium\Save\BuyersPremiumRangeProducerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomDataLoaderCreateTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\Lot\Category\LotLinker\LotCategoryLotLinkerAwareTrait;
use Sam\Lot\Category\Validate\LotCategoryExistenceCheckerAwareTrait;
use Sam\Lot\Save\Cloner\LotImageClonerCreateTrait;
use Sam\Lot\Save\ItemNum\ItemNumCloneStrategyCreateTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItemCustData\LotItemCustDataWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\TimedOnlineItem\TimedOnlineItemWriteRepositoryAwareTrait;
use TimedOnlineItem;

/**
 * Class LotCloner
 * @package Sam\Lot\Save
 */
class LotCloner extends CustomizableClass
{
    use AccountAwareTrait;
    use AuctionDateFromLotsDetectorCreateTrait;
    use AuctionLotDateAssignorCreateTrait;
    use AuctionLotDatesUpdaterCreateTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidIncrementLoaderAwareTrait;
    use BidIncrementProducerAwareTrait;
    use BuyersPremiumRangeProducerCreateTrait;
    use BuyersPremiumRangeLoaderCreateTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use ItemNumCloneStrategyCreateTrait;
    use LotCategoryExistenceCheckerAwareTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCategoryLotLinkerAwareTrait;
    use LotCustomDataLoaderCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotImageClonerCreateTrait;
    use LotItemCustDataWriteRepositoryAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use LotNoAutoFillerAwareTrait;
    use TimedItemLoaderAwareTrait;
    use TimedOnlineItemWriteRepositoryAwareTrait;

    /** @var Auction|null */
    protected ?Auction $targetAuction = null;
    /** @var array */
    protected array $fields = [];
    /** @var Auction|null */
    protected ?Auction $sourceAuction = null;
    /** @var LotItem|null */
    protected ?LotItem $sourceLotItem = null;

    /**
     * Class instantiated method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return Auction
     */
    public function getTargetAuction(): Auction
    {
        if (!$this->targetAuction instanceof Auction) {
            throw new InvalidArgumentException("Target auction not defined");
        }
        return $this->targetAuction;
    }

    /**
     * @param Auction $targetAuction
     * @return static
     */
    public function setTargetAuction(Auction $targetAuction): static
    {
        $this->targetAuction = $targetAuction;
        return $this;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        if (empty($this->fields)) {
            throw new InvalidArgumentException("Cloning fields not defined");
        }
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return static
     */
    public function setFields(array $fields): static
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return Auction
     */
    public function getSourceAuction(): Auction
    {
        if (!$this->sourceAuction instanceof Auction) {
            throw new InvalidArgumentException("Source auction not defined");
        }
        return $this->sourceAuction;
    }

    /**
     * @param Auction $sourceAuction
     * @return static
     */
    public function setSourceAuction(Auction $sourceAuction): static
    {
        $this->sourceAuction = $sourceAuction;
        return $this;
    }

    /**
     * @return LotItem
     */
    public function getSourceLotItem(): LotItem
    {
        if (!$this->sourceLotItem instanceof LotItem) {
            throw new InvalidArgumentException("Source lot item not defined");
        }
        return $this->sourceLotItem;
    }

    /**
     * @param LotItem|null $sourceLotItem
     * @return static
     */
    public function setSourceLotItem(?LotItem $sourceLotItem): static
    {
        $this->sourceLotItem = $sourceLotItem;
        return $this;
    }

    /**
     * @param int $editorUserId
     * @return LotItem
     */
    public function cloneLot(int $editorUserId): LotItem
    {
        $sourceLotItem = $this->getSourceLotItem();
        $accountId = $this->getAccountId();
        [$itemNum, $itemNumExt] = $this->createItemNumCloneStrategy()->detectCloneItemNum(
            $accountId,
            $sourceLotItem->ItemNum,
            $sourceLotItem->ItemNumExt
        );
        $targetLotItem = $this->createLotItem($editorUserId, $accountId, $itemNum, $itemNumExt);
        $lotCustomFieldNames = $this->collectCustomFieldNames();
        $fields = $this->getFields();
        foreach ($fields as $name) {
            switch ($name) {
                case Constants\LotCloner::LC_CATEGORY:
                    $this->cloneCategories($targetLotItem, $sourceLotItem, $editorUserId);
                    break;
                case Constants\LotCloner::LC_IMAGES:
                    $this->createLotImageCloner()->cloneAll($targetLotItem, $sourceLotItem, $editorUserId);
                    break;
                case Constants\LotCloner::LC_IMAGE_DEFAULT:
                    if (!in_array(Constants\LotCloner::LC_IMAGES, $fields, true)) {
                        $this->createLotImageCloner()->cloneDefault($targetLotItem, $sourceLotItem, $editorUserId);
                    }
                    break;
                case Constants\LotCloner::LC_INCREMENTS:
                    $this->cloneIncrements($targetLotItem, $sourceLotItem, $editorUserId);
                    break;
                case Constants\LotCloner::LC_BUYERS_PREMIUM:
                    $this->cloneByersPremium($targetLotItem, $sourceLotItem, $editorUserId);
                    $targetLotItem->AdditionalBpInternet = $sourceLotItem->AdditionalBpInternet;
                    $targetLotItem->BpRangeCalculation = $sourceLotItem->BpRangeCalculation;
                    break;
                case Constants\LotCloner::LC_GENERAL_NOTE:
                case Constants\LotCloner::LC_NOTE_TO_CLERK:
                case Constants\LotCloner::LC_FULL_AUCTION_LOT:
                case Constants\LotCloner::LC_FULL_LOT_ITEM:
                    break;
                default:
                    if (in_array($name, $lotCustomFieldNames, true)) {
                        $this->createLotCustomData(
                            $name,
                            $lotCustomFieldNames,
                            $sourceLotItem,
                            $targetLotItem,
                            $editorUserId
                        );
                    } else {
                        $targetLotItem->$name = $sourceLotItem->$name;
                    }
                    break;
            }
        }
        $this->getLotItemWriteRepository()->saveWithModifier($targetLotItem, $editorUserId);
        $this->cloneAuctionLot($targetLotItem, $editorUserId);
        $targetAuction = $this->getTargetAuction();
        if ($targetAuction->isTimed()) {
            $this->createTimedInfo($accountId, $targetLotItem, $targetAuction, $editorUserId);
        }
        return $targetLotItem;
    }

    /**
     * @param LotItem $targetLotItem
     * @param int $editorUserId
     */
    protected function cloneAuctionLot(LotItem $targetLotItem, int $editorUserId): void
    {
        $fields = $this->getFields();
        $sourceAuction = $this->getSourceAuction();
        $targetAuction = $this->getTargetAuction();
        $sourceLotItem = $this->getSourceLotItem();
        $previousAuctionLot = $this->getAuctionLotLoader()->load($sourceLotItem->Id, $sourceAuction->Id);
        if (
            in_array(Constants\LotCloner::LC_FULL_AUCTION_LOT, $fields, true)
            && $previousAuctionLot
        ) {
            $auctionLot = clone $previousAuctionLot;
            $auctionLot->Id = null;
            $auctionLot->RowVersion = 0;
            $auctionLot->__Restored = false;
            $auctionLot->LotNum = null;
        } else {
            $auctionLot = $this->createEntityFactory()->auctionLotItem();
        }

        $auctionLot->AccountId = $this->getAccountId();
        $auctionLot->AuctionId = $targetAuction->Id;
        $auctionLot->LotItemId = $targetLotItem->Id;
        $auctionLot->toActive();
        $auctionLot->ListingOnly = false;
        $auctionLot->TimezoneId = $targetAuction->TimezoneId;

        if ($previousAuctionLot) {
            if (in_array(Constants\LotCloner::LC_GENERAL_NOTE, $fields, true)) {
                $auctionLot->GeneralNote = $previousAuctionLot->GeneralNote;
            }
            if (in_array(Constants\LotCloner::LC_NOTE_TO_CLERK, $fields, true)) {
                $auctionLot->NoteToClerk = $previousAuctionLot->NoteToClerk;
            }
            $auctionLot->TimezoneId = $previousAuctionLot->TimezoneId;
        }
        $this->getLotNoAutoFiller()
            ->setAutoIncrementMode(Constants\Lot::LOT_NO_AUTO_INC_AUCTION_OPTION)
            ->fill($auctionLot);
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
    }

    /**
     * @param int|null $accountId
     * @param LotItem $lotItem
     * @param Auction $auction
     * @param int $editorUserId
     * @return TimedOnlineItem
     * @throws Exception
     */
    protected function createTimedInfo(?int $accountId, LotItem $lotItem, Auction $auction, int $editorUserId): TimedOnlineItem
    {
        $timedItem = $this->getTimedItemLoader()->loadOrCreate($lotItem->Id, $auction->Id); // Get timed info in select auction lot
        $this->getTimedOnlineItemWriteRepository()->saveWithModifier($timedItem, $editorUserId);

        if ($auction->isTimedOngoing()) {
            $startBiddingDate = $this->getCurrentDateUtc();
            $startClosingDate = $this->getCurrentDateUtc()->add(new DateInterval('P7D'));
        } else { // Timed+Scheduled
            $startBiddingDate = clone $auction->StartBiddingDate;
            $startClosingDate = clone $auction->StartClosingDate;
            if (
                !$auction->ExtendAll
                || $auction->StaggerClosing
            ) {
                if ($auction->isAuctionToItemsDateAssignment()) {
                    if ($auction->StaggerClosing > 0) {
                        $this->createAuctionLotDatesUpdater()->update($auction->Id, $editorUserId);
                    }
                } elseif ($auction->isItemsToAuctionDateAssignment()) {
                    $startClosingDate = $this->getCurrentDateSys($accountId)
                        ->setTime(0, 0)
                        ->add(new DateInterval('P7D'));
                    $startClosingDate->setTimezone(new DateTimeZone('UTC'));
                    $auctionStartClosingDateFromLots = $this->createAuctionDateFromLotsDetector()->detectStartClosingDate($auction->Id);
                    if ($auctionStartClosingDateFromLots) {
                        $startClosingDate = $auctionStartClosingDateFromLots;
                    }
                }
            }
        }

        $auctionLot = $this->getAuctionLotLoader()->load($lotItem->Id, $auction->Id);
        if ($auctionLot) {
            log_error("Available auction lot not found" . composeSuffix(['li' => $lotItem->Id, 'a' => $auction->Id]));
            $auctionLotDates = TimedAuctionLotDates::new()
                ->setStartBiddingDate($startBiddingDate)
                ->setStartClosingDate($startClosingDate);
            $this->createAuctionLotDateAssignor()->assignForTimed($auctionLot, $auctionLotDates, $editorUserId);
        } else {
            log_error("Available auction lot not found" . composeSuffix(['li' => $lotItem->Id, 'a' => $auction->Id]));
        }

        return $timedItem;
    }

    /**
     * @param LotItem $lotItem
     * @param LotItem $sourceLotItem
     * @param int $editorUserId
     */
    protected function cloneCategories(LotItem $lotItem, LotItem $sourceLotItem, int $editorUserId): void
    {
        $lotCategoryIds = $this->getLotCategoryLoader()->loadIdsForLot($sourceLotItem->Id);
        foreach ($lotCategoryIds as $lotCategoryId) {
            if (!$this->getLotCategoryExistenceChecker()->existForLot($lotCategoryId, $lotItem->Id)) { // Prevent duplicate
                $this->getLotCategoryLotLinker()->assignCategoryForLot([$lotCategoryId], $lotItem->Id, $editorUserId);
            }
        }
    }

    /**
     * @param LotItem $lotItem
     * @param LotItem $sourceLotItem
     * @param int $editorUserId
     */
    public function cloneIncrements(LotItem $lotItem, LotItem $sourceLotItem, int $editorUserId): void
    {
        foreach ($this->getBidIncrementLoader()->loadForLot($sourceLotItem->Id) as $lotIncrement) {
            $this->getBidIncrementProducer()->create(
                $lotIncrement->Amount,
                $lotIncrement->Increment,
                $editorUserId,
                $lotItem->AccountId,
                null,
                null,
                $lotItem->Id
            );
        }
    }

    /**
     * @param LotItem $lotItem
     * @param LotItem $sourceLotItem
     * @param int $editorUserId
     */
    protected function cloneByersPremium(LotItem $lotItem, LotItem $sourceLotItem, int $editorUserId): void
    {
        $lotPremiums = $this->createBuyersPremiumRangeLoader()->loadBpRangeByLotItemId($sourceLotItem->Id);
        foreach ($lotPremiums as $lotPremium) {
            $this->createBuyersPremiumRangeProducer()->addInLotItemBp(
                $lotPremium->Amount,
                $lotPremium->Fixed,
                $lotPremium->Percent,
                $lotPremium->Mode,
                $editorUserId,
                $lotItem->Id
            );
        }
    }

    /**
     * @param int $editorUserId
     * @param int $accountId
     * @param int $itemNum
     * @param string $itemNumExt
     * @return LotItem
     */
    protected function createLotItem(int $editorUserId, int $accountId, int $itemNum, string $itemNumExt): LotItem
    {
        $fields = $this->getFields();
        if (in_array(Constants\LotCloner::LC_FULL_LOT_ITEM, $fields, true)) {
            $lotItem = clone $this->getSourceLotItem();
            $lotItem->Id = null;
            $lotItem->RowVersion = 0;
            $lotItem->__Restored = false;
        } else {
            $lotItem = $this->createEntityFactory()->lotItem();
        }

        $lotItem->AccountId = $accountId;
        $lotItem->BpRangeCalculation = LotItem::BP_RANGE_CALCULATION_DEFAULT;
        $lotItem->ItemNum = $itemNum;
        $lotItem->ItemNumExt = $itemNumExt;
        $lotItem->toActive(); // default user status
        $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);
        return $lotItem;
    }

    /**
     * @return string[]
     */
    protected function collectCustomFieldNames(): array
    {
        $lotCustomFields = [];
        foreach ($this->createLotCustomFieldLoader()->loadAll() as $lotCustomField) {
            if (!$lotCustomField->Unique) {
                $lotCustomFields[$lotCustomField->Id] = $lotCustomField->Name;
            }
        }
        return $lotCustomFields;
    }

    /**
     * @param string $name
     * @param string[] $lotCustomFieldNames [<licf.id> => <licf.name>]
     * @param LotItem $lotItemOrig
     * @param LotItem $lotItem
     * @param int $editorUserId
     */
    protected function createLotCustomData(
        string $name,
        array $lotCustomFieldNames,
        LotItem $lotItemOrig,
        LotItem $lotItem,
        int $editorUserId
    ): void {
        $lotCustomFieldId = array_search($name, $lotCustomFieldNames, true);
        if (!$lotCustomFieldId) {
            log_warning(
                'Lot custom field id not found'
                . composeSuffix(['search name' => $name, 'all names' => $lotCustomFieldNames])
            );
            return;
        }
        $lotCustomDataOrig = $this->createLotCustomDataLoader()->load($lotCustomFieldId, $lotItemOrig->Id, true);
        if (
            $lotCustomDataOrig
            && $lotCustomDataOrig->Active
        ) {
            $lotCustomData = $this->createEntityFactory()->lotItemCustData();
            $lotCustomData->LotItemId = $lotItem->Id;
            $lotCustomData->LotItemCustFieldId = $lotCustomFieldId;
            $lotCustomData->Active = true;
            $lotCustomData->Numeric = $lotCustomDataOrig->Numeric;
            $lotCustomData->Text = $lotCustomDataOrig->Text;
            $this->getLotItemCustDataWriteRepository()->saveWithModifier($lotCustomData, $editorUserId);
        }
    }
}
