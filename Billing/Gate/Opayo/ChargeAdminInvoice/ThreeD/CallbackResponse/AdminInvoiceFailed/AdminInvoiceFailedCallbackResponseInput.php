<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Sept 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAdminInvoice\ThreeD\CallbackResponse\AdminInvoiceFailed;

use Sam\Core\Service\CustomizableClass;


class AdminInvoiceFailedCallbackResponseInput extends CustomizableClass
{
    public ?int $userId;
    public ?int $invoiceId;
    public string $threeDStatusResponse;
    public bool $isReadOnlyDb;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $userId
     * @param int|null $invoiceId
     * @param string $threeDStatusResponse
     * @param bool $isReadOnlyDb
     * @return $this
     */
    public function construct(
        ?int $userId,
        ?int $invoiceId,
        string $threeDStatusResponse,
        bool $isReadOnlyDb
    ): static {
        $this->userId = $userId;
        $this->invoiceId = $invoiceId;
        $this->threeDStatusResponse = $threeDStatusResponse;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
