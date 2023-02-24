<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Sept 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed\Handle;


use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;


class AdminInvoiceFailedCallbackResponseHandleResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const OK_SUCCESS_HANDLE_FAILED_PAYMENT = 1;

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
        $this->getResultStatusCollector()->construct(
            [],
            [self::OK_SUCCESS_HANDLE_FAILED_PAYMENT => 'Payment fail was successfully handled']
        );
        return $this;
    }

    // --- Mutate ---

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }
}
