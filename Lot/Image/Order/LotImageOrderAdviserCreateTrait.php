<?php
/**
 * SAM-7912: Refactor \LotImage_Orderer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Order;

/**
 * Trait LotImageOrderAdviserCreateTrait
 * @package Sam\Lot\Image\Order
 */
trait LotImageOrderAdviserCreateTrait
{
    protected ?LotImageOrderAdviser $lotImageOrderAdviser = null;

    /**
     * @return LotImageOrderAdviser
     */
    protected function createLotImageOrderAdviser(): LotImageOrderAdviser
    {
        return $this->lotImageOrderAdviser ?: LotImageOrderAdviser::new();
    }

    /**
     * @param LotImageOrderAdviser $lotImageOrderAdviser
     * @return static
     * @internal
     */
    public function setLotImageOrderAdviser(LotImageOrderAdviser $lotImageOrderAdviser): static
    {
        $this->lotImageOrderAdviser = $lotImageOrderAdviser;
        return $this;
    }
}
