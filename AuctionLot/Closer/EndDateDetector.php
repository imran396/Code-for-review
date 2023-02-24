<?php
/**
 * Lot End / Sold date detection, that happens on timed lot closing
 * To use this class, you need to initialize CloserSign value and define auction.id, lot_item.id.
 * It is possible, when you set AuctionLot, or TimedItem,
 * or Auction & LotItem both, or AuctionId & LotItemId both.
 *
 * Related tickets:
 * SAM-3899: Lot End and Sold dates adjustments
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Sep 24, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Closer;

use Auction;
use AuctionCache;
use AuctionDynamic;
use AuctionLotItem;
use AuctionLotItemCache;
use DateTime;
use InvalidArgumentException;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Auction\Load\AuctionDynamicLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotCacheLoaderAwareTrait;
use Sam\AuctionLot\StaggerClosing\StaggerClosingHelperCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class EndDateDetector
 * @package Sam\AuctionLot\Closer
 */
class EndDateDetector extends CustomizableClass
{
    use AuctionCacheLoaderAwareTrait;
    use AuctionDynamicLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotCacheLoaderAwareTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use CurrentDateTrait;
    use StaggerClosingHelperCreateTrait;
    use TimezoneLoaderAwareTrait;

    protected ?string $closeBidType = null;
    protected ?int $lotItemId = null;
    protected ?int $auctionId = null;
    protected ?Auction $auction = null;
    /**
     * @var AuctionCache|null
     */
    protected ?AuctionCache $auctionCache = null;
    /**
     * @var AuctionDynamic|null
     */
    protected ?AuctionDynamic $auctionDynamic = null;
    /**
     * @var AuctionLotItem|null
     */
    protected ?AuctionLotItem $auctionLot = null;
    /**
     * @var AuctionLotItemCache|null
     */
    protected ?AuctionLotItemCache $auctionLotCache = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect Lot Sold and Lot End dates
     * @param int $lotItemId
     * @param int $auctionId
     * @return array
     * @noinspection PhpUnused
     */
    public function detectDates(int $lotItemId, int $auctionId): array
    {
        $this->setLotItemId($lotItemId);
        $this->setAuctionId($auctionId);
        $lotSoldDate = $this->detectLotSoldDate();
        $lotEndDate = $this->detectLotEndDate();
        return [$lotSoldDate, $lotEndDate];
    }

    /**
     * Detects Lot Sold date in UTC (li.date_sold)
     * @return DateTime|null
     */
    public function detectLotSoldDate(): ?DateTime
    {
        if ($this->getCloseBidType() === Constants\BidTransaction::TYPE_REGULAR) {
            if ($this->getAuction()->ExtendAll) {
                $soldDate = $this->detectLotSoldDateForExtendAll();
            } else {
                $soldDate = $this->getAuctionLotCache()->EndDate;
            }
        } else {  // Constants\TimedLot::BuyNow, Constants\TimedLot::Offer
            $soldDate = $this->getCurrentDateUtc();
        }
        return $soldDate;
    }

    /**
     * Detected Lot End date is assigned on lot closing (ali.end_date)
     * @return DateTime
     */
    public function detectLotEndDate(): DateTime
    {
        if ($this->getAuction()->ExtendAll) {
            $endDate = $this->detectLotSoldDateForExtendAll();
        } else {
            $endDate = $this->getAuctionLot()->EndDate;
        }
        return $endDate;
    }

    /**
     * Determine date assigned to lot_item.sold_date
     * @return DateTime|null
     */
    protected function detectLotSoldDateForExtendAll(): ?DateTime
    {
        if ($this->getAuction()->StaggerClosing) {
            $soldDate = $this->createStaggerClosingHelper()
                ->calcEndDate(
                    $this->getAuctionDynamic()->ExtendAllStartClosingDate,
                    $this->getAuction()->LotsPerInterval,
                    $this->getAuction()->StaggerClosing,
                    $this->getAuctionLot()->Order
                );
        } else {
            $soldDate = $this->getAuction()->EndDate;
        }
        return $soldDate;
    }

    /**
     * @return int
     */
    public function getLotItemId(): int
    {
        if (!$this->lotItemId) {
            throw new InvalidArgumentException('Unknown lot item id');
        }
        return $this->lotItemId;
    }

    /**
     * @param int $lotItemId
     * @return static
     */
    public function setLotItemId(int $lotItemId): static
    {
        $this->lotItemId = $lotItemId;
        return $this;
    }

    /**
     * @return int
     */
    public function getAuctionId(): int
    {
        if (!$this->auctionId) {
            throw new InvalidArgumentException('Unknown auction id');
        }
        return $this->auctionId;
    }

    /**
     * @param int $auctionId
     * @return static
     */
    public function setAuctionId(int $auctionId): static
    {
        $this->auctionId = $auctionId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCloseBidType(): ?string
    {
        return $this->closeBidType;
    }

    /**
     * @param string|null $closeBidType
     * @return static
     */
    public function setCloseBidType(?string $closeBidType): static
    {
        $this->closeBidType = Cast::toString($closeBidType, Constants\BidTransaction::$types);
        return $this;
    }

    /**
     * @return Auction|null
     */
    public function getAuction(): ?Auction
    {
        if (!$this->auction) {
            $this->auction = $this->getAuctionLoader()->load($this->getAuctionId());
        }
        return $this->auction;
    }

    /**
     * @param Auction $auction
     * @return static
     */
    public function setAuction(Auction $auction): static
    {
        $this->auction = $auction;
        $this->setAuctionId($auction->Id);
        return $this;
    }

    /**
     * @return AuctionCache|null
     */
    public function getAuctionCache(): ?AuctionCache
    {
        if (!$this->auctionCache) {
            $this->auctionCache = $this->getAuctionCacheLoader()->load($this->getAuctionId());
        }
        return $this->auctionCache;
    }

    /**
     * @param AuctionCache $auctionCache
     * @return static
     * @noinspection PhpUnused
     */
    public function setAuctionCache(AuctionCache $auctionCache): static
    {
        $this->auctionCache = $auctionCache;
        $this->setAuctionId($auctionCache->AuctionId);
        return $this;
    }

    /**
     * @return AuctionDynamic
     */
    public function getAuctionDynamic(): AuctionDynamic
    {
        if (!$this->auctionDynamic) {
            $this->auctionDynamic = $this->getAuctionDynamicLoader()->loadOrCreate($this->getAuctionId(), true);
        }
        return $this->auctionDynamic;
    }

    /**
     * @param AuctionDynamic $auctionDynamic
     * @return static
     */
    public function setAuctionDynamic(AuctionDynamic $auctionDynamic): static
    {
        $this->auctionDynamic = $auctionDynamic;
        $this->setAuctionId($auctionDynamic->AuctionId);
        return $this;
    }

    /**
     * @return AuctionLotItem|null
     */
    public function getAuctionLot(): ?AuctionLotItem
    {
        if (!$this->auctionLot) {
            $this->auctionLot = $this->createAuctionLotItemReadRepository()
                ->enableReadOnlyDb(true)
                ->filterLotItemId($this->getLotItemId())
                ->filterAuctionId($this->getAuctionId())
                ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
                ->loadEntity();
        }
        return $this->auctionLot;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return static
     */
    public function setAuctionLot(AuctionLotItem $auctionLot): static
    {
        $this->auctionLot = $auctionLot;
        $this->setLotItemId($auctionLot->LotItemId);
        $this->setAuctionId($auctionLot->AuctionId);
        return $this;
    }

    /**
     * @return AuctionLotItemCache
     */
    public function getAuctionLotCache(): AuctionLotItemCache
    {
        if (!$this->auctionLotCache) {
            $this->auctionLotCache = $this->getAuctionLotCacheLoader()->loadById($this->getAuctionLot()->Id, true);
        }
        return $this->auctionLotCache;
    }

    /**
     * @param AuctionLotItemCache $auctionLotCache
     * @return static
     * @noinspection PhpUnused
     */
    public function setAuctionLotCache(AuctionLotItemCache $auctionLotCache): static
    {
        $this->auctionLotCache = $auctionLotCache;
        return $this;
    }
}
