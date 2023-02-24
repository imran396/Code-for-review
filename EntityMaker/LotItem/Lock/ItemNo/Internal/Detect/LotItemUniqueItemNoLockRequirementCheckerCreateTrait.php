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

namespace Sam\EntityMaker\LotItem\Lock\ItemNo\Internal\Detect;

/**
 * @package Sam\EntityMaker\LotItem
 */
trait LotItemUniqueItemNoLockRequirementCheckerCreateTrait
{
    protected ?LotItemUniqueItemNoLockRequirementChecker $lotItemUniqueItemNoLockRequirementChecker = null;

    /**
     * @return LotItemUniqueItemNoLockRequirementChecker
     */
    protected function createLotItemUniqueItemNoLockRequirementChecker(): LotItemUniqueItemNoLockRequirementChecker
    {
        return $this->lotItemUniqueItemNoLockRequirementChecker ?: LotItemUniqueItemNoLockRequirementChecker::new();
    }

    /**
     * @param LotItemUniqueItemNoLockRequirementChecker $lotItemUniqueItemNoLockRequirementChecker
     * @return $this
     * @internal
     */
    public function setLotItemUniqueItemNoLockRequirementChecker(LotItemUniqueItemNoLockRequirementChecker $lotItemUniqueItemNoLockRequirementChecker): static
    {
        $this->lotItemUniqueItemNoLockRequirementChecker = $lotItemUniqueItemNoLockRequirementChecker;
        return $this;
    }
}
