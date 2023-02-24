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

namespace Sam\Lot\Image\BucketImport\Associate\Order;

/**
 * Trait LotImageAssociateOrderAdviserCreateTrait
 * @package Sam\Lot\Image\BucketImport\Associate\Order
 */
trait LotImageAssociateOrderAdviserCreateTrait
{
    protected ?LotImageAssociateOrderAdviser $lotImageAssociateOrderAdviser = null;

    /**
     * @return LotImageAssociateOrderAdviser
     */
    protected function createLotImageAssociateOrderAdviser(): LotImageAssociateOrderAdviser
    {
        return $this->lotImageAssociateOrderAdviser ?: LotImageAssociateOrderAdviser::new();
    }

    /**
     * @param LotImageAssociateOrderAdviser $lotImageAssociateOrderAdviser
     * @return static
     * @internal
     */
    public function setLotImageAssociateOrderAdviser(LotImageAssociateOrderAdviser $lotImageAssociateOrderAdviser): static
    {
        $this->lotImageAssociateOrderAdviser = $lotImageAssociateOrderAdviser;
        return $this;
    }
}
