<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Meta\Internal\Load;

/**
 * Trait DataProviderCreateTrait
 * @package Sam\Lot\LotFieldConfig\Meta\Internal\Load
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
     * @return static
     * @internal
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }
}
