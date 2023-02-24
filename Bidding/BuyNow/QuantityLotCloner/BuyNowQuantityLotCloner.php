<?php
/**
 * This class implements cloning a lot when buying now with a selected quantity.
 * If the selected quantity is lower than available, we clone the original lot and sell a cloned lot to the buyer.
 *
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BuyNow\QuantityLotCloner;

use Auction;
use DateInterval;
use LotItem;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Bidding\BuyNow\QuantityLotCloner\Internal\BuyNowItemNumCloneStrategyCreateTrait;
use Sam\Bidding\BuyNow\QuantityLotCloner\Internal\BuyNowLotNumCloneStrategyCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Lot\Save\LotClonerCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;

/**
 * Lot cloner for the BuyNowSelectQuantity feature
 *
 * Class BuyNowQuantityLotCloner
 * @package Sam\Bidding\BuyNow\QuantityLotCloner
 */
class BuyNowQuantityLotCloner extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BuyNowItemNumCloneStrategyCreateTrait;
    use BuyNowLotNumCloneStrategyCreateTrait;
    use CurrentDateTrait;
    use LotClonerCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use OptionalsTrait;

    public const OP_CLONE_IMAGE_STRATEGY = 'cloneImageStrategy';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Clone lot item and auction lot and set selected quantity
     *
     * @param LotItem $lotItem
     * @param Auction $auction
     * @param int $quantity
     * @param int $editorUserId
     * @return LotItem
     */
    public function clone(LotItem $lotItem, Auction $auction, int $quantity, int $editorUserId): LotItem
    {
        $clonedLotItem = $this->cloneLot($lotItem, $auction, $editorUserId);
        $this->fillClonedAuctionLot($clonedLotItem->Id, $lotItem->Id, $auction->Id, $quantity, $editorUserId);
        return $clonedLotItem;
    }

    /**
     * @param LotItem $sourceLotItem
     * @param Auction $auction
     * @param int $editorUserId
     * @return LotItem
     */
    protected function cloneLot(LotItem $sourceLotItem, Auction $auction, int $editorUserId): LotItem
    {
        $fields = $this->makeLotClonerFieldList();
        $lotCloner = $this->createLotCloner();
        $lotCloner
            ->setAccountId($auction->AccountId)
            ->setSourceAuction($auction)
            ->setTargetAuction($auction)
            ->setSourceLotItem($sourceLotItem)
            ->setFields($fields)
            ->setItemNumCloneStrategy($this->createBuyNowItemNumCloneStrategy());
        $clonedLotItem = $lotCloner->cloneLot($editorUserId);
        return $clonedLotItem;
    }

    /**
     * Determine which fields to clone
     *
     * @return array
     */
    protected function makeLotClonerFieldList(): array
    {
        $fields = [
            Constants\LotCloner::LC_FULL_LOT_ITEM,
            Constants\LotCloner::LC_FULL_AUCTION_LOT,
            Constants\LotCloner::LC_CATEGORY,
            Constants\LotCloner::LC_BUYERS_PREMIUM,
        ];

        $imageCloneStrategy = $this->fetchOptional(self::OP_CLONE_IMAGE_STRATEGY);
        if ($imageCloneStrategy === Constants\BuyNow::BUY_NOW_CLONE_IMAGE_FIRST) {
            $fields[] = Constants\LotCloner::LC_IMAGE_DEFAULT;
        } elseif ($imageCloneStrategy === Constants\BuyNow::BUY_NOW_CLONE_IMAGE_ALL) {
            $fields[] = Constants\LotCloner::LC_IMAGES;
        }

        $customFields = $this->createLotCustomFieldLoader()->loadAll();
        foreach ($customFields as $customField) {
            if (!$customField->Unique) {
                $fields[] = $customField->Name;
            }
        }
        return $fields;
    }

    /**
     * Set selected quantity to cloned auction lot,
     * set lot# based on clone strategy,
     * unpublish lot
     *
     * @param int $clonedLotItemId
     * @param int $sourceLotItemId
     * @param int $auctionId
     * @param int $quantity
     * @param int $editorUserId
     */
    protected function fillClonedAuctionLot(
        int $clonedLotItemId,
        int $sourceLotItemId,
        int $auctionId,
        int $quantity,
        int $editorUserId
    ): void {
        $clonedAuctionLot = $this->getAuctionLotLoader()->load($clonedLotItemId, $auctionId);
        if (!$clonedAuctionLot) {
            log_error("Available cloned AuctionLot not found" . composeSuffix(['li' => $clonedLotItemId, 'a' => $auctionId]));
            return;
        }

        $sourceAuctionLot = $this->getAuctionLotLoader()->load($sourceLotItemId, $auctionId);
        if (!$sourceAuctionLot) {
            log_error("Available source AuctionLot not found" . composeSuffix(['li' => $sourceLotItemId, 'a' => $auctionId]));
            return;
        }

        $clonedLotNum = $this->createBuyNowLotNumCloneStrategy()
            ->detectCloneLotNum(
                $auctionId,
                $sourceAuctionLot->LotNumPrefix,
                $sourceAuctionLot->LotNum,
                $sourceAuctionLot->LotNumExt
            );
        [$clonedAuctionLot->LotNumPrefix, $clonedAuctionLot->LotNum, $clonedAuctionLot->LotNumExt] = $clonedLotNum;

        $clonedAuctionLot->Quantity = (float)$quantity;
        $clonedAuctionLot->UnpublishDate = $this->getCurrentDateUtc()->sub(new DateInterval('PT1M'));
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($clonedAuctionLot, $editorUserId);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_CLONE_IMAGE_STRATEGY] = $optionals[self::OP_CLONE_IMAGE_STRATEGY]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->bidding->buyNow->timed->selectQuantity->imageCloneStrategy');
            };
        $this->setOptionals($optionals);
    }
}
