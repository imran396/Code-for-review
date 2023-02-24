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

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\ShippingInfo\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Storage\WriteRepository\Entity\InvoiceUserShipping\InvoiceUserShippingWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserShipping\UserShippingWriteRepositoryAwareTrait;
use Sam\View\Responsive\Form\MyInvoiceItemForm\ShippingInfo\Common\MyInvoiceItemFormShippingInfoInput as Input;
use Sam\View\Responsive\Form\MyInvoiceItemForm\ShippingInfo\Save\Internal\Load\DataProviderCreateTrait;

class MyInvoiceItemFormShippingInfoSaver extends CustomizableClass
{
    use DataProviderCreateTrait;
    use InvoiceUserShippingWriteRepositoryAwareTrait;
    use UserShippingWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Input $input
     * @return void
     */
    public function save(Input $input): void
    {
        $dataProvider = $this->createDataProvider();
        $invoice = $dataProvider->loadInvoice($input->invoiceId);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($input->invoiceId);
        }
        $userShipping = $dataProvider->loadUserShippingOrCreate($invoice->BidderId, $input->isReadOnlyDb);
        $shippingCompanyName = trim($input->shippingCompanyName);
        $shippingFirstName = trim($input->shippingFirstName);
        $shippingLastName = trim($input->shippingLastName);
        $shippingAddress = trim($input->shippingAddress);
        $shippingCity = trim($input->shippingCity);


        $shippingPhone = trim($input->shippingPhone);

        $userShipping->CompanyName = $input->shippingCompanyName;
        $userShipping->FirstName = $shippingFirstName;
        $userShipping->LastName = $shippingLastName;
        $userShipping->Phone = $shippingPhone;
        $userShipping->Fax = trim($input->shippingFax);
        $userShipping->Country = $input->shippingCountryCode;
        $userShipping->Address = $shippingAddress;
        $userShipping->Address2 = trim($input->shippingAddress2);
        $userShipping->Address3 = trim($input->shippingAddress3);
        $userShipping->City = $shippingCity;
        $userShipping->State = $input->shippingState;
        $userShipping->Zip = trim($input->shippingZip);
        $userShipping->ContactType = (int)$input->contactType;
        $this->getUserShippingWriteRepository()->saveWithModifier($userShipping, $input->editorUserId);

        $invoiceShipping = $dataProvider->loadInvoiceUserShippingOrCreate($input->invoiceId, $input->isReadOnlyDb);
        $invoiceShipping->CompanyName = $shippingCompanyName;
        $invoiceShipping->FirstName = $shippingFirstName;
        $invoiceShipping->LastName = $shippingLastName;
        $invoiceShipping->Phone = $shippingPhone;
        $invoiceShipping->Fax = trim($input->shippingFax);
        $invoiceShipping->Country = $input->shippingCountryCode;
        $invoiceShipping->Address = $shippingAddress;
        $invoiceShipping->Address2 = trim($input->shippingAddress2);
        $invoiceShipping->Address3 = trim($input->shippingAddress3);
        $invoiceShipping->City = $shippingCity;
        $invoiceShipping->State = $input->shippingState;
        $invoiceShipping->Zip = $input->shippingZip;
        $this->getInvoiceUserShippingWriteRepository()->saveWithModifier($invoiceShipping, $input->editorUserId);
    }
}
