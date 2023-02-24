<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Aggregate class can be used, when we need to operate we several TimedItem entities in one class namespace.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Aggregate;

use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use TimedOnlineItem;

/**
 * Class TimedItemAggregate
 * @package Sam\Storage\Entity\Aggregate
 */
class TimedItemAggregate extends EntityAggregateBase
{
    use TimedItemLoaderAwareTrait;

    private ?int $timedItemId = null;
    private ?TimedOnlineItem $timedItem = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Empty aggregated entities
     * @return static
     */
    public function clear(): static
    {
        $this->timedItemId = null;
        $this->timedItem = null;
        return $this;
    }

    // --- timed_online_item.id ---

    /**
     * @return int|null
     */
    public function getTimedItemId(): ?int
    {
        return $this->timedItemId;
    }

    /**
     * @param int|null $timedItemId
     * @return static
     */
    public function setTimedItemId(?int $timedItemId): static
    {
        $timedItemId = $timedItemId ?: null;
        if ($this->timedItemId !== $timedItemId) {
            $this->clear();
        }
        $this->timedItemId = $timedItemId;
        return $this;
    }

    // --- TimedOnlineItem ---

    /**
     * @return bool
     */
    public function hasTimedItem(): bool
    {
        return ($this->timedItem !== null);
    }

    /**
     * Return TimedOnlineItem object
     * @param bool $isReadOnlyDb
     * @return TimedOnlineItem|null
     */
    public function getTimedItem(bool $isReadOnlyDb = false): ?TimedOnlineItem
    {
        if ($this->timedItem === null) {
            $this->timedItem = $this->getTimedItemLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadById($this->timedItemId, $isReadOnlyDb);
        }
        return $this->timedItem;
    }

    /**
     * @param TimedOnlineItem|null $timedItem
     * @return static
     */
    public function setTimedItem(?TimedOnlineItem $timedItem = null): static
    {
        if (!$timedItem) {
            $this->clear();
        } elseif ($timedItem->Id !== $this->timedItemId) {
            $this->clear();
            $this->timedItemId = $timedItem->Id;
        }
        $this->timedItem = $timedItem;
        return $this;
    }
}
