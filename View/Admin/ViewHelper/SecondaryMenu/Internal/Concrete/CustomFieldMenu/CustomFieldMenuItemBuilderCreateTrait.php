<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\CustomFieldMenu;

/**
 * Trait CustomFieldMenuItemBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\CustomFieldMenu
 */
trait CustomFieldMenuItemBuilderCreateTrait
{
    protected ?CustomFieldMenuItemBuilder $customFieldMenuItemBuilder = null;

    /**
     * @return CustomFieldMenuItemBuilder
     */
    protected function createCustomFieldMenuItemBuilder(): CustomFieldMenuItemBuilder
    {
        return $this->customFieldMenuItemBuilder ?: CustomFieldMenuItemBuilder::new();
    }

    /**
     * @param CustomFieldMenuItemBuilder $customFieldMenuItemBuilder
     * @return $this
     * @internal
     */
    public function setCustomFieldMenuItemBuilder(CustomFieldMenuItemBuilder $customFieldMenuItemBuilder): static
    {
        $this->customFieldMenuItemBuilder = $customFieldMenuItemBuilder;
        return $this;
    }
}
