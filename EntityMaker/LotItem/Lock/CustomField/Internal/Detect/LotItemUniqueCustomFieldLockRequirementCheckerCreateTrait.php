<?php
/**
 * SAM-10589: Supply uniqueness of lot item fields: lot custom fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 09, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Lock\CustomField\Internal\Detect;

/**
 * Trait LotItemUniqueCustomFieldLockRequirementCheckerCreateTrait
 * @package Sam\EntityMaker\LotItem\Lock\CustomField\Internal\Detect
 */
trait LotItemUniqueCustomFieldLockRequirementCheckerCreateTrait
{
    protected ?LotItemUniqueCustomFieldLockRequirementChecker $lotItemUniqueCustomFieldLockRequirementChecker = null;

    /**
     * @return LotItemUniqueCustomFieldLockRequirementChecker
     */
    protected function createLotItemUniqueCustomFieldLockRequirementChecker(): LotItemUniqueCustomFieldLockRequirementChecker
    {
        return $this->lotItemUniqueCustomFieldLockRequirementChecker ?: LotItemUniqueCustomFieldLockRequirementChecker::new();
    }

    /**
     * @param LotItemUniqueCustomFieldLockRequirementChecker $lotItemUniqueCustomFieldLockRequirementChecker
     * @return static
     * @internal
     */
    public function setLotItemUniqueCustomFieldLockRequirementChecker(LotItemUniqueCustomFieldLockRequirementChecker $lotItemUniqueCustomFieldLockRequirementChecker): static
    {
        $this->lotItemUniqueCustomFieldLockRequirementChecker = $lotItemUniqueCustomFieldLockRequirementChecker;
        return $this;
    }
}
