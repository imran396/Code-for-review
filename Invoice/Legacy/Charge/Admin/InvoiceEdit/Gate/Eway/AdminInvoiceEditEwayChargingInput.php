<?php
/**
 * SAM-10912: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Eway invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Eway;

use Invoice;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AdminInvoiceEditEwayChargingInput
 * @package Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Eway
 */
class AdminInvoiceEditEwayChargingInput extends CustomizableClass
{
    public readonly Invoice $invoice;
    public readonly array $invoicedAuctionDtos;
    public readonly bool $isReplaceOldCard;
    public readonly int $ccType;
    public readonly int $editorUserId;
    public readonly string $amountFormatted;
    public readonly string $ccCode;
    public readonly string $ccNumber;
    public readonly string $chargeOption;
    public readonly string $ewayCardcvn;
    public readonly string $ewayCardnumber;
    public readonly string $expMonth;
    public readonly string $expYear;
    public readonly string $firstName;
    public readonly string $lastName;
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
        int $editorUserId,
        Invoice $invoice,
        string $amountFormatted,
        array $invoicedAuctionDtos,
        bool $isReplaceOldCard,
        string $chargeOption,
        int $ccType,
        string $ccNumber,
        string $expYear,
        string $expMonth,
        string $ccCode,
        string $ewayCardnumber,
        string $ewayCardcvn,
        string $firstName,
        string $lastName,
        bool $onlyCharge = false,
    ): static {
        $this->amountFormatted = $amountFormatted;
        $this->ccCode = $ccCode;
        $this->ccNumber = $ccNumber;
        $this->ccType = $ccType;
        $this->chargeOption = $chargeOption;
        $this->editorUserId = $editorUserId;
        $this->ewayCardcvn = $ewayCardcvn;
        $this->ewayCardnumber = $ewayCardnumber;
        $this->expMonth = $expMonth;
        $this->expYear = $expYear;
        $this->firstName = $firstName;
        $this->invoice = $invoice;
        $this->invoicedAuctionDtos = $invoicedAuctionDtos;
        $this->isReplaceOldCard = $isReplaceOldCard;
        $this->lastName = $lastName;
        $this->onlyCharge = $onlyCharge;
        return $this;
    }
}
