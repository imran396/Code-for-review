<?php
/**
 * SAM-7717: Refactor admin menu tabs rendering module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem;

/**
 * Trait MenuItemPrivilegeCheckerCreateTrait
 * @package Sam\View\Admin\ViewHelper\MainMenu\Internal\MenuItem
 * @internal
 */
trait MenuItemPrivilegeCheckerCreateTrait
{
    protected ?MenuItemPrivilegeChecker $menuItemPrivilegeChecker = null;

    /**
     * @return MenuItemPrivilegeChecker
     */
    protected function createMenuItemPrivilegeChecker(): MenuItemPrivilegeChecker
    {
        return $this->menuItemPrivilegeChecker ?: MenuItemPrivilegeChecker::new();
    }

    /**
     * @param MenuItemPrivilegeChecker $menuItemPrivilegeChecker
     * @return static
     * @internal
     */
    public function setMenuItemPrivilegeChecker(MenuItemPrivilegeChecker $menuItemPrivilegeChecker): static
    {
        $this->menuItemPrivilegeChecker = $menuItemPrivilegeChecker;
        return $this;
    }
}
