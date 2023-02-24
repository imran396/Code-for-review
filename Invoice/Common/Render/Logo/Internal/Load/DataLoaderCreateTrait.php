<?php
/**
 * SAM-10234: Add unit tests for path resolver classes
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Apr 09, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Render\Logo\Internal\Load;

/**
 * Trait DataLoaderCreateTrait
 * @package Sam\Invoice\Common\Render\Logo\Internal\Load
 */
trait DataLoaderCreateTrait
{
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
