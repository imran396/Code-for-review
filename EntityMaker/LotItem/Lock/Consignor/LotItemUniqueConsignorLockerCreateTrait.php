<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\Consignor;

/**
 * Trait LotItemUniqueConsignorLockerCreateTrait
 * @package Sam\EntityMaker\LotItem
 */
trait LotItemUniqueConsignorLockerCreateTrait
{
    protected ?LotItemUniqueConsignorLocker $lotItemUniqueConsignorLocker = null;

    /**
     * @return LotItemUniqueConsignorLocker
     */
    protected function createLotItemUniqueConsignorLocker(): LotItemUniqueConsignorLocker
    {
        return $this->lotItemUniqueConsignorLocker ?: LotItemUniqueConsignorLocker::new();
    }

    /**
     * @param LotItemUniqueConsignorLocker $lotItemUniqueConsignorLocker
     * @return static
     * @internal
     */
    public function setLotItemUniqueConsignorLocker(LotItemUniqueConsignorLocker $lotItemUniqueConsignorLocker): static
    {
        $this->lotItemUniqueConsignorLocker = $lotItemUniqueConsignorLocker;
        return $this;
    }
}
