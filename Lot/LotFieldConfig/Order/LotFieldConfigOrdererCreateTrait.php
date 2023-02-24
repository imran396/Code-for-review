<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Order;

/**
 * Trait LotFieldConfigOrdererCreateTrait
 * @package Sam\Lot\LotFieldConfig\Order
 */
trait LotFieldConfigOrdererCreateTrait
{
    /**
     * @var LotFieldConfigOrderer|null
     */
    protected ?LotFieldConfigOrderer $lotFieldConfigOrderer = null;

    /**
     * @return LotFieldConfigOrderer
     */
    protected function createLotFieldConfigOrderer(): LotFieldConfigOrderer
    {
        return $this->lotFieldConfigOrderer ?: LotFieldConfigOrderer::new();
    }

    /**
     * @param LotFieldConfigOrderer $lotFieldConfigOrderer
     * @return static
     * @internal
     */
    public function setLotFieldConfigOrderer(LotFieldConfigOrderer $lotFieldConfigOrderer): static
    {
        $this->lotFieldConfigOrderer = $lotFieldConfigOrderer;
        return $this;
    }
}
