<?php
/**
 * SAM-10996: Stacked Tax. New Invoice Edit page: Invoiced user billing and shipping sections
 * SAM-11831: Stacked Tax: Validation is missing at billing email and billing/shipping phone/fax number at invoice edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\BillingInfoPanel\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BillingInfoEditingValidationResult
 * @package Sam\View\Admin\Form\InvoiceEditForm\BillingInfoPanel\Edit
 */
class BillingInfoEditingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_BILLING_EMAIL_FORMAT = 1;

    public const WARN_PHONE_FORMAT = 2;
    public const WARN_FAX_FORMAT = 3;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_BILLING_EMAIL_FORMAT => 'Invalid billing email format',
    ];

    /** @var string[] */
    protected const WARNING_MESSAGES = [
        self::WARN_PHONE_FORMAT => 'Invalid billing phone number',
        self::WARN_FAX_FORMAT => 'Invalid billing fax number',
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
        $this->getResultStatusCollector()->construct(errorMessages: self::ERROR_MESSAGES, warningMessages: self::WARNING_MESSAGES);
        return $this;
    }

    // --- Mutation ---

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    public function addWarning(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addWarning($code, $message);
        return $this;
    }

    // --- Query methods ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage(", ");
    }

    /**
     * @return ResultStatus[]
     */
    public function errors(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    /**
     * @return ResultStatus[]
     */
    public function warnings(): array
    {
        return $this->getResultStatusCollector()->getWarningStatuses();
    }
}
