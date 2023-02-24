<?php
/**
 * SAM-10557: Supply uniqueness of lot item fields: item#, unique lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\ItemNo;

/**
 * Trait LotItemUniqueItemNoLockerCreateTrait
 * @package Sam\EntityMaker\LotItem
 */
trait LotItemUniqueItemNoLockerCreateTrait
{
    protected ?LotItemUniqueItemNoLocker $lotItemUniqueItemNoLocker = null;

    /**
     * @return LotItemUniqueItemNoLocker
     */
    protected function createLotItemUniqueItemNoLocker(): LotItemUniqueItemNoLocker
    {
        return $this->lotItemUniqueItemNoLocker ?: LotItemUniqueItemNoLocker::new();
    }

    /**
     * @param LotItemUniqueItemNoLocker $lotItemUniqueItemNoLocker
     * @return $this
     * @internal
     */
    public function setLotItemUniqueItemNoLocker(LotItemUniqueItemNoLocker $lotItemUniqueItemNoLocker): static
    {
        $this->lotItemUniqueItemNoLocker = $lotItemUniqueItemNoLocker;
        return $this;
    }
}
