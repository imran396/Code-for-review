<?php
/**
 * <Description of class>
 *
 * SAM-10918: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract PayTrace invoice charging
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


namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\PayTrace;

use Invoice;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AdminInvoiceEditPayTraceChargingInput
 * @package
 */
class AdminInvoiceEditPayTraceChargingInput extends CustomizableClass
{
    public Invoice $invoice;
    public array $invoicedAuctionDtos;
    public int $ccType;
    public int $editorUserId;
    public string $address1;
    public string $amountFormatted;
    public string $ccCode;
    public string $ccNumber;
    public string $chargeOption;
    public string $city;
    public string $country;
    public string $expMonth;
    public string $expYear;
    public string $firstName;
    public bool $isReplaceOldCard;
    public string $lastName;
    public string $state;
    public string $zip;
    public readonly bool $onlyCharge;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        array $invoicedAuctionDtos,
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
        string $address1,
        string $zip,
        bool $onlyCharge = false,
    ): static {
        $this->invoicedAuctionDtos = $invoicedAuctionDtos;
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
        $this->country = $country;
        $this->state = $state;
        $this->city = $city;
        $this->address1 = $address1;
        $this->zip = $zip;
        $this->onlyCharge = $onlyCharge;
        return $this;
    }
}
