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

namespace Sam\Application\Controller\Admin\LotImage\Validate;

/**
 * Trait LotImageControllerValidatorCreateTrait
 * @package
 */
trait LotImageControllerValidatorCreateTrait
{
    /**
     * @var LotImageControllerValidator|null
     */
    protected ?LotImageControllerValidator $inventoryControllerValidator = null;

    /**
     * @return LotImageControllerValidator
     */
    protected function createLotImageControllerValidator(): LotImageControllerValidator
    {
        return $this->inventoryControllerValidator ?: LotImageControllerValidator::new();
    }

    /**
     * @param LotImageControllerValidator $inventoryControllerValidator
     * @return static
     * @internal
     */
    public function setLotImageControllerValidator(LotImageControllerValidator $inventoryControllerValidator): static
    {
        $this->inventoryControllerValidator = $inventoryControllerValidator;
        return $this;
    }
}
