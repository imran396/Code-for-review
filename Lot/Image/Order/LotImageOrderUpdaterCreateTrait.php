<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Order;

/**
 * Trait LotImageOrderUpdaterCreateTrait
 * @package Sam\Lot\Image\Order
 */
trait LotImageOrderUpdaterCreateTrait
{
    protected ?LotImageOrderUpdater $lotImageOrderUpdater = null;

    /**
     * @return LotImageOrderUpdater
     */
    protected function createLotImageOrderUpdater(): LotImageOrderUpdater
    {
        return $this->lotImageOrderUpdater ?: LotImageOrderUpdater::new();
    }

    /**
     * @param LotImageOrderUpdater $lotImageOrderUpdater
     * @return static
     * @internal
     */
    public function setLotImageOrderUpdater(LotImageOrderUpdater $lotImageOrderUpdater): static
    {
        $this->lotImageOrderUpdater = $lotImageOrderUpdater;
        return $this;
    }
}
