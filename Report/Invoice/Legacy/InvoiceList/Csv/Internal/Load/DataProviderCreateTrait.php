<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Invoice\Legacy\InvoiceList\Csv\Internal\Load;

/**
 * Trait DataProviderCreateTrait
 * @package Sam\Report\Invoice\Legacy\InvoiceList\Csv\Internal\Load
 */
trait DataProviderCreateTrait
{
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
     * @return $this
     * @internal
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }
}
