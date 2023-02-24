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

namespace Sam\Lot\LotFieldConfig\Delete;

/**
 * Trait LotFieldConfigDeleterCreateTrait
 * @package Sam\Lot\LotFieldConfig\Delete
 */
trait LotFieldConfigDeleterCreateTrait
{
    /**
     * @var LotFieldConfigDeleter|null
     */
    protected ?LotFieldConfigDeleter $lotFieldConfigDeleter = null;

    /**
     * @return LotFieldConfigDeleter
     */
    protected function createLotFieldConfigDeleter(): LotFieldConfigDeleter
    {
        return $this->lotFieldConfigDeleter ?: LotFieldConfigDeleter::new();
    }

    /**
     * @param LotFieldConfigDeleter $lotFieldConfigDeleter
     * @return static
     * @internal
     */
    public function setLotFieldConfigDeleter(LotFieldConfigDeleter $lotFieldConfigDeleter): static
    {
        $this->lotFieldConfigDeleter = $lotFieldConfigDeleter;
        return $this;
    }
}
