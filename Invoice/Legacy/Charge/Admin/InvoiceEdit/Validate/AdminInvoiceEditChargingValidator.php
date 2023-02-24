<?php
/**
 * SAM-10909: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. General adjustments
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Validate\AdminInvoiceEditChargingValidationResult as Result;

/**
 * Class AdminInvoiceEditChargingValidator
 * @package Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Validate
 */
class AdminInvoiceEditChargingValidator extends CustomizableClass
{
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $amountFormatted
     * @param int $invoiceAccountId
     * @return Result
     */
    public function validate(string $amountFormatted, int $invoiceAccountId): Result
    {
        $nf = $this->getNumberFormatter()->constructForInvoice($invoiceAccountId);
        $result = Result::new()->construct();

        if (trim($amountFormatted) === '') {
            return $result->addError(Result::ERR_CHARGE_AMOUNT_REQUIRED);
        }

        $amount = $nf->removeFormat($amountFormatted);
        if (!NumberValidator::new()->isReal($amount)) {
            return $result->addError(Result::ERR_CHARGE_AMOUNT_INVALID);
        }

        return $result;
    }
}
