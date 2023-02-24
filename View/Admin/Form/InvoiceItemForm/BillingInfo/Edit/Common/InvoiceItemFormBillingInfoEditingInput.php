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

namespace Sam\View\Admin\Form\InvoiceItemForm\BillingInfo\Edit\Common;

use Sam\Core\Service\CustomizableClass;

class InvoiceItemFormBillingInfoEditingInput extends CustomizableClass
{
    public int $invoiceId;
    public int $editorUserId;
    public string $billingCompanyName;
    public string $billingFirstName;
    public string $billingLastName;
    public string $billingCountryCode;
    public string $billingAddress;
    public string $billingAddress2;
    public string $billingAddress3;
    public string $billingCity;
    public string $billingStateName;
    public string $billingStateUsCode;
    public string $billingStateCaCode;
    public string $billingStateMxCode;
    public string $billingZip;
    public string $billingEmail;
    public string $billingPhone;
    public string $billingFax;

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
        string $billingCompanyName,
        string $billingFirstName,
        string $billingLastName,
        string $billingCountry,
        string $billingAddress,
        string $billingAddress2,
        string $billingAddress3,
        string $billingCity,
        string $billingStateName,
        string $billingStateUsCode,
        string $billingStateCaCode,
        string $billingStateMxCode,
        string $billingZip,
        string $billingEmail,
        string $billingPhone,
        string $billingFax
    ): static {
        $this->invoiceId = $invoiceId;
        $this->editorUserId = $editorUserId;
        $this->billingCompanyName = $billingCompanyName;
        $this->billingFirstName = $billingFirstName;
        $this->billingLastName = $billingLastName;
        $this->billingCountryCode = $billingCountry;
        $this->billingAddress = $billingAddress;
        $this->billingAddress2 = $billingAddress2;
        $this->billingAddress3 = $billingAddress3;
        $this->billingCity = $billingCity;
        $this->billingStateName = $billingStateName;
        $this->billingStateUsCode = $billingStateUsCode;
        $this->billingStateCaCode = $billingStateCaCode;
        $this->billingStateMxCode = $billingStateMxCode;
        $this->billingZip = $billingZip;
        $this->billingEmail = $billingEmail;
        $this->billingPhone = $billingPhone;
        $this->billingFax = $billingFax;
        return $this;
    }
}
