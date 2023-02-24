<?php
/**
 * SAM-10913: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Opayo invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Opayo;

use Invoice;
use Sam\Core\Service\CustomizableClass;

class AdminInvoiceEditOpayoChargingInput extends CustomizableClass
{
    public Invoice $invoice;
    public bool $isReplaceOldCard;
    public int $ccType;
    public int $editorUserId;
    public string $address;
    public string $amountFormatted;
    public string $ccCode;
    public string $ccNumber;
    public string $chargeOption;
    public string $city;
    public string $country;
    public string $expMonth;
    public string $expYear;
    public string $firstName;
    public string $lastName;
    public string $paymentUrl;
    public string $phone;
    public string $sessionId;
    public string $state;
    public string $zip;

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
        string $country,
        string $state,
        string $city,
        string $address,
        string $zip,
        string $phone,
        string $paymentUrl,
        string $sessionId,
    ): static {
        $this->address = $address;
        $this->amountFormatted = $amountFormatted;
        $this->ccCode = $ccCode;
        $this->ccNumber = $ccNumber;
        $this->ccType = $ccType;
        $this->chargeOption = $chargeOption;
        $this->city = $city;
        $this->country = $country;
        $this->editorUserId = $editorUserId;
        $this->expMonth = $expMonth;
        $this->expYear = $expYear;
        $this->firstName = $firstName;
        $this->invoice = $invoice;
        $this->lastName = $lastName;
        $this->isReplaceOldCard = $isReplaceOldCard;
        $this->paymentUrl = $paymentUrl;
        $this->phone = $phone;
        $this->sessionId = $sessionId;
        $this->state = $state;
        $this->zip = $zip;
        return $this;
    }
}
