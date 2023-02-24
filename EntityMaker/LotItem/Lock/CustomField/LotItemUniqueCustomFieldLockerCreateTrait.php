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

namespace Sam\EntityMaker\LotItem\Lock\CustomField;

/**
 * Trait LotItemUniqueCustomFieldLockerCreateTrait
 * @package Sam\EntityMaker\LotItem\Lock\CustomField
 */
trait LotItemUniqueCustomFieldLockerCreateTrait
{
    protected ?LotItemUniqueCustomFieldLocker $lotItemUniqueCustomFieldLocker = null;

    /**
     * @return LotItemUniqueCustomFieldLocker
     */
    protected function createLotItemUniqueCustomFieldLocker(): LotItemUniqueCustomFieldLocker
    {
        return $this->lotItemUniqueCustomFieldLocker ?: LotItemUniqueCustomFieldLocker::new();
    }

    /**
     * @param LotItemUniqueCustomFieldLocker $lotItemUniqueCustomFieldLocker
     * @return static
     * @internal
     */
    public function setLotItemUniqueCustomFieldLocker(LotItemUniqueCustomFieldLocker $lotItemUniqueCustomFieldLocker): static
    {
        $this->lotItemUniqueCustomFieldLocker = $lotItemUniqueCustomFieldLocker;
        return $this;
    }
}
