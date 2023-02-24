<?php
/**
 * SAM-10900: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Individual Invoiced Item Sales Tax validation and updating
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

namespace Sam\View\Admin\Form\InvoiceItemForm\EditItemTax\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\InvoiceItemForm\EditItemTax\Common\InvoiceItemFormItemTaxEditInput as Input;
use Sam\View\Admin\Form\InvoiceItemForm\EditItemTax\Validate\InvoiceItemFormItemTaxEditValidationResult as Result;

class InvoiceItemFormItemTaxEditValidator extends CustomizableClass
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
     * @param Input $input
     * @return Result
     */
    public function validate(Input $input): Result
    {
        $nf = $this->getNumberFormatter()->constructForInvoice($input->invoiceAccountId);
        $result = Result::new()->construct();

        $taxPercent = $nf->removeFormat($input->taxPercent);
        if (
            $taxPercent !== ''
            && !NumberValidator::new()->isRealPositiveOrZero($taxPercent)
        ) {
            $result->addError(Result::ERR_TAX_PERCENT_INVALID);
        }

        if (!in_array((int)$input->taxApplication, Constants\User::TAX_APPLICATIONS, true)) {
            $result->addError(Result::ERR_TAX_APPLICATION_INVALID);
        }

        return $result;
    }

}
