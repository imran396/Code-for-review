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

namespace Sam\CustomField\User\Validate\Web;

use Sam\CustomField\Base\Validate\CustomDataValidatorInterface;
use Sam\CustomField\Base\Validate\Web\CustomFieldWebDataSimpleValidator;
use UserCustField;

/**
 * Class UserCustomFieldWebDataValidator
 * @package Sam\CustomField\User\Validate\Web
 */
class UserCustomFieldWebDataValidator extends CustomFieldWebDataSimpleValidator implements CustomDataValidatorInterface
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate value for User custom field
     *
     * @param UserCustField $customField
     * @param int|string|bool|null $value can accept int|string|bool|null values depending of custom field type.
     * @param string|null $encoding
     * @return bool
     */
    public function validate($customField, int|string|bool|null $value, ?string $encoding = null): bool
    {
        $isValid = $this->validateByType(
            $value,
            $customField->Type,
            $customField->Parameters,
            $customField->Required
        );
        return $isValid;
    }

    /**
     * @return string
     */
    public function dateErrorMessage(): string
    {
        if ($this->isTranslating()) {
            $errorMessage = $this->getTranslator()->translate('USER_ERR_DATE', 'user');
        } else {
            $errorMessage = parent::dateErrorMessage();
        }
        return $errorMessage;
    }

    /**
     * @return string
     */
    public function optionErrorMessage(): string
    {
        if ($this->isTranslating()) {
            $errorMessage = $this->getTranslator()->translate('USER_ERR_OPTION', 'user');
        } else {
            $errorMessage = parent::optionErrorMessage();
        }
        return $errorMessage;
    }

    /**
     * @return string
     */
    public function requiredErrorMessage(): string
    {
        if ($this->isTranslating()) {
            $errorMessage = $this->getTranslator()->translate('SIGNUP_ERR_REQUIRED', 'user');
        } else {
            $errorMessage = parent::requiredErrorMessage();
        }
        return $errorMessage;
    }

    /**
     * @return string
     */
    public function integerErrorMessage(): string
    {
        if ($this->isTranslating()) {
            $errorMessage = $this->getTranslator()->translate('USER_ERR_INTEGER', 'user');
        } else {
            $errorMessage = parent::integerErrorMessage();
        }
        return $errorMessage;
    }

    /**
     * @return string
     */
    public function numericErrorMessage(): string
    {
        if ($this->isTranslating()) {
            $errorMessage = $this->getTranslator()->translate('USER_ERR_NUMERIC', 'user');
        } else {
            $errorMessage = parent::numericErrorMessage();
        }
        return $errorMessage;
    }

    /**
     * @return string
     */
    public function numberFormatErrorMessage(): string
    {
        if ($this->isTranslating()) {
            $errorMessage = $this->getTranslator()->translate('USER_ERR_NUMBER_FORMAT', 'user');
        } else {
            $errorMessage = parent::numberFormatErrorMessage();
        }
        return $errorMessage;
    }
}
