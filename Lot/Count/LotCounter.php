<?php
/**
 * It logic is also used for total auction (all and active) lot count calculation,
 * stored in AuctionCache (TotalLots, TotalActiveLots). Then we should disable UseCached.
 *
 * SAM-5153: Lot counter
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           08.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Lot\Count;

use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;

/**
 * Class LotCounter
 * @package Sam\Lot\Count
 */
class LotCounter extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionCacheLoaderAwareTrait;
    use AuctionLotItemReadRepositoryCreateTrait;

    /** @var bool */
    private bool $isOnlyOngoing = false;
    /** @var bool */
    private bool $isUseCached = true;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        parent::initInstance();
        return $this;
    }

    /**
     * Count auction lots
     * @return int
     */
    public function count(): int
    {
        $auction = $this->getAuction();
        if (!$auction) {
            log_error("Auction not found in auction lot counter" . composeSuffix(['a' => $this->getAuctionId()]));
            return 0;
        }

        // Use cached value if allowed (default behavior):
        if ($this->isUseCached()) {
            $auctionCache = $this->getAuctionCache();
            if ($auctionCache) {
                $cachedValue = $this->isOnlyOngoing() ? $auctionCache->TotalActiveLots : $auctionCache->TotalLots;
                // cached value exists (we should have cached value always, but if cache were removed for some reason -
                // handle this correctly):
                if ($cachedValue !== null) {
                    return $cachedValue;
                }
            }
        }

        // Calculate new value via db call:
        $repo = $this->createAuctionLotItemReadRepository()
            ->joinLotItemFilterActive(true)
            ->filterAuctionId($auction->Id);

        if ($this->isOnlyOngoing()) {
            $repo->filterLotStatusId(Constants\Lot::LS_ACTIVE);
            if ($auction->isTimed()) {
                $repo->filterTimedLotEndDateInFuture()
                    ->joinAuction()
                    ->joinAuctionDynamic()
                    ->joinAuctionLotItemCache();
            }
        } else {
            $repo->filterLotStatusId(Constants\Lot::$availableLotStatuses);
        }

        return $repo->count();
    }

    /**
     * @return bool
     */
    public function isOnlyOngoing(): bool
    {
        return $this->isOnlyOngoing;
    }

    /**
     * Enable onlyOngoing
     * @param bool $enabled
     * @return static
     */
    public function enableOnlyOngoing(bool $enabled): static
    {
        $this->isOnlyOngoing = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUseCached(): bool
    {
        return $this->isUseCached;
    }

    /**
     * Fetch values from AuctionCache record, if exists, or calculate count
     * @param bool $enabled
     * @return static
     */
    public function enableUseCached(bool $enabled): static
    {
        $this->isUseCached = $enabled;
        return $this;
    }
}
