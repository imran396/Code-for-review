<?php
/**
 * SAM-5412: Validations at controller layer
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/01/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Settlement\Validate;

/**
 * Trait SettlementControllerValidatorCreateTrait
 * @package
 */
trait SettlementControllerValidatorCreateTrait
{
    /**
     * @var SettlementControllerValidator|null
     */
    protected ?SettlementControllerValidator $inventoryControllerValidator = null;

    /**
     * @return SettlementControllerValidator
     */
    protected function createSettlementControllerValidator(): SettlementControllerValidator
    {
        return $this->inventoryControllerValidator ?: SettlementControllerValidator::new();
    }

    /**
     * @param SettlementControllerValidator $inventoryControllerValidator
     * @return static
     * @internal
     */
    public function setSettlementControllerValidator(SettlementControllerValidator $inventoryControllerValidator): static
    {
        $this->inventoryControllerValidator = $inventoryControllerValidator;
        return $this;
    }
}
