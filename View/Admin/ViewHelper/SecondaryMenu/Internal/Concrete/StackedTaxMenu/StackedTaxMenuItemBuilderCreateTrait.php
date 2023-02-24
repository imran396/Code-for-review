<?php
/**
 *
 * SAM-10940: Stacked Tax. Add to admin menu (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\StackedTaxMenu;

/**
 * Trait StackedTaxMenuItemBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\StackedTaxMenu
 */
trait StackedTaxMenuItemBuilderCreateTrait
{
    protected ?StackedTaxMenuItemBuilder $stackedTaxMenuItemBuilder = null;

    /**
     * @return StackedTaxMenuItemBuilder
     */
    protected function createStackedTaxMenuItemBuilder(): StackedTaxMenuItemBuilder
    {
        return $this->stackedTaxMenuItemBuilder ?: StackedTaxMenuItemBuilder::new();
    }

    /**
     * @param StackedTaxMenuItemBuilder $stackedTaxMenuItemBuilder
     * @return $this
     * @internal
     */
    public function setStackedTaxMenuItemBuilder(StackedTaxMenuItemBuilder $stackedTaxMenuItemBuilder): static
    {
        $this->stackedTaxMenuItemBuilder = $stackedTaxMenuItemBuilder;
        return $this;
    }
}
