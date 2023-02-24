<?php
/**
 * SAM-4669: Invoice management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\Credit;

use Invoice;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Save\AwareTrait\NumberFormattingExpectationAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Invoice\Common\Calculate\Basic\AnyInvoiceCalculator;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdaterCreateTrait;
use Sam\Invoice\Common\Currency\InvoiceCurrencyDetectorAwareTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\InvoiceAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Credit\UserCreditManagerAwareTrait;

/**
 * Class InvoiceCreditApplier
 * @package Sam\Invoice\Common\Credit
 * @method Invoice getInvoice() - required dependency
 */
class InvoiceCreditApplier extends CustomizableClass
{
    use InvoiceAwareTrait;
    use InvoiceCurrencyDetectorAwareTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceTotalsUpdaterCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use NumberFormatterAwareTrait;
    use NumberFormattingExpectationAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use TranslatorAwareTrait;
    use UserCreditManagerAwareTrait;

    public const ERR_INVALID_CREDIT_AMOUNT = 1;
    public const ERR_NO_ENOUGH_CREDIT = 2;

    public const OK_APPLIED = 1;

    /**
     * @var string|float
     */
    protected float|string $creditAmount;

    /**
     * @var int|null
     */
    protected ?int $editorUserId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize object
     * @return static
     */
    public function initInstance(): static
    {
        $this->creditAmount = 0.;

        // Init ResultStatusCollector
        $errorMessages = [
            self::ERR_INVALID_CREDIT_AMOUNT => $this->getTranslator()->translate('MYINVOICES_INVALID_CREDIT', 'myinvoices'),
            self::ERR_NO_ENOUGH_CREDIT => $this->getTranslator()->translate('MYINVOICES_NO_ENOUGH_CREDIT', 'myinvoices'),
        ];

        $successMessages = [
            self::OK_APPLIED => $this->getTranslator()->translate('INVOICE_CREDIT_MSG', 'myinvoices'),
        ];

        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
        return $this;
    }

    /**
     * Validates object
     *
     * @return bool
     */
    public function validate(): bool
    {
        $collector = $this->getResultStatusCollector()->clear();

        $creditAmountRaw = $this->getCreditAmount();
        if (!NumberValidator::new()->isRealPositive($creditAmountRaw)) {
            $collector->addError(self::ERR_INVALID_CREDIT_AMOUNT);
            return false;
        }

        $creditAmount = $this->normalizeCreditAmount();
        $userCreditAmount = $this->getUserCreditManager()->calcTotal($this->getInvoice()->BidderId);

        if (Floating::lt($userCreditAmount, $creditAmount)) {
            $collector->addError(self::ERR_NO_ENOUGH_CREDIT);
            return false;
        }

        return true;
    }

    /**
     * Applies validated credit amount to current invoice
     * @param int $editorUserId
     */
    public function apply(int $editorUserId): void
    {
        $amountToPay = $this->normalizeCreditAmount();
        $userId = $this->getInvoice()->BidderId;

        $this->getUserCreditManager()->deduct($userId, $amountToPay, $editorUserId);
        $this->getInvoicePaymentManager()->add(
            $this->getInvoiceId(),
            Constants\Payment::PM_CREDIT_MEMO,
            $amountToPay,
            $userId,
            'Prepaid Credit'
        );

        $invoice = $this->getInvoice();
        $balanceDue = AnyInvoiceCalculator::new()->calcRoundedBalanceDue($invoice);

        if (Floating::eq($balanceDue, 0)) {
            $invoice->toPaid();
        }
        $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $this->editorUserId);

        $this->processSuccessMessage();
    }

    /**
     * @param int $editorUserId
     * @return static
     */
    public function setEditorUserId(int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * @return string|float
     */
    public function getCreditAmount(): string|float
    {
        return $this->creditAmount;
    }

    /**
     * @return float
     */
    protected function normalizeCreditAmount(): float
    {
        return (float)$this->getCreditAmount();
    }

    /**
     * @param string $creditAmount
     * @return static
     */
    public function setCreditAmount(string $creditAmount): static
    {
        $this->creditAmount = $this->isNumberFormattingExpected()
            ? $this->getNumberFormatter()->removeFormat($creditAmount)
            : $creditAmount;
        return $this;
    }

    /**
     * Add success message with payment info to result collector
     */
    protected function processSuccessMessage(): void
    {
        $currencySign = $this->getInvoiceCurrencyDetector()->detectSign($this->getInvoiceId(), true);

        $creditAmount = $this->normalizeCreditAmount();
        $creditPrice = $currencySign . $this->getNumberFormatter()->formatMoney($creditAmount);

        $userCredits = $this->getUserCreditManager()->calcTotal($this->getInvoice()->BidderId);
        $creditTotalPrice = $currencySign . $this->getNumberFormatter()->formatMoney($userCredits);

        $this->getResultStatusCollector()->addSuccessWithInjectedInMessageArguments(self::OK_APPLIED, [$creditPrice, $creditTotalPrice]);
    }
}
