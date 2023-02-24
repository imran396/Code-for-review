<?php
/**
 * SAM-6697: Lot deleters for v3.5 https://bidpath.atlassian.net/browse/SAM-6697
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Delete\TimedItem;

/**
 * Trait TimedItemDeleterCreateTrait
 * @package Sam\AuctionLot\Delete\TimedItem
 */
trait TimedItemDeleterCreateTrait
{
    protected ?TimedItemDeleter $timedItemDeleter = null;

    /**
     * @return TimedItemDeleter
     */
    protected function createTimedItemDeleter(): TimedItemDeleter
    {
        return $this->timedItemDeleter ?: TimedItemDeleter::new();
    }

    /**
     * @param TimedItemDeleter $timedItemDeleter
     * @return $this
     * @internal
     */
    public function setTimedItemDeleter(TimedItemDeleter $timedItemDeleter): static
    {
        $this->timedItemDeleter = $timedItemDeleter;
        return $this;
    }
}
