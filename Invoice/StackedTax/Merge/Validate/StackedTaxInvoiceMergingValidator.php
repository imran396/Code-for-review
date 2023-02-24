<?php
/**
 * This class is used to validate selected invoices for merging.
 * It validates invoices(selected for merging) have same account/same currency/same bidder or not.
 * It validates invoice(selected for merging) is more than one or not. For merging need at least two valid invoices.
 * It validates invoice(selected for merging) related entity deleted or not.
 *
 * SAM-11142: Stacked Tax. Invoice Management pages. Merge invoices
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Merge\Validate;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Legacy\Merge\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Common\Validate\InvoiceRelatedEntityValidatorAwareTrait;
use Sam\Invoice\Legacy\Merge\Validate\LegacyInvoiceMergingValidationResult as Result;

class StackedTaxInvoiceMergingValidator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceRelatedEntityValidatorAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Main method ---

    /**
     * @param int[] $selectedInvoiceIds
     * @param bool $isReadOnlyDb
     * @return Result
     */
    public function validate(array $selectedInvoiceIds, bool $isReadOnlyDb = false): Result
    {
        $prevAccountId = $prevBidderId = $prevCurrency = null;
        $result = Result::new()->construct();
        $invoices = $this->createDataProvider()->loadInvoices($selectedInvoiceIds, $isReadOnlyDb);
        $result = $this->isInvoiceAvailable($invoices, $selectedInvoiceIds, $result);

        if ($result->hasError()) {
            return $result;
        }

        foreach ($invoices as $invoice) {
            $result = $this->isRelatedEntityDeleted($invoice->Id, $result);
            if ($result->hasError()) {
                return $result;
            }
            $result = $this->isMatchAccount($invoice, $prevAccountId, $result);
            $result = $this->isMatchBidder($invoice, $prevBidderId, $result);
            $result = $this->isMatchCurrency($invoice, $prevCurrency, $result);

            $prevAccountId = $invoice->AccountId;
            $prevBidderId = $invoice->BidderId;
            $prevCurrency = $invoice->CurrencySign;
            $result->validInvoices[] = $invoice;
        }
        $result = $this->hasSecondInvoice($result);
        return $result;
    }

    // --- Internal logic ---

    /**
     * @param Invoice[] $invoices
     * @param int[] $selectedInvoiceIds
     * @param Result $result
     * @return Result
     */
    protected function isInvoiceAvailable(array $invoices, array $selectedInvoiceIds, Result $result): Result
    {
        $loadedInvoiceIds = $this->getInvoiceIds($invoices);
        $missedInvoiceIds = array_diff($selectedInvoiceIds, $loadedInvoiceIds);
        if ($missedInvoiceIds) {
            $result->missedInvoiceIds = $missedInvoiceIds;
            $result = $result->addErrorWithAppendedMessage(Result::ERR_INVOICE_NOT_AVAILABLE, composeSuffix(['i' => $missedInvoiceIds]));
        }
        return $result;
    }

    /**
     * Check related entity deleted or not
     * @param int $invoiceId
     * @param Result $result
     * @return Result
     */
    protected function isRelatedEntityDeleted(int $invoiceId, Result $result): Result
    {
        $isValid = $this->createDataProvider()->isValidRelatedEntity($invoiceId);
        if (!$isValid) {
            $result = $result->addError(Result::ERR_RELATED_DELETED);
        }
        return $result;
    }

    /**
     * Check merging invoice account is same or not
     * @param Invoice $invoice
     * @param int|null $prevAccountId
     * @param Result $result
     * @return Result
     */
    protected function isMatchAccount(Invoice $invoice, ?int $prevAccountId, Result $result): Result
    {
        if (!$prevAccountId) {
            return $result;
        }
        $isMatch = $invoice->AccountId === $prevAccountId;
        if (!$isMatch) {
            $result = $result->addError(Result::ERR_DIFFERENT_ACCOUNTS);
        }
        return $result;
    }

    /**
     * Check merging invoice currency is same or not
     * @param Invoice $invoice
     * @param string|null $prevCurrency
     * @param Result $result
     * @return Result
     */
    protected function isMatchCurrency(Invoice $invoice, ?string $prevCurrency, Result $result): Result
    {
        if (!$prevCurrency) {
            return $result;
        }
        $isMatch = $invoice->CurrencySign === $prevCurrency;
        if (!$isMatch) {
            return $result->addError(Result::ERR_DIFFERENT_CURRENCY);
        }
        return $result;
    }

    /**
     * Check merging invoice bidder is same or not
     * @param Invoice $invoice
     * @param int|null $prevBidderId
     * @param Result $result
     * @return Result
     */
    protected function isMatchBidder(Invoice $invoice, ?int $prevBidderId, Result $result): Result
    {
        if (!$prevBidderId) {
            return $result;
        }
        $isMatch = $invoice->BidderId === $prevBidderId;
        if (!$isMatch) {
            $result = $result->addError(Result::ERR_DIFFERENT_BIDDERS);
        }
        return $result;
    }

    /**
     * check has second invoice or not for merging invoice with others.
     * @param Result $result
     * @return Result
     */
    protected function hasSecondInvoice(Result $result): Result
    {
        $count = count($result->validInvoices);
        if ($count === 0) {
            $result = $result->addError(Result::ERR_NO_INVOICE_TO_MERGE);
        }
        if ($count === 1) {
            $result = $result->addError(Result::ERR_NO_INVOICE_TO_MERGE_WITH);
        }
        return $result;
    }

    /**
     * @param Invoice[] $invoices
     * @return array
     */
    protected function getInvoiceIds(array $invoices): array
    {
        return array_map(
            static function (?Invoice $invoice) {
                return $invoice->Id ?? null;
            },
            $invoices
        );
    }
}
