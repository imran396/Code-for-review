<?php
/**
 * SAM-5412: Validations at controller layer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Lot\Validate;

/**
 * Trait LotControllerValidatorCreateTrait
 * @package Sam\Application\Controller\Responsive\Lot
 */
trait LotControllerValidatorCreateTrait
{
    /**
     * @var LotControllerValidator|null
     */
    protected ?LotControllerValidator $lotControllerValidator = null;

    /**
     * @return LotControllerValidator
     */
    protected function createLotControllerValidator(): LotControllerValidator
    {
        return $this->lotControllerValidator ?: LotControllerValidator::new();
    }

    /**
     * @param LotControllerValidator $lotControllerValidator
     * @return static
     * @internal
     */
    public function setLotControllerValidator(LotControllerValidator $lotControllerValidator): static
    {
        $this->lotControllerValidator = $lotControllerValidator;
        return $this;
    }
}
