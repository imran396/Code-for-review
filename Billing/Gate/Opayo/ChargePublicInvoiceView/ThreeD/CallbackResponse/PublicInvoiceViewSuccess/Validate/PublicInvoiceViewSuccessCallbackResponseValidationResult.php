<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;


class PublicInvoiceViewSuccessCallbackResponseValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_NOT_AVAILABLE = 1;
    public const ERR_USER_NOT_FOUND = 2;
    public const ERR_INVALID_ACCOUNT_ID = 3;
    public const ERR_INVALID_MAIN_ACCOUNT = 4;
    public const ERR_INVALID_USER_ID = 5;
    public const ERR_INVALID_INVOICE_ID = 6;
    public const ERR_INVALID_CC_NUMBER = 7;
    public const ERR_INVALID_AMOUNT = 8;
    public const ERR_INVALID_BILLING_PARAMS = 9;
    public const ERR_EDITOR_USER_NOT_FOUND = 10;

    public const OK_SUCCESS_VALIDATION = 11;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_NOT_AVAILABLE => 'Invoice not available',
        self::ERR_USER_NOT_FOUND => 'User not found',
        self::ERR_INVALID_ACCOUNT_ID => 'Account id is invalid',
        self::ERR_INVALID_MAIN_ACCOUNT => 'Main account id is invalid',
        self::ERR_INVALID_USER_ID => 'User id is invalid',
        self::ERR_INVALID_INVOICE_ID => 'Invoice id is invalid',
        self::ERR_INVALID_CC_NUMBER => 'Cc number is invalid',
        self::ERR_INVALID_AMOUNT => 'Amount is invalid',
        self::ERR_INVALID_BILLING_PARAMS => 'User params are empty',
        self::ERR_EDITOR_USER_NOT_FOUND => 'Editor user not found',
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
