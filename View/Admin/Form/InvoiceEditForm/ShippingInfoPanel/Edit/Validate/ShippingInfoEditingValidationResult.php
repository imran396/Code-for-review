<?php
/**
 * SAM-11831: Stacked Tax: Validation is missing at billing email and billing/shipping phone/fax number at invoice edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ShippingInfoEditingValidationResult
 * @package Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit\Validate
 */
class ShippingInfoEditingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const WARN_PHONE_FORMAT = 1;
    public const WARN_FAX_FORMAT = 2;

    /** @var string[] */
    protected const WARNING_MESSAGES = [
        self::WARN_PHONE_FORMAT => 'Invalid shipping phone number',
        self::WARN_FAX_FORMAT => 'Invalid shipping fax number',
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
        $this->getResultStatusCollector()->construct(warningMessages: self::WARNING_MESSAGES);
        return $this;
    }

    // --- Mutation ---

    public function addWarning(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addWarning($code, $message);
        return $this;
    }

    // --- Query methods ---

    /**
     * @return ResultStatus[]
     */
    public function warnings(): array
    {
        return $this->getResultStatusCollector()->getWarningStatuses();
    }
}
