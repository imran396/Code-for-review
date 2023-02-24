<?php
/**
 * My Invoice Data Loader Create Trait
 *
 * SAM-6307: Refactor My Invoice List page at client side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceList\Load;

/**
 * Trait MyInvoiceDataLoaderCreateTrait
 */
trait MyInvoiceDataLoaderCreateTrait
{
    protected ?MyInvoiceDataLoader $myInvoiceDataLoader = null;

    /**
     * @return MyInvoiceDataLoader
     */
    protected function createMyInvoiceDataLoader(): MyInvoiceDataLoader
    {
        $myInvoiceDataLoader = $this->myInvoiceDataLoader ?: MyInvoiceDataLoader::new();
        return $myInvoiceDataLoader;
    }

    /**
     * @param MyInvoiceDataLoader $myInvoiceDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setMyInvoiceDataLoader(MyInvoiceDataLoader $myInvoiceDataLoader): static
    {
        $this->myInvoiceDataLoader = $myInvoiceDataLoader;
        return $this;
    }
}
