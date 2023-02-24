<?php
/**
 * SAM-10915: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Authorize.Net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet;

use Invoice;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\Service\CustomizableClass;

class AdminStackedTaxInvoicePaymentAuthorizeNetChargingInput extends CustomizableClass
{
    public readonly Invoice $invoice;
    public readonly bool $isReplaceOldCard;
    public readonly int $ccType;
    public readonly int $editorUserId;
    public readonly string $amountFormatted;
    public readonly string $ccCode;
    public readonly string $ccNumber;
    public readonly string $chargeOption;
    public readonly string $country;
    public readonly string $expMonth;
    public readonly string $expYear;
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly bool $isReadOnlyDb;

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
        bool $isReadOnlyDb
    ): static {
        $this->amountFormatted = $amountFormatted;
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
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }

    public function constructForCcOnFile(
        int $editorUserId,
        Invoice $invoice,
        string $amountFormatted,
        bool $isReadOnlyDb = false
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
            $isReadOnlyDb
        );
    }
}
