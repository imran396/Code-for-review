<?php
/**
 * SAM-6181 : Refactor for Admin>Auction>Enter bids - Move input validation logic to separate class and implement unit test
 * https://bidpath.atlassian.net/browse/SAM-6181
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01/25/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\Internal\Validate;

use Auction;
use AuctionBidder;
use AuctionLotItem;
use LotItem;
use Sam\Auction\Load\AuctionLoader;
use Sam\AuctionLot\Load\AuctionLotLoader;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\TimedItemLoader;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\AuctionLot\LotNo\Parse\LotNoParserCreateTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoader;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollector;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Lot\Date\TimedLotDateDetectorCreateTrait;
use Sam\Lot\Load\LotItemLoader;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\AuctionEnterBidConstants;
use Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\Validate\AuctionEnterBidInputDto;
use TimedOnlineItem;

/**
 * Class ValidationHelper
 * @package Sam\View\Admin\Form\AuctionEnterBidForm\EnterBid\Internal\Validate
 */
class BaseValidationHelper extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use CurrentDateTrait;
    use LotItemLoaderAwareTrait;
    use LotNoParserCreateTrait;
    use NumberFormatterAwareTrait;
    use OptionalsTrait;
    use ResultStatusCollectorAwareTrait;
    use TimedItemLoaderAwareTrait;
    use TimedLotDateDetectorCreateTrait;

    public const OP_AUCTION = OptionalKeyConstants::KEY_AUCTION; // ?Auction
    public const OP_AUCTION_BIDDER = 'auctionBidder'; // ?AuctionBidder
    public const OP_AUCTION_LOT = OptionalKeyConstants::KEY_AUCTION_LOT; // ?AuctionLotItem
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_LOT_ITEM = OptionalKeyConstants::KEY_LOT_ITEM; // ?LotItem
    public const OP_TIMED_ITEM = 'timedOnlineItem'; // TimedOnlineItem

    // --- Incoming values ---
    protected ?AuctionLotItem $auctionLot = null;
    protected int $auctionId;
    protected AuctionEnterBidInputDto $dto;

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param ResultStatusCollector $collector
     * @param AuctionEnterBidInputDto $dto
     * @param int $auctionId
     * @param array $optionals
     * @return $this
     */
    public function construct(
        ResultStatusCollector $collector,
        AuctionEnterBidInputDto $dto,
        int $auctionId,
        array $optionals = []
    ): static {
        $this->setResultStatusCollector($collector);
        $this->dto = $dto;
        $this->auctionId = $auctionId;
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @return bool
     */
    public function validateLotFullNumber(): bool
    {
        $lotNoParser = $this->createLotNoParser()->construct();
        $isValidLotFullNum = $lotNoParser->validate($this->dto->lotFullNo);
        if ($isValidLotFullNum) {
            $lotNoParsed = $lotNoParser->parse($this->dto->lotFullNo);
            $this->dto->lotNum = (string)$lotNoParsed->lotNum;
            $this->dto->lotNumExt = $lotNoParsed->lotNumExtension;
            $this->dto->lotNumPrefix = $lotNoParsed->lotNumPrefix;
        } else {
            $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_FULL_LOT_NO, $lotNoParser->getErrorMessage());
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function validateLotNumber(): bool
    {
        if ($this->dto->lotNum === '') {
            $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_LOT_NO_REQUIRED);
            return false;
        }
        if (!NumberValidator::new()->isInt($this->dto->lotNum)) {
            $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_LOT_NO_NUMERIC);
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function validateAuctionLotExistence(): bool
    {
        $this->auctionLot = $this->fetchOptional(
            self::OP_AUCTION_LOT,
            [
                Cast::toInt($this->dto->lotNum),
                $this->dto->lotNumExt,
                $this->dto->lotNumPrefix,
            ]
        );
        if (!$this->auctionLot) {
            $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_LOT_NOT_EXIST);
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function validateIsListingOnly(): bool
    {
        if ($this->auctionLot->ListingOnly) {
            $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_LISTING_ONLY);
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function validateLotAlreadySold(): bool
    {
        /** @var LotItem|null $lotItem */
        $lotItem = $this->fetchOptional(self::OP_LOT_ITEM, [$this->auctionLot->LotItemId]);
        if (
            $lotItem
            && (
                $lotItem->hasWinningBidder()
                || $lotItem->hasHammerPrice()
            )
        ) {
            $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_ALREADY_SOLD);
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function validateBidderNumber(): bool
    {
        if (!$this->dto->bidderNum) {
            $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_BIDDER_NO_REQUIRED);
            return false;
        }

        if (!preg_match('/^\w*$/', $this->dto->bidderNum)) {
            $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_BIDDER_NO_ALPHA_NUMERIC);
            return false;
        }

        /** @var AuctionBidder|null $auctionBidder */
        $auctionBidder = $this->fetchOptional(self::OP_AUCTION_BIDDER);
        if (!$auctionBidder) {
            $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_INVALID_USER);
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function validateTimedItem(): bool
    {
        /** @var Auction $auction */
        $auction = $this->fetchOptional(self::OP_AUCTION);
        if ($auction->isTimed()) {
            // for timed online items, check the item start date
            $nowGmt = $this->getCurrentDateUtc();
            [$startDateGmt,] = $this->createTimedLotDateDetector()->detect($this->auctionLot);
            if ($nowGmt->getTimestamp() < $startDateGmt->getTimestamp()) {
                $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_NOT_STARTED);
                return false;
            }
            /** @var TimedOnlineItem $timedItem */
            $timedItem = $this->fetchOptional(self::OP_TIMED_ITEM, [$this->auctionLot->LotItemId]);
            if ($timedItem->NoBidding) {
                $this->getResultStatusCollector()->addError(AuctionEnterBidConstants::ERR_NOT_FOR_BIDDING);
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $auctionId = $this->auctionId;
        $isReadOnlyDb = $optionals[self::OP_IS_READ_ONLY_DB] ?? true;
        $bidderNumberPad = $this->getBidderNumberPadding()->add($this->dto->bidderNum);

        $optionals[self::OP_AUCTION_LOT] = $optionals[self::OP_AUCTION_LOT]
            ?? static function (?int $lotNo, string $lotNumExt, string $lotNumPrefix) use (
                $auctionId,
                $isReadOnlyDb
            ): ?AuctionLotItem {
                return AuctionLotLoader::new()->loadByLotNo($lotNo, $lotNumExt, $lotNumPrefix, $auctionId, $isReadOnlyDb);
            };

        $optionals[self::OP_LOT_ITEM] = $optionals[self::OP_LOT_ITEM]
            ?? static function (int $lotItemId) use ($isReadOnlyDb): ?LotItem {
                return LotItemLoader::new()->load($lotItemId, $isReadOnlyDb);
            };

        $optionals[self::OP_AUCTION_BIDDER] = $optionals[self::OP_AUCTION_BIDDER]
            ?? static function () use ($bidderNumberPad, $auctionId, $isReadOnlyDb): ?AuctionBidder {
                return AuctionBidderLoader::new()->loadByBidderNum($bidderNumberPad, $auctionId, $isReadOnlyDb);
            };

        $optionals[self::OP_TIMED_ITEM] = $optionals[self::OP_TIMED_ITEM]
            ?? static function (int $lotItemId) use ($auctionId, $isReadOnlyDb): TimedOnlineItem {
                return TimedItemLoader::new()->loadOrCreate($lotItemId, $auctionId, $isReadOnlyDb);
            };

        $optionals[self::OP_AUCTION] = $optionals[self::OP_AUCTION]
            ?? static function () use ($auctionId, $isReadOnlyDb): ?Auction {
                return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
            };

        $this->setOptionals($optionals);
    }
}
