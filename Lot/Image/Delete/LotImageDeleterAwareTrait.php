<?php
/**
 * SAM-4464: Apply Lot Image modules
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Delete;

/**
 * Trait LotImageDeleterAwareTrait
 * @package Sam\Lot\Image\Delete
 */
trait LotImageDeleterAwareTrait
{
    /**
     * @var LotImageDeleter|null
     */
    protected ?LotImageDeleter $lotImageDeleter = null;

    /**
     * @return LotImageDeleter
     */
    protected function getLotImageDeleter(): LotImageDeleter
    {
        if ($this->lotImageDeleter === null) {
            $this->lotImageDeleter = LotImageDeleter::new();
        }
        return $this->lotImageDeleter;
    }

    /**
     * @param LotImageDeleter $lotImageDeleter
     * @return static
     * @internal
     */
    public function setLotImageDeleter(LotImageDeleter $lotImageDeleter): static
    {
        $this->lotImageDeleter = $lotImageDeleter;
        return $this;
    }
}
