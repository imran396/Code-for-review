<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\CustomTemplateMenu;

/**
 * Trait CustomTemplateMenuItemBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\CustomTemplateMenu
 */
trait CustomTemplateMenuItemBuilderCreateTrait
{
    protected ?CustomTemplateMenuItemBuilder $customTemplateMenuItemBuilder = null;

    /**
     * @return CustomTemplateMenuItemBuilder
     */
    protected function createCustomTemplateMenuItemBuilder(): CustomTemplateMenuItemBuilder
    {
        return $this->customTemplateMenuItemBuilder ?: CustomTemplateMenuItemBuilder::new();
    }

    /**
     * @param CustomTemplateMenuItemBuilder $customTemplateMenuItemBuilder
     * @return $this
     * @internal
     */
    public function setCustomTemplateMenuItemBuilder(CustomTemplateMenuItemBuilder $customTemplateMenuItemBuilder): static
    {
        $this->customTemplateMenuItemBuilder = $customTemplateMenuItemBuilder;
        return $this;
    }
}
