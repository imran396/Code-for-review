<?php
/**
 * Data Loader Create Trait
 *
 * SAM-5876: Refactor data loader for Barcode List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 4, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BarcodeListForm\Single\Load;

/**
 * Trait DataLoaderCreateTrait
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
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setDataLoader(DataLoader $dataLoader): static
    {
        $this->dataLoader = $dataLoader;
        return $this;
    }
}
