<?php
/**
 * SAM-10913: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Opayo invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Opayo\Internal\Calculate;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManager;

class InvoiceCalculator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function addCcSurchargeToTheAmount(
        Invoice $invoice,
        int $ccType,
        float $amount,
        int $editorUserId,
    ): float {
        return InvoiceAdditionalChargeManager::new()->addCcSurchargeToTheAmount(
            $ccType,
            $amount,
            $invoice,
            $editorUserId,
        );
    }
}
