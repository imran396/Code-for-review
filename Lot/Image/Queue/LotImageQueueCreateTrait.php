<?php
/**
 * SAM-7845: Refactor \Lot_Image class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Queue;

/**
 * Trait LotImageQueueCreateTrait
 * @package Sam\Lot\Image\Queue
 */
trait LotImageQueueCreateTrait
{
    protected ?LotImageQueue $lotImageQueue = null;

    /**
     * @return LotImageQueue
     */
    protected function createLotImageQueue(): LotImageQueue
    {
        return $this->lotImageQueue ?: LotImageQueue::new();
    }

    /**
     * @param LotImageQueue $lotImageQueue
     * @return static
     * @internal
     */
    public function setLotImageQueue(LotImageQueue $lotImageQueue): static
    {
        $this->lotImageQueue = $lotImageQueue;
        return $this;
    }
}
