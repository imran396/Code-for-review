<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettingMenu;

/**
 * Trait SettingsMenuItemBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettingMenu
 */
trait SettingsMenuItemBuilderCreateTrait
{
    protected ?SettingMenuItemBuilder $settingsMenuItemBuilder = null;

    /**
     * @return SettingMenuItemBuilder
     */
    protected function createSettingsMenuItemBuilder(): SettingMenuItemBuilder
    {
        return $this->settingsMenuItemBuilder ?: SettingMenuItemBuilder::new();
    }

    /**
     * @param SettingMenuItemBuilder $settingsMenuItemBuilder
     * @return $this
     * @internal
     */
    public function setSettingsMenuItemBuilder(SettingMenuItemBuilder $settingsMenuItemBuilder): static
    {
        $this->settingsMenuItemBuilder = $settingsMenuItemBuilder;
        return $this;
    }
}
