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

namespace Sam\View\Admin\Form\InvoiceItemForm\BillingInfo\Edit\Save;

use Sam\View\Admin\Form\InvoiceItemForm\BillingInfo\Edit\Common\InvoiceItemFormBillingInfoEditingInput;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceUserBilling\InvoiceUserBillingWriteRepositoryAwareTrait;

class InvoiceItemFormBillingInfoEditingUpdater extends CustomizableClass
{
    use InvoiceUserBillingWriteRepositoryAwareTrait;
    use InvoiceUserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param InvoiceItemFormBillingInfoEditingInput $input
     * @param bool $isReadOnlyDb
     * @return void
     */
    public function update(
        InvoiceItemFormBillingInfoEditingInput $input,
        bool $isReadOnlyDb = false
    ): void {
        $invoiceUserBilling = $this->getInvoiceUserLoader()
            ->clear()
            ->loadInvoiceUserBillingOrCreate($input->invoiceId, $isReadOnlyDb);

        $invoiceUserBilling->CompanyName = trim($input->billingCompanyName);
        $invoiceUserBilling->FirstName = trim($input->billingFirstName);
        $invoiceUserBilling->LastName = trim($input->billingLastName);
        $invoiceUserBilling->Phone = trim($input->billingPhone);
        $invoiceUserBilling->Fax = trim($input->billingFax);
        $invoiceUserBilling->Address = trim($input->billingAddress);
        $invoiceUserBilling->Address2 = trim($input->billingAddress2);
        $invoiceUserBilling->Address3 = trim($input->billingAddress3);
        $invoiceUserBilling->City = trim($input->billingCity);
        $invoiceUserBilling->Zip = trim($input->billingZip);
        $invoiceUserBilling->Email = trim($input->billingEmail);

        $billingCountry = $input->billingCountryCode;
        $invoiceUserBilling->Country = $billingCountry;
        $addressChecker = AddressChecker::new();
        if ($addressChecker->isUsa($billingCountry)) {
            $invoiceUserBilling->State = $input->billingStateUsCode;
        } elseif ($addressChecker->isCanada($billingCountry)) {
            $invoiceUserBilling->State = $input->billingStateCaCode;
        } elseif ($addressChecker->isMexico($billingCountry)) {
            $invoiceUserBilling->State = $input->billingStateMxCode;
        } else {
            $invoiceUserBilling->State = trim($input->billingStateName);
        }

        $this->getInvoiceUserBillingWriteRepository()->saveWithModifier($invoiceUserBilling, $input->editorUserId);
    }

}
