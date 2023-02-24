<?php
/**
 * SAM-8833: Lot item entity maker - extract item# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\Internal\Validate;

/**
 * Trait ItemNoValidatorCreateTrait
 * @package Sam\EntityMaker\LotItem
 */
trait ItemNoValidatorCreateTrait
{
    protected ?ItemNoValidator $itemNoValidator = null;

    /**
     * @return ItemNoValidator
     */
    protected function createItemNoValidator(): ItemNoValidator
    {
        return $this->itemNoValidator ?: ItemNoValidator::new();
    }

    /**
     * @param ItemNoValidator $itemNoValidator
     * @return $this
     * @internal
     */
    public function setItemNoValidator(ItemNoValidator $itemNoValidator): static
    {
        $this->itemNoValidator = $itemNoValidator;
        return $this;
    }
}
