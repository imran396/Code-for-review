<?php
/**
 * SAM-11127: Stacked Tax. New Invoice Edit page: Payment Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoicePaymentEditFormValidationResult
 * @package Sam\View\Admin\Form\InvoicePaymentEditForm\Edit\Validate
 */
class InvoicePaymentEditFormValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_METHOD_REQUIRED = 1;
    public const ERR_METHOD_INVALID = 2;
    public const ERR_CREDIT_CARD_REQUIRED = 3;
    public const ERR_CREDIT_CARD_INVALID = 4;
    public const ERR_DATE_REQUIRED = 5;
    public const ERR_DATE_INVALID = 6;
    public const ERR_AMOUNT_REQUIRED = 7;
    public const ERR_AMOUNT_INVALID = 8;
    public const ERR_PAYMENT_GATEWAY_REQUIRED = 9;

    public const OK_SUCCESS_VALIDATION = 111;

    /** @var string[] */
    public const ERROR_MESSAGES = [
        self::ERR_METHOD_REQUIRED => 'Payment method required',
        self::ERR_METHOD_INVALID => 'Payment method invalid',
        self::ERR_CREDIT_CARD_REQUIRED => 'Credit card required',
        self::ERR_CREDIT_CARD_INVALID => 'Credit card invalid',
        self::ERR_DATE_REQUIRED => 'Payment date required',
        self::ERR_DATE_INVALID => 'Payment date invalid',
        self::ERR_AMOUNT_REQUIRED => 'Amount required',
        self::ERR_AMOUNT_INVALID => 'Amount invalid',
        self::ERR_PAYMENT_GATEWAY_REQUIRED => 'Payment gateway required',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_SUCCESS_VALIDATION => 'Input is valid',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    /**
     * @param int $code
     * @return $this
     */
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

    /**
     * @return array
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
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
        return $this->getResultStatusCollector()->hasSuccess();
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrors(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
