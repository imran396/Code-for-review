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

namespace Sam\Application\Controller\Admin\User\Validate;

/**
 * Trait UserControllerValidatorCreateTrait
 * @package
 */
trait UserControllerValidatorCreateTrait
{
    /**
     * @var UserControllerValidator|null
     */
    protected ?UserControllerValidator $inventoryControllerValidator = null;

    /**
     * @return UserControllerValidator
     */
    protected function createUserControllerValidator(): UserControllerValidator
    {
        return $this->inventoryControllerValidator ?: UserControllerValidator::new();
    }

    /**
     * @param UserControllerValidator $inventoryControllerValidator
     * @return static
     * @internal
     */
    public function setUserControllerValidator(UserControllerValidator $inventoryControllerValidator): static
    {
        $this->inventoryControllerValidator = $inventoryControllerValidator;
        return $this;
    }
}
