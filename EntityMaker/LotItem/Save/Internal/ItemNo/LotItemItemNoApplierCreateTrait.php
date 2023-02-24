<?php
/**
 * SAM-10599: Supply uniqueness of lot item fields: item# - Adjust item# auto-assignment with internal locking
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Save\Internal\ItemNo;

/**
 * Trait LotItemItemNoApplierCreateTrait
 * @package Sam\EntityMaker\LotItem\Save\Internal\ItemNo
 */
trait LotItemItemNoApplierCreateTrait
{
    protected ?LotItemItemNoApplier $lotItemItemNoApplier = null;

    /**
     * @return LotItemItemNoApplier
     */
    protected function createLotItemItemNoApplier(): LotItemItemNoApplier
    {
        return $this->lotItemItemNoApplier ?: LotItemItemNoApplier::new();
    }

    /**
     * @param LotItemItemNoApplier $lotItemItemNoApplier
     * @return $this
     * @internal
     */
    public function setLotItemItemNoApplier(LotItemItemNoApplier $lotItemItemNoApplier): static
    {
        $this->lotItemItemNoApplier = $lotItemItemNoApplier;
        return $this;
    }
}
