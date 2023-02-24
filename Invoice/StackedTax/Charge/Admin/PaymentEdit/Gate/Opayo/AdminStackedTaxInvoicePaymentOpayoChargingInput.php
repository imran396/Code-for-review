<?php
/**
 * SAM-11496: Stacked Tax. Actions at the Admin Invoice List page. Adopt the "Charge via Opayo" action
 *
 * Stacked Tax. Admin - Add to Stacked Tax Payment page (Invoice) the functionality from Pay Invoice page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo;

use Invoice;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\Service\CustomizableClass;

class AdminStackedTaxInvoicePaymentOpayoChargingInput extends CustomizableClass
{
    public readonly Invoice $invoice;
    public readonly bool $isReplaceOldCard;
    public readonly int $ccType;
    public readonly int $editorUserId;
    public readonly string $amountFormatted;
    public readonly string $editableFormAmount;
    public readonly ?int $taxSchemaId;
    public readonly string $note;
    public readonly string $ccCode;
    public readonly string $ccNumber;
    public readonly string $chargeOption;
    public readonly string $expMonth;
    public readonly string $expYear;
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly string $paymentUrl;
    public readonly string $sessionId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $editorUserId,
        Invoice $invoice,
        string $amountFormatted,
        string $editableFormAmount,
        ?int $taxSchemaId,
        string $note,
        bool $isReplaceOldCard,
        string $chargeOption,
        int $ccType,
        string $ccNumber,
        string $expYear,
        string $expMonth,
        string $ccCode,
        string $firstName,
        string $lastName,
        string $paymentUrl,
        string $sessionId,
    ): static {
        $this->amountFormatted = $amountFormatted;
        $this->editableFormAmount = $editableFormAmount;
        $this->taxSchemaId = $taxSchemaId;
        $this->note = $note;
        $this->ccCode = $ccCode;
        $this->ccNumber = $ccNumber;
        $this->ccType = $ccType;
        $this->chargeOption = $chargeOption;
        $this->editorUserId = $editorUserId;
        $this->expMonth = $expMonth;
        $this->expYear = $expYear;
        $this->firstName = $firstName;
        $this->invoice = $invoice;
        $this->lastName = $lastName;
        $this->isReplaceOldCard = $isReplaceOldCard;
        $this->paymentUrl = $paymentUrl;
        $this->sessionId = $sessionId;
        return $this;
    }


    public function constructForCcOnFile(
        int $editorUserId,
        Invoice $invoice,
        string $amountFormatted,
        string $editableFormAmount,
        ?int $taxSchemaId,
        string $note,
        string $paymentUrl,
        string $sessionId,
    ): static {
        return self::new()->construct(
            $editorUserId,
            $invoice,
            $amountFormatted,
            $editableFormAmount,
            $taxSchemaId,
            $note,
            false,
            InvoiceItemFormConstants::CHARGE_OPTION_CC_ON_PROFILE,
            Constants\CreditCard::T_NONE,
            '',
            '',
            '',
            '',
            '',
            '',
            $paymentUrl,
            $sessionId
        );
    }
}
