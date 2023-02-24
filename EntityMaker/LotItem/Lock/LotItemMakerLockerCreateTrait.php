<?php
/**
 * SAM-10557: Supply uniqueness of lot item fields: item#, unique lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock;

/**
 * Trait LotItemMakerLockerCreateTrait
 * @package Sam\EntityMaker\LotItem
 */
trait LotItemMakerLockerCreateTrait
{
    protected ?LotItemMakerLocker $lotItemMakerLocker = null;

    /**
     * @return LotItemMakerLocker
     */
    protected function createLotItemMakerLocker(): LotItemMakerLocker
    {
        return $this->lotItemMakerLocker ?: LotItemMakerLocker::new();
    }

    /**
     * @param LotItemMakerLocker $lotItemMakerLocker
     * @return $this
     * @internal
     */
    public function setLotItemMakerLocker(LotItemMakerLocker $lotItemMakerLocker): static
    {
        $this->lotItemMakerLocker = $lotItemMakerLocker;
        return $this;
    }
}
