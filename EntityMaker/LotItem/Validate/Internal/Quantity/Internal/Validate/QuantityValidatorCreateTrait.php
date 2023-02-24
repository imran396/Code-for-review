<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\Quantity\Internal\Validate;

/**
 * Trait QuantityValidatorCreateTrait
 * @package Sam\EntityMaker\LotItem
 */
trait QuantityValidatorCreateTrait
{
    /**
     * @var QuantityValidator|null
     */
    protected ?QuantityValidator $quantityValidator = null;

    /**
     * @return QuantityValidator
     */
    protected function createQuantityValidator(): QuantityValidator
    {
        return $this->quantityValidator ?: QuantityValidator::new();
    }

    /**
     * @param QuantityValidator $quantityValidator
     * @return static
     * @internal
     */
    public function setQuantityValidator(QuantityValidator $quantityValidator): static
    {
        $this->quantityValidator = $quantityValidator;
        return $this;
    }
}
