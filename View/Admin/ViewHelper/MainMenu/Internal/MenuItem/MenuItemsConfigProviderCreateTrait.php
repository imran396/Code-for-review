<?php
/**
 * SAM-7717: Refactor admin menu tabs rendering module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem;

/**
 * Trait MenuItemsConfigProviderCreateTrait
 * @package Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem
 * @internal
 */
trait MenuItemsConfigProviderCreateTrait
{
    protected ?MenuItemsConfigProvider $menuItemsConfigProvider = null;

    /**
     * @return MenuItemsConfigProvider
     */
    protected function createMenuItemsConfigProvider(): MenuItemsConfigProvider
    {
        return $this->menuItemsConfigProvider ?: MenuItemsConfigProvider::new();
    }

    /**
     * @param MenuItemsConfigProvider $menuItemsConfigProvider
     * @return static
     * @internal
     */
    public function setMenuItemsConfigProvider(MenuItemsConfigProvider $menuItemsConfigProvider): static
    {
        $this->menuItemsConfigProvider = $menuItemsConfigProvider;
        return $this;
    }
}
