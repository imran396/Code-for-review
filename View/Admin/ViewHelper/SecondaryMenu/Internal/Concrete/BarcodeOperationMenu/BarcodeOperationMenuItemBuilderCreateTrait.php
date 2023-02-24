<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 1, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BarcodeOperationMenu;

/**
 * Trait BarcodeOperationMenuItemBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Inernal\Concrete\BarcodeOperationMenu
 */
trait BarcodeOperationMenuItemBuilderCreateTrait
{
    protected ?BarcodeOperationMenuItemBuilder $barcodeOperationMenuItemBuilder = null;

    /**
     * @return BarcodeOperationMenuItemBuilder
     */
    protected function createBarcodeOperationMenuItemBuilder(): BarcodeOperationMenuItemBuilder
    {
        return $this->barcodeOperationMenuItemBuilder ?: BarcodeOperationMenuItemBuilder::new();
    }

    /**
     * @param BarcodeOperationMenuItemBuilder $barcodeOperationMenuItemBuilder
     * @return $this
     * @internal
     */
    public function setBarcodeOperationMenuItemBuilder(BarcodeOperationMenuItemBuilder $barcodeOperationMenuItemBuilder): static
    {
        $this->barcodeOperationMenuItemBuilder = $barcodeOperationMenuItemBuilder;
        return $this;
    }
}
