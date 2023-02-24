<?php
/**
 * Help methods for User custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 3, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Validate\Web;

/**
 * Trait UserCustomFieldWebDataValidatorAwareTrait
 * @package Sam\CustomField\User\Validate
 */
trait UserCustomFieldWebDataValidatorAwareTrait
{
    protected ?UserCustomFieldWebDataValidator $userCustomFieldWebDataValidator = null;

    /**
     * @return UserCustomFieldWebDataValidator
     */
    protected function getUserCustomFieldWebDataValidator(): UserCustomFieldWebDataValidator
    {
        if ($this->userCustomFieldWebDataValidator === null) {
            $this->userCustomFieldWebDataValidator = UserCustomFieldWebDataValidator::new();
        }
        return $this->userCustomFieldWebDataValidator;
    }

    /**
     * @param UserCustomFieldWebDataValidator $userCustomFieldWebDataValidator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserCustomFieldWebDataValidator(UserCustomFieldWebDataValidator $userCustomFieldWebDataValidator): static
    {
        $this->userCustomFieldWebDataValidator = $userCustomFieldWebDataValidator;
        return $this;
    }
}
