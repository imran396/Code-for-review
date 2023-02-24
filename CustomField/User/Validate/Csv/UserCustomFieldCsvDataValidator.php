<?php
/*
 * Help methods for user custom field data loading
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

namespace Sam\CustomField\User\Validate\Csv;

use Sam\CustomField\Base\Validate\Csv\CustomFieldCsvDataSimpleValidator;
use Sam\CustomField\Base\Validate\CustomDataValidatorInterface;
use UserCustField;

/**
 * Class UserCustomFieldCsvDataValidator
 * @package Sam\CustomField\User\Validate\Csv
 */
class UserCustomFieldCsvDataValidator extends CustomFieldCsvDataSimpleValidator implements CustomDataValidatorInterface
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate passed by import value for User custom field
     *
     * @param UserCustField $userCustomField
     * @param string $value can accept only string instead of UserCustomFieldWebDataValidator
     * @param string|null $encoding
     * @return bool
     */
    public function validate($userCustomField, $value, ?string $encoding = null): bool
    {
        return $this->validateImportByType(
            $value,
            $userCustomField->Type,
            $userCustomField->Parameters,
            $encoding
        );
    }
}
