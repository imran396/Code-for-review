<?php
/**
 * SAM-11027: Stacked Tax. Public My Invoice pages. Save user data before CC charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class MyInvoiceItemFormBillingInfoValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_REQUIRED_FIRST_NAME = 1;
    public const ERR_REQUIRED_LAST_NAME = 2;
    public const ERR_REQUIRED_ADDRESS = 3;
    public const ERR_REQUIRED_CITY = 4;
    public const ERR_INVALID_US_STATE = 5;
    public const ERR_INVALID_CANADA_STATE = 6;
    public const ERR_INVALID_MEXICO_STATE = 7;
    public const ERR_REQUIRED_CUSTOM_STATE = 8;
    public const ERR_REQUIRED_COUNTRY = 9;
    public const ERR_REQUIRED_ZIP_CODE = 10;
    public const ERR_CC_NUMBER_INVALID = 11;
    public const ERR_CC_NUMBER_REQUIRED = 12;
    public const ERR_CC_TYPE_REQUIRED = 13;
    public const ERR_CC_EXP_DATE_EXPIRED = 14;
    public const ERR_CC_EXP_MONTH_REQUIRED = 15;
    public const ERR_CC_EXP_YEAR_REQUIRED = 16;
    public const ERR_BANK_WIRE_ROUTE_NUMBER_REQUIRED = 17;
    public const ERR_BANK_WIRE_ACCOUNT_NUMBER_REQUIRED = 18;
    public const ERR_BANK_WIRE_ACCOUNT_NAME_REQUIRED = 19;
    public const ERR_BANK_WIRE_ACCOUNT_TYPE_REQUIRED = 20;
    public const ERR_BANK_WIRE_NAME_REQUIRED = 21;

    public const OK_SUCCESS_VALIDATION = 111;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_REQUIRED_FIRST_NAME => 'Required First Name',
        self::ERR_REQUIRED_LAST_NAME => 'Required First Name',
        self::ERR_REQUIRED_ADDRESS => 'Required Address',
        self::ERR_REQUIRED_CITY => 'Required City',
        self::ERR_INVALID_US_STATE => 'Required US State',
        self::ERR_INVALID_CANADA_STATE => 'Invalid Canada State',
        self::ERR_INVALID_MEXICO_STATE => 'Invalid Mexico State',
        self::ERR_REQUIRED_CUSTOM_STATE => 'Required Custom State',
        self::ERR_REQUIRED_COUNTRY => 'Required Country',
        self::ERR_REQUIRED_ZIP_CODE => 'Required Zip Code',
        self::ERR_CC_NUMBER_INVALID => 'CcNumber is invalid',
        self::ERR_CC_NUMBER_REQUIRED => 'CcNumber required',
        self::ERR_CC_TYPE_REQUIRED => 'CcType required',
        self::ERR_CC_EXP_DATE_EXPIRED => 'Cc Exp Date is expired',
        self::ERR_CC_EXP_YEAR_REQUIRED => 'Cc Exp Year is required',
        self::ERR_CC_EXP_MONTH_REQUIRED => 'Cc Exp Month is required',
        self::ERR_BANK_WIRE_ROUTE_NUMBER_REQUIRED => 'Bank wire route number required',
        self::ERR_BANK_WIRE_ACCOUNT_NUMBER_REQUIRED => 'Bank wire account number required',
        self::ERR_BANK_WIRE_ACCOUNT_NAME_REQUIRED => 'Bank wire account name required',
        self::ERR_BANK_WIRE_ACCOUNT_TYPE_REQUIRED => 'Bank wire account type required',
        self::ERR_BANK_WIRE_NAME_REQUIRED => 'Bank wire name required',
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

    public function hasFirstNameError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_FIRST_NAME);
    }

    public function hasLastNameError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_LAST_NAME);
    }

    public function hasAddressError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_ADDRESS);
    }

    public function hasCityRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_CITY);
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

    public function hasCountryRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_COUNTRY);
    }

    public function hasZipCodeRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_REQUIRED_ZIP_CODE);
    }

    public function hasInvalidCcNumberError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_CC_NUMBER_INVALID);
    }

    public function hasRequiredCcNumberError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_CC_NUMBER_REQUIRED);
    }

    public function hasRequiredCcTypeError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_CC_TYPE_REQUIRED);
    }

    public function hasCcExpDateExpiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_CC_EXP_DATE_EXPIRED);
    }

    public function hasCcExpMonthRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_CC_EXP_MONTH_REQUIRED);
    }

    public function hasCcExpYearRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_CC_EXP_YEAR_REQUIRED);
    }

    public function hasBankWireRouteNumberRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_BANK_WIRE_ROUTE_NUMBER_REQUIRED);
    }

    public function hasBankWireAccountNumberRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_BANK_WIRE_ACCOUNT_NUMBER_REQUIRED);
    }

    public function hasBankWireAccountNameRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_BANK_WIRE_ACCOUNT_NAME_REQUIRED);
    }

    public function hasBankWireAccountTypeRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_BANK_WIRE_ACCOUNT_TYPE_REQUIRED);
    }

    public function hasBankWireNameRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_BANK_WIRE_NAME_REQUIRED);
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
