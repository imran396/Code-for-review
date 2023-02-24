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
 * @since           Nov 21, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Validate\Csv;

/**
 * Trait UserCustomFieldCsvDataValidatorAwareTrait
 * @package Sam\CustomField\User\Validate\Csv
 */
trait UserCustomFieldCsvDataValidatorAwareTrait
{
    /**
     * @var UserCustomFieldCsvDataValidator|null
     */
    protected ?UserCustomFieldCsvDataValidator $userCustomFieldCsvDataValidator = null;

    /**
     * @return UserCustomFieldCsvDataValidator
     */
    protected function getUserCustomFieldCsvDataValidator(): UserCustomFieldCsvDataValidator
    {
        if ($this->userCustomFieldCsvDataValidator === null) {
            $this->userCustomFieldCsvDataValidator = UserCustomFieldCsvDataValidator::new();
        }
        return $this->userCustomFieldCsvDataValidator;
    }

    /**
     * @param UserCustomFieldCsvDataValidator $userCustomFieldCsvDataValidator
     * @return static
     * @internal
     */
    public function setUserCustomFieldCsvDataValidator(UserCustomFieldCsvDataValidator $userCustomFieldCsvDataValidator): static
    {
        $this->userCustomFieldCsvDataValidator = $userCustomFieldCsvDataValidator;
        return $this;
    }
}
