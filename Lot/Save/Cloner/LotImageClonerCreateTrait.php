<?php
/**
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Save\Cloner;

/**
 * Trait LotImageClonerCreateTrait
 * @package Sam\Lot\Save\Cloner
 */
trait LotImageClonerCreateTrait
{
    protected ?LotImageCloner $lotImageCloner = null;

    /**
     * @return LotImageCloner
     */
    protected function createLotImageCloner(): LotImageCloner
    {
        return $this->lotImageCloner ?: LotImageCloner::new();
    }

    /**
     * @param LotImageCloner $lotImageCloner
     * @return static
     * @internal
     */
    public function setLotImageCloner(LotImageCloner $lotImageCloner): static
    {
        $this->lotImageCloner = $lotImageCloner;
        return $this;
    }
}
