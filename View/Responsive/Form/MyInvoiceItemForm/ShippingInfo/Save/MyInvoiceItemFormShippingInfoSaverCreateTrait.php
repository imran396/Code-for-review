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


trait MyInvoiceItemFormShippingInfoSaverCreateTrait
{
    protected ?MyInvoiceItemFormShippingInfoSaver $myInvoiceItemFormShippingInfoSaver = null;

    /**
     * @return MyInvoiceItemFormShippingInfoSaver
     */
    protected function createMyInvoiceItemFormShippingInfoSaver(): MyInvoiceItemFormShippingInfoSaver
    {
        return $this->myInvoiceItemFormShippingInfoSaver ?: MyInvoiceItemFormShippingInfoSaver::new();
    }

    /**
     * @param MyInvoiceItemFormShippingInfoSaver $myInvoiceItemFormShippingInfoSaver
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setMyInvoiceItemFormShippingInfoSaver(MyInvoiceItemFormShippingInfoSaver $myInvoiceItemFormShippingInfoSaver): static
    {
        $this->myInvoiceItemFormShippingInfoSaver = $myInvoiceItemFormShippingInfoSaver;
        return $this;
    }
}
