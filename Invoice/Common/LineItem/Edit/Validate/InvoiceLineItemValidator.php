<?php
/**
 * SAM-9454: Refactor Invoice Line item editor for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Edit\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Invoice\Common\LineItem\Edit\Common\InvoiceLineItemInput as Input;
use Sam\Invoice\Common\LineItem\Edit\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Common\LineItem\Edit\Validate\InvoiceLineItemValidationResult as Result;

/**
 * Class InvoiceLineItemValidator
 * @package Sam\Invoice\Common\LineItem\Edit\Save
 */
class InvoiceLineItemValidator extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validates object
     * @param Input $input
     * @return Result
     */
    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();
        $this->validateInvoiceLineId($input, $result);
        $this->validateLabel($input, $result);
        $this->validateAmount($input, $result);
        $this->validateAuctionType($input, $result);
        $this->validateBreakDown($input, $result);
        return $result;
    }

    /**
     * Validate invoice line id (SAM-9452)
     * @param Input $input
     * @param Result $result
     */
    protected function validateInvoiceLineId(Input $input, Result $result): void
    {
        if (!$input->invoiceLineItemId) {
            return;
        }

        if (!$this->createDataProvider()->existById($input->invoiceLineItemId)) {
            $result->addError(Result::ERR_INVOICE_LINE_ITEM_NOT_FOUND_BY_ID);
        }
    }

    /**
     * Validate Amount
     * @param Input $input
     * @param Result $result
     */
    protected function validateAmount(Input $input, Result $result): void
    {
        if ($input->amount === '') {
            $result->addError(Result::ERR_AMOUNT_REQUIRED);
        } elseif (!NumberValidator::new()->isReal($input->amount)) {
            $result->addError(Result::ERR_AMOUNT_INVALID);
        }
    }

    /**
     * Validate Label
     * @param Input $input
     * @param Result $result
     */
    protected function validateLabel(Input $input, Result $result): void
    {
        if ($input->label === '') {
            $result->addError(Result::ERR_LABEL_REQUIRED);
            return;
        }

        $skipIds = $input->invoiceLineItemId ? [$input->invoiceLineItemId] : [];
        if ($this->createDataProvider()->existByLabelAndAccount($input->label, $input->accountId, $skipIds)) {
            $result->addError(Result::ERR_LABEL_EXIST);
        }
    }

    /**
     * Validate actionType
     * @param Input $input
     * @param Result $result
     */
    protected function validateAuctionType(Input $input, Result $result): void
    {
        if (!in_array($input->auctionType, Constants\Invoice::$invoiceLineItemAuctionTypes, true)) {
            $result->addError(Result::ERR_AUCTION_TYPE_REQUIRED);
        }
    }

    /**
     * Validate breakdown
     * @param Input $input
     * @param Result $result
     */
    protected function validateBreakDown(Input $input, Result $result): void
    {
        if ($input->breakDown === '') {
            $result->addError(Result::ERR_BREAKDOWN_REQUIRED);
        }
    }
}
