<?php
/**
 * SAM-11027: Stacked Tax. Public My Invoice pages. Save user data before CC charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\ShippingInfo\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class MyInvoiceItemFormShippingInfoValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_REQUIRED_CONTACT_TYPE = 1;
    public const ERR_REQUIRED_FIRST_NAME = 2;
    public const ERR_REQUIRED_LAST_NAME = 3;
    public const ERR_REQUIRED_COUNTRY = 4;
    public const ERR_REQUIRED_ADDRESS = 5;
    public const ERR_INVALID_US_STATE = 6;
    public const ERR_INVALID_CANADA_STATE = 7;
    public const ERR_INVALID_MEXICO_STATE = 8;
    public const ERR_REQUIRED_CUSTOM_STATE = 9;
    public const ERR_REQUIRED_CITY = 10;
    public const ERR_REQUIRED_ZIP_CODE = 11;

    public const OK_SUCCESS_VALIDATION = 111;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_REQUIRED_CONTACT_TYPE => 'Required Contact type',
        self::ERR_REQUIRED_FIRST_NAME => 'Reauired First Name',
        self::ERR_REQUIRED_LAST_NAME => 'Required Last Name',
        self::ERR_REQUIRED_COUNTRY => 'Required Country',
        self::ERR_REQUIRED_ADDRESS => 'Required Address',
        self::ERR_INVALID_US_STATE => 'Required US State',
        self::ERR_INVALID_CANADA_STATE => 'Invalid Canada State',
        self::ERR_INVALID_MEXICO_STATE => 'Invalid Mexico State',
        self::ERR_REQUIRED_CUSTOM_STATE => 'Required Custom State',
        self::ERR_REQUIRED_CITY => 'Required City',
        self::ERR_REQUIRED_ZIP_CODE => 'Required Zip Code',
    ];

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_SUCCESS_VALIDATION => 'Input is valid',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function errorMessage(string $glue = null): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function hasContactTypeRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_CONTACT_TYPE);
    }

    public function hasFirstNameRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_FIRST_NAME);
    }

    public function hasLastNameRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_LAST_NAME);
    }

    public function hasCountryRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_COUNTRY);
    }

    public function hasAddressRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_ADDRESS);
    }

    public function hasUsStateError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_INVALID_US_STATE);
    }

    public function hasCanadaStateError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_INVALID_CANADA_STATE);
    }

    public function hasMexicoStateError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_INVALID_MEXICO_STATE);
    }

    public function hasCustomStateError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_CUSTOM_STATE);
    }

    public function hasCityRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_CITY);
    }

    public function hasZipCodeRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_ZIP_CODE);
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $logData['error_message'] = $this->errorMessage(", ");
        }
        return $logData;
    }
}
