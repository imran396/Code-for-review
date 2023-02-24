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

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Save;

trait MyInvoiceItemFormBillingInfoSaverCreateTrait
{
    protected ?MyInvoiceItemFormBillingInfoSaver $myInvoiceItemFormBillingInfoSaver = null;

    /**
     * @return MyInvoiceItemFormBillingInfoSaver
     */
    protected function createMyInvoiceItemFormBillingInfoSaver(): MyInvoiceItemFormBillingInfoSaver
    {
        return $this->myInvoiceItemFormBillingInfoSaver ?: MyInvoiceItemFormBillingInfoSaver::new();
    }

    /**
     * @param MyInvoiceItemFormBillingInfoSaver $myInvoiceItemFormBillingInfoSaver
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setMyInvoiceItemFormBillingInfoSaver(MyInvoiceItemFormBillingInfoSaver $myInvoiceItemFormBillingInfoSaver): static
    {
        $this->myInvoiceItemFormBillingInfoSaver = $myInvoiceItemFormBillingInfoSaver;
        return $this;
    }
}
