<?php
/**
 * SAM-9438: Implement ./InvoiceControllerValidationResult and use it as return value for ./InvoiceControllerValidator::validate
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-27, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Invoice\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceControllerValidationResult
 * @package Sam\Application\Controller\Admin\Invoice\Validate
 */
class InvoiceControllerValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    /**
     * Check invoice entity existence
     */
    public const ERR_INCORRECT_INVOICE_ID = 1;
    /**
     * Check invoice availability (not deleted)
     */
    public const ERR_UNAVAILABLE_INVOICE = 2;
    /**
     * Access denied. Admin has not enough privileges
     */
    public const ERR_INVOICE_ACCESS_DENIED = 3;
    /**
     * Check of invoice account
     */
    public const ERR_INVOICE_ACCOUNT_NOT_FOUND = 4;
    /**
     * Check access rights on portal
     */
    public const ERR_INVOICE_AND_PORTAL_ACCOUNTS_NOT_MATCH = 5;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_INCORRECT_INVOICE_ID => 'Wrong invoice id',
        self::ERR_UNAVAILABLE_INVOICE => 'Available invoice not found (deleted)',
        self::ERR_INVOICE_ACCESS_DENIED => ' Access denied: admin has not enough privileges',
        self::ERR_INVOICE_ACCOUNT_NOT_FOUND => 'Access denied: invoice account not available',
        self::ERR_INVOICE_AND_PORTAL_ACCOUNTS_NOT_MATCH => 'Access denied: invoice and portal accounts not match',
    ];

    /**
     * All validations completed successfully
     */
    public const OK_SUCCESS_VALIDATION = 100;

    /** @var string[] */
    public const SUCCESS_MESSAGES = [
        self::OK_SUCCESS_VALIDATION => 'Successful validation',
    ];

    /**
     * Class instantiation method
     * @return $this
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

    // --- Mutate state ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(): static
    {
        $this->getResultStatusCollector()->addSuccess(self::OK_SUCCESS_VALIDATION);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function hasInvoiceUnavailableError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_UNAVAILABLE_INVOICE]);
    }
}
