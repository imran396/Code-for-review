<?php
/**
 * SAM-10995: Stacked Tax. New Invoice Edit page: Initial layout and header section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceHeaderValidationResult
 * @package Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate
 */
class InvoiceHeaderValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_NO_REQUIRED = 1;
    public const ERR_INVOICE_NO_INVALID = 2;
    public const ERR_INVOICE_NO_EXISTS = 3;

    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_NO_REQUIRED => 'Invoice# required',
        self::ERR_INVOICE_NO_INVALID => 'Invoice# must be positive integer',
        self::ERR_INVOICE_NO_EXISTS => 'Invoice# already exists',
    ];

    /**
     * Class instantiation method
     * @return static
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

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrors(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
