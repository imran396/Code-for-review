<?php
/**
 * SAM-11027: Stacked Tax. Public My Invoice pages. Save user data before CC charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\ShippingInfo\Common;

use Sam\Core\Service\CustomizableClass;

class MyInvoiceItemFormShippingInfoInput extends CustomizableClass
{
    public int $invoiceId;
    public int $editorUserId;
    public string $shippingCompanyName;
    public string $shippingFirstName;
    public string $shippingLastName;
    public string $shippingCountryCode;
    public string $shippingAddress;
    public string $shippingAddress2;
    public string $shippingAddress3;
    public string $shippingCity;
    public string $shippingState;
    public string $shippingZip;
    public string $shippingPhone;
    public string $shippingFax;
    public string $contactType;
    public bool $isReadOnlyDb;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $invoiceId,
        int $editorUserId,
        string $shippingCompanyName,
        string $shippingFirstName,
        string $shippingLastName,
        string $shippingCountry,
        string $shippingAddress,
        string $shippingAddress2,
        string $shippingAddress3,
        string $shippingCity,
        string $shippingState,
        string $shippingZip,
        string $shippingPhone,
        string $shippingFax,
        string $contactType,
        bool $isReadOnlyDb = false,
    ): static {
        $this->invoiceId = $invoiceId;
        $this->editorUserId = $editorUserId;
        $this->shippingCompanyName = $shippingCompanyName;
        $this->shippingFirstName = $shippingFirstName;
        $this->shippingLastName = $shippingLastName;
        $this->shippingCountryCode = $shippingCountry;
        $this->shippingAddress = $shippingAddress;
        $this->shippingAddress2 = $shippingAddress2;
        $this->shippingAddress3 = $shippingAddress3;
        $this->shippingCity = $shippingCity;
        $this->shippingState = $shippingState;
        $this->shippingZip = $shippingZip;
        $this->shippingPhone = $shippingPhone;
        $this->shippingFax = $shippingFax;
        $this->contactType = $contactType;
        $this->isReadOnlyDb = $isReadOnlyDb;
        return $this;
    }
}
