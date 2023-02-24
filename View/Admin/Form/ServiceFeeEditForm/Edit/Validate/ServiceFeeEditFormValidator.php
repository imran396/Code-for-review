<?php
/**
 * SAM-11110: Stacked Tax. New Invoice Edit page: Service Fee Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Tax\StackedTax\Schema\Validate\TaxSchemaExistenceCheckerCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Dto\ServiceFeeEditFormInput;
use Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate\ServiceFeeEditFormValidationResult as Result;

/**
 * Class ServiceFeeEditFormValidator
 * @package Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate
 */
class ServiceFeeEditFormValidator extends CustomizableClass
{
    use NumberFormatterAwareTrait;
    use TaxSchemaExistenceCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(ServiceFeeEditFormInput $input): Result
    {
        $result = Result::new()->construct();

        $this->getNumberFormatter()->constructForInvoice($input->invoiceAccountId);

        if (!$input->type) {
            $result->addError(Result::ERR_TYPE_REQUIRED);
        }

        if (!$input->amount) {
            $result->addError(Result::ERR_AMOUNT_REQUIRED);
        }

        $amount = $this->getNumberFormatter()->removeFormat($input->amount);
        if (
            $input->amount !== ''
            && !NumberValidator::new()->isReal($amount) // allow negative refund
        ) {
            $result->addError(Result::ERR_AMOUNT_INVALID);
        }

        if (!$input->name) {
            $result->addError(Result::ERR_NAME_REQUIRED);
        }

        if ($input->taxSchemaId) {
            $isFound = $this->createTaxSchemaExistenceChecker()->existById(
                $input->taxSchemaId,
                $input->invoiceAccountId,
                Constants\StackedTax::AS_SERVICES
            );
            if (!$isFound) {
                $result->addError(Result::ERR_TAX_SCHEMA_INVALID);
            }
        }

        return $result;
    }
}
