<?php
/**
 * SAM-10898: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Billing Info and Shipping Info management
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\ShippingInfo\Edit\Common;

use Sam\Core\Service\CustomizableClass;

class InvoiceItemFormShippingInfoEditingInput extends CustomizableClass
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
    public string $shippingStateName;
    public string $shippingStateUsCode;
    public string $shippingStateCaCode;
    public string $shippingStateMxCode;
    public string $shippingZip;
    public string $shippingPhone;
    public string $shippingFax;

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
        string $shippingCompanyName,
        string $shippingFirstName,
        string $shippingLastName,
        string $shippingCountry,
        string $shippingAddress,
        string $shippingAddress2,
        string $shippingAddress3,
        string $shippingCity,
        string $shippingStateName,
        string $shippingStateUsCode,
        string $shippingStateCaCode,
        string $shippingStateMxCode,
        string $shippingZip,
        string $shippingPhone,
        string $shippingFax
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
        $this->shippingStateName = $shippingStateName;
        $this->shippingStateUsCode = $shippingStateUsCode;
        $this->shippingStateCaCode = $shippingStateCaCode;
        $this->shippingStateMxCode = $shippingStateMxCode;
        $this->shippingZip = $shippingZip;
        $this->shippingPhone = $shippingPhone;
        $this->shippingFax = $shippingFax;
        return $this;
    }
}
