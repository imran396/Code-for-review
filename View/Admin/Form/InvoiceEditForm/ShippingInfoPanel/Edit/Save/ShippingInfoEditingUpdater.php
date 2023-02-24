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

namespace Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit\Save;

use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceUserShipping\InvoiceUserShippingWriteRepositoryAwareTrait;
use Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit\ShippingInfoEditingInput;

/**
 * Class ShippingInfoEditingUpdater
 * @package Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit
 */
class ShippingInfoEditingUpdater extends CustomizableClass
{
    use InvoiceUserShippingWriteRepositoryAwareTrait;
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
     * @param ShippingInfoEditingInput $input
     * @param bool $isReadOnlyDb
     * @return void
     */
    public function update(
        ShippingInfoEditingInput $input,
        bool $isReadOnlyDb = false
    ): void {
        $invoiceUserShipping = $this->getInvoiceUserLoader()
            ->clear()
            ->loadInvoiceUserShippingOrCreate($input->invoiceId, $isReadOnlyDb);

        $invoiceUserShipping->CompanyName = trim($input->shippingCompanyName);
        $invoiceUserShipping->FirstName = trim($input->shippingFirstName);
        $invoiceUserShipping->LastName = trim($input->shippingLastName);
        $invoiceUserShipping->Phone = trim($input->shippingPhone);
        $invoiceUserShipping->Fax = trim($input->shippingFax);
        $invoiceUserShipping->Address = trim($input->shippingAddress);
        $invoiceUserShipping->Address2 = trim($input->shippingAddress2);
        $invoiceUserShipping->Address3 = trim($input->shippingAddress3);
        $invoiceUserShipping->City = trim($input->shippingCity);
        $invoiceUserShipping->Zip = trim($input->shippingZip);

        $shippingCountry = $input->shippingCountryCode;
        $invoiceUserShipping->Country = $shippingCountry;
        $addressChecker = AddressChecker::new();
        if ($addressChecker->isUsa($shippingCountry)) {
            $invoiceUserShipping->State = $input->shippingStateUsCode;
        } elseif ($addressChecker->isCanada($shippingCountry)) {
            $invoiceUserShipping->State = $input->shippingStateCaCode;
        } elseif ($addressChecker->isMexico($shippingCountry)) {
            $invoiceUserShipping->State = $input->shippingStateMxCode;
        } else {
            $invoiceUserShipping->State = trim($input->shippingStateName);
        }

        $this->getInvoiceUserShippingWriteRepository()->saveWithModifier($invoiceUserShipping, $input->editorUserId);
    }

}
