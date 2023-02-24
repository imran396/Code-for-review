<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge\Internal\Save;

use InvoiceAdditional;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManager;
use Sam\Storage\WriteRepository\Entity\InvoiceAdditional\InvoiceAdditionalWriteRepository;

/**
 * Class DataSaver
 * @package Sam\Invoice\StackedTax\Generate\Produce\Internal\InvoiceLineItemCharge\Internal\Save
 */
class DataSaver extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function saveInvoiceAdditional(InvoiceAdditional $invoiceAdditional, int $editorUserId): void
    {
        InvoiceAdditionalWriteRepository::new()->saveWithModifier($invoiceAdditional, $editorUserId);
    }

    public function addInvoiceAdditionalExtraFee(
        int $invoiceId,
        string $name,
        float $amount,
        int $editorUserId
    ): InvoiceAdditional {
        return InvoiceAdditionalChargeManager::new()->add(
            Constants\Invoice::IA_EXTRA_FEE,
            $invoiceId,
            $name,
            $amount,
            $editorUserId
        );
    }
}
