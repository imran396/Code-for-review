<?php
/**
 * Single Barcode Updater Create Trait
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

namespace Sam\View\Admin\Form\BarcodeListForm\Single\Save;

/**
 * Trait SingleBarcodeUpdaterCreateTrait
 */
trait SingleBarcodeUpdaterCreateTrait
{
    protected ?SingleBarcodeUpdater $singleBarcodeUpdater = null;

    /**
     * @return SingleBarcodeUpdater
     */
    protected function createSingleBarcodeUpdater(): SingleBarcodeUpdater
    {
        return $this->singleBarcodeUpdater ?: SingleBarcodeUpdater::new();
    }

    /**
     * @param SingleBarcodeUpdater $singleBarcodeUpdater
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setSingleBarcodeUpdater(SingleBarcodeUpdater $singleBarcodeUpdater): static
    {
        $this->singleBarcodeUpdater = $singleBarcodeUpdater;
        return $this;
    }
}
