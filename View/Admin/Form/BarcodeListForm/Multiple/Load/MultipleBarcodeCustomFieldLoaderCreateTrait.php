<?php
/**
 * Multiple Barcode Custom Field Loader Create Trait
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

namespace Sam\View\Admin\Form\BarcodeListForm\Multiple\Load;

/**
 * Trait MultipleBarcodeCustomFieldLoaderCreateTrait
 */
trait MultipleBarcodeCustomFieldLoaderCreateTrait
{
    protected ?MultipleBarcodeCustomFieldLoader $multipleBarcodeCustomFieldLoader = null;

    /**
     * @return MultipleBarcodeCustomFieldLoader
     */
    protected function createMultipleBarcodeCustomFieldLoader(): MultipleBarcodeCustomFieldLoader
    {
        $multipleBarcodeCustomFieldLoader = $this->multipleBarcodeCustomFieldLoader
            ?: MultipleBarcodeCustomFieldLoader::new();
        return $multipleBarcodeCustomFieldLoader;
    }

    /**
     * @param MultipleBarcodeCustomFieldLoader $multipleBarcodeCustomFieldLoader
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setMultipleBarcodeCustomFieldLoader(
        MultipleBarcodeCustomFieldLoader $multipleBarcodeCustomFieldLoader
    ): static {
        $this->multipleBarcodeCustomFieldLoader = $multipleBarcodeCustomFieldLoader;
        return $this;
    }
}
