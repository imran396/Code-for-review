<?php
/**
 * SAM-7973: Refactor \Exrate_Sync_Ajax
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\ExchangeRate\Internal;

/**
 * Trait DataLoaderCreateTrait
 * @package Sam\Currency\ExchangeRate\Internal
 * @internal
 */
trait DataLoaderCreateTrait
{
    /**
     * @var DataLoader|null
     */
    protected ?DataLoader $dataLoader = null;

    /**
     * @return DataLoader
     */
    protected function createDataLoader(): DataLoader
    {
        return $this->dataLoader ?: DataLoader::new();
    }

    /**
     * @param DataLoader $dataLoader
     * @return static
     * @internal
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }
}
