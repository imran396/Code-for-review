<?php
/**
 * SAM-3566: Refactoring for user authorization logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/25/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\User\Auth\Credentials\Load;


/**
 * Trait DataProviderAwareTrait
 * @internal
 * @package
 */
trait DataProviderAwareTrait
{
    protected ?DataProvider $dataProvider = null;

    /**
     * @return DataProvider
     */
    protected function getDataProvider(): DataProvider
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new();
        }
        return $this->dataProvider;
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
