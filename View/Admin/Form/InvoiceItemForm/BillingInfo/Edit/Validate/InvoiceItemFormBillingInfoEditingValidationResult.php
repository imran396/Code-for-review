<?php
/**
 * SAM-10898: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Billing Info and Shipping Info management
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

namespace Sam\View\Admin\Form\InvoiceItemForm\BillingInfo\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class InvoiceItemFormBillingInfoEditingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_BILLING_EMAIL_FORMAT = 1;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_BILLING_EMAIL_FORMAT => 'Invalid billing email format',
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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    // --- Mutation ---

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
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

    public function statusCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstStatusCode();
    }

    public function hasBillingEmailError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_BILLING_EMAIL_FORMAT);
    }
}
