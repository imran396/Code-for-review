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

namespace Sam\EntityMaker\LotItem\Lock\Consignor\Internal\Detect;

/**
 * @package Sam\EntityMaker\LotItem
 */
trait LotItemUniqueConsignorLockRequirementCheckerCreateTrait
{
    protected ?LotItemUniqueConsignorLockRequirementChecker $lotItemUniqueConsignorLockRequirementChecker = null;

    /**
     * @return LotItemUniqueConsignorLockRequirementChecker
     */
    protected function createLotItemUniqueConsignorLockRequirementChecker(): LotItemUniqueConsignorLockRequirementChecker
    {
        return $this->lotItemUniqueConsignorLockRequirementChecker ?: LotItemUniqueConsignorLockRequirementChecker::new();
    }

    /**
     * @param LotItemUniqueConsignorLockRequirementChecker $lotItemUniqueConsignorLockRequirementChecker
     * @return static
     * @internal
     */
    public function setLotItemUniqueConsignorLockRequirementChecker(LotItemUniqueConsignorLockRequirementChecker $lotItemUniqueConsignorLockRequirementChecker): static
    {
        $this->lotItemUniqueConsignorLockRequirementChecker = $lotItemUniqueConsignorLockRequirementChecker;
        return $this;
    }
}
