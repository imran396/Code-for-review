<?php
/** @noinspection PhpUnused */

/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/13/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use Sam\Storage\Entity\Aggregate\TimedItemAggregate;
use TimedOnlineItem;

/**
 * Trait TimedItemAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait TimedItemAwareTrait
{
    protected ?TimedItemAggregate $timedItemAggregate = null;

    /**
     * @return int|null
     */
    public function getTimedItemId(): ?int
    {
        return $this->getTimedItemAggregate()->getTimedItemId();
    }

    /**
     * @param int|null $timedItemId null for absent entity
     * @return static
     */
    public function setTimedItemId(?int $timedItemId): static
    {
        $this->getTimedItemAggregate()->setTimedItemId($timedItemId);
        return $this;
    }

    // --- TimedOnlineItem ---

    /**
     * Return TimedItem|null object
     * @param bool $isReadOnlyDb
     * @return TimedOnlineItem|null
     */
    public function getTimedItem(bool $isReadOnlyDb = false): ?TimedOnlineItem
    {
        return $this->getTimedItemAggregate()->getTimedItem($isReadOnlyDb);
    }

    /**
     * @param TimedOnlineItem|null $timedItem
     * @return static
     */
    public function setTimedItem(?TimedOnlineItem $timedItem): static
    {
        $this->getTimedItemAggregate()->setTimedItem($timedItem);
        return $this;
    }

    // --- TimedItemAggregate ---

    /**
     * @return TimedItemAggregate
     */
    protected function getTimedItemAggregate(): TimedItemAggregate
    {
        if ($this->timedItemAggregate === null) {
            $this->timedItemAggregate = TimedItemAggregate::new();
        }
        return $this->timedItemAggregate;
    }
}
