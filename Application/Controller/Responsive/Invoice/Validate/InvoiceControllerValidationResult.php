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

namespace Sam\Application\Controller\Responsive\Invoice\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceControllerValidationResult
 * @package Sam\Application\Controller\Responsive\Invoice\Validate
 */
class InvoiceControllerValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    /**
     * Check invoice entity existence and availability
     */
    public const ERR_INVOICE_NOT_FOUND = 1;
    /**
     * Check editor user has access to invoice (should be owner)
     */
    public const ERR_ACCESS_DENIED = 2;
    /**
     * Check domain auction visibility by invoice account (SAM-3051)
     */
    public const ERR_DOMAIN_AUCTION_VISIBILITY = 3;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_NOT_FOUND => 'Available invoice not found',
        self::ERR_ACCESS_DENIED => 'Access denied',
        self::ERR_DOMAIN_AUCTION_VISIBILITY => 'Failed domain auction visibility check',
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

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
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

    /**
     * @return bool
     */
    public function hasAccessError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_ACCESS_DENIED]);
    }
}
