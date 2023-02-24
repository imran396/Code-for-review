<?php
/**
 * SAM-10335: Allow to adjust CC surcharge per account: Implementation (Dev)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class CreditCardEditingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_NAME_EXISTS = 1;
    public const ERR_SURCHARGE_NOT_POSITIVE_NUMBER = 2;
    public const ERR_NAME_REQUIRED = 3;

    public const OK_SUCCESS_VALIDATION = 4;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_NAME_EXISTS => 'Name exists',
        self::ERR_NAME_REQUIRED => 'Name required',
        self::ERR_SURCHARGE_NOT_POSITIVE_NUMBER => 'Should be positive number',
    ];

    protected const NAME_ERRORS = [
        self::ERR_NAME_EXISTS,
        self::ERR_NAME_REQUIRED,
    ];

    protected const SURCHARGE_ERRORS = [
        self::ERR_SURCHARGE_NOT_POSITIVE_NUMBER
    ];

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_SUCCESS_VALIDATION => 'Credit Card saved',
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

    // --- Mutate methods ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    // --- Query methods ---

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage("\n");
    }

    public function statusCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstStatusCode();
    }

    public function hasNameRequiredError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_NAME_REQUIRED);
    }

    public function hasNameExistsError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_NAME_EXISTS);
    }

    public function nameErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(self::NAME_ERRORS);
    }

    public function hasSurchargeError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::SURCHARGE_ERRORS);
    }

    public function surchargeErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(self::SURCHARGE_ERRORS);
    }
}
