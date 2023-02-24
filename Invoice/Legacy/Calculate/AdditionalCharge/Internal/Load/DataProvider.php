<?php
/**
 * SAM-9966:  Optimize db queries for Public/Admin Invoice List/Edit
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 31, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Calculate\AdditionalCharge\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Calculate\Basic\LegacyInvoiceCalculatorAwareTrait;
use Sam\Invoice\Legacy\Calculate\Tax\LegacyInvoiceTaxCalculatorCreateTrait;

/**
 * Class DataProvider
 * @package ${NAMESPACE}
 */
class DataProvider extends CustomizableClass
{
    use LegacyInvoiceTaxCalculatorCreateTrait;
    use LegacyInvoiceCalculatorAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function calcTotalSalesTaxApplied($invoiceId, $isReadOnlyDb): float
    {
        return $this->createLegacyInvoiceTaxCalculator()->calcTotalSalesTaxApplied($invoiceId, $isReadOnlyDb);
    }

    public function calcTotalHammerPrice($invoiceId, $isReadOnlyDb): float
    {
        return $this->getLegacyInvoiceCalculator()->calcTotalHammerPrice($invoiceId, $isReadOnlyDb);
    }
}
