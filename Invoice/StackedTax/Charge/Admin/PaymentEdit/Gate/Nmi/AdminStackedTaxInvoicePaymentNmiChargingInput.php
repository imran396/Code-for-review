<?php
/**
 * <Description of class>
 *
 * SAM-10919: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Nmi invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Nmi;

use Invoice;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\Service\CustomizableClass;

class AdminStackedTaxInvoicePaymentNmiChargingInput extends CustomizableClass
{
    public Invoice $invoice;
    public int $ccType;
    public int $editorUserId;
    public string $amountFormatted;
    public string $ccCode;
    public string $ccNumber;
    public string $chargeOption;
    public string $expMonth;
    public string $expYear;
    public string $firstName;
    public bool $isReplaceOldCard;
    public string $lastName;

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
        bool $isReplaceOldCard,
        string $chargeOption,
        int $ccType,
        string $ccNumber,
        string $expYear,
        string $expMonth,
        string $ccCode,
        string $firstName,
        string $lastName,
    ): static {
        $this->editorUserId = $editorUserId;
        $this->invoice = $invoice;
        $this->amountFormatted = $amountFormatted;
        $this->isReplaceOldCard = $isReplaceOldCard;
        $this->chargeOption = $chargeOption;
        $this->ccType = $ccType;
        $this->ccNumber = $ccNumber;
        $this->expYear = $expYear;
        $this->expMonth = $expMonth;
        $this->ccCode = $ccCode;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        return $this;
    }

    public function constructForCcOnFile(
        int $editorUserId,
        Invoice $invoice,
        string $amountFormatted
    ): static {
        return self::new()->construct(
            $editorUserId,
            $invoice,
            $amountFormatted,
            false,
            InvoiceItemFormConstants::CHARGE_OPTION_CC_ON_PROFILE,
            Constants\CreditCard::T_NONE,
            '',
            '',
            '',
            '',
            '',
            '',
        );
    }
}
