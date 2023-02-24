<?php
/**
 * SAM-11027: Stacked Tax. Public My Invoice pages. Save user data before CC charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Common;

use Sam\Core\Service\CustomizableClass;

class MyInvoiceItemFormBillingInfoInput extends CustomizableClass
{
    public int $invoiceId;
    public int $editorUserId;
    public string $ccNumber;
    public string $ccNumberEway;
    public string $expMonth;
    public string $expYear;
    public ?int $ccType;
    public string $billingCompanyName;
    public string $billingFirstName;
    public string $billingLastName;
    public string $billingCountryCode;
    public string $billingAddress;
    public string $billingAddress2;
    public string $billingAddress3;
    public string $billingCity;
    public string $billingState;
    public string $billingZip;
    public string $billingPhone;
    public string $billingFax;
    public string $billingBankWireRouteNumber;
    public string $billingBankWireAccountNumber;
    public string $billingBankWireAccountType;
    public string $billingBankWireAccountHolderType;
    public string $billingBankWireName;
    public string $billingBankWireAccountName;
    public int $paymentMethodId;
    public bool $wasCcEdited;
    public string $wirePaymentGateway = '';
    public bool $isReadOnlyDb;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $invoiceId,
        int $editorUserId,
        string $ccNumber,
        string $ccNumberEway,
        string $expMonth,
        string $expYear,
        ?int $ccType,
        string $billingCompanyName,
        string $billingFirstName,
        string $billingLastName,
        string $billingCountry,
        string $billingAddress,
        string $billingAddress2,
        string $billingAddress3,
        string $billingCity,
        string $billingState,
        string $billingZip,
        string $billingPhone,
        string $billingFax,
        string $billingBankWireRouteNumber,
        string $billingBankWireAccountNumber,
        string $billingBankWireAccountType,
        string $billingBankWireAccountHolderType,
        string $billingBankWireName,
        string $billingBankWireAccountName,
        int $paymentMethodId,
        bool $wasCcEdited,
        bool $isReadOnlyDb = false,
    ): static {
        $this->invoiceId = $invoiceId;
        $this->editorUserId = $editorUserId;
        $this->ccNumber = $ccNumber;
        $this->ccNumberEway = $ccNumberEway;
        $this->expMonth = $expMonth;
        $this->expYear = $expYear;
        $this->ccType = $ccType;
        $this->billingCompanyName = $billingCompanyName;
        $this->billingFirstName = $billingFirstName;
        $this->billingLastName = $billingLastName;
        $this->billingCountryCode = $billingCountry;
        $this->billingAddress = $billingAddress;
        $this->billingAddress2 = $billingAddress2;
        $this->billingAddress3 = $billingAddress3;
        $this->billingCity = $billingCity;
        $this->billingState = $billingState;
        $this->billingZip = $billingZip;
        $this->billingPhone = $billingPhone;
        $this->billingFax = $billingFax;
        $this->billingBankWireRouteNumber = $billingBankWireRouteNumber;
        $this->billingBankWireAccountNumber = $billingBankWireAccountNumber;
        $this->billingBankWireAccountType = $billingBankWireAccountType;
        $this->billingBankWireAccountHolderType = $billingBankWireAccountHolderType;
        $this->billingBankWireName = $billingBankWireName;
        $this->billingBankWireAccountName = $billingBankWireAccountName;
        $this->paymentMethodId = $paymentMethodId;
        $this->wasCcEdited = $wasCcEdited;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }

    public function setWirePaymentGateway(string $wirePaymentGateway): static
    {
        $this->wirePaymentGateway = $wirePaymentGateway;
        return $this;
    }
}
