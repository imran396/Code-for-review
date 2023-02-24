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

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\BillingInfo\Save\Internal\Load;

trait DataProviderCreateTrait
{
    /**
     * @var DataProvider|null
     */
    protected ?DataProvider $dataProvider = null;

    /**
     * @return DataProvider
     */
    protected function createDataProvider(): DataProvider
    {
        return $this->dataProvider ?: DataProvider::new();
    }

    /**
     * @param DataProvider $dataProvider
     * @return DataProviderCreateTrait
     * @internal
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }
}
