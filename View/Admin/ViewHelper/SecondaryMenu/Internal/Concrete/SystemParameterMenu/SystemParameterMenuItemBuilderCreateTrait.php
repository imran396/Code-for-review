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

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SystemParameterMenu;

/**
 * Trait SystemParameterMenuBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SystemParameterMenu
 */
trait SystemParameterMenuItemBuilderCreateTrait
{
    protected ?SystemParameterMenuItemBuilder $systemParameterMenuBuilder = null;

    /**
     * @return SystemParameterMenuItemBuilder
     */
    protected function createSystemParameterMenuItemBuilder(): SystemParameterMenuItemBuilder
    {
        return $this->systemParameterMenuBuilder ?: SystemParameterMenuItemBuilder::new();
    }

    /**
     * @param SystemParameterMenuItemBuilder $systemParameterMenuBuilder
     * @return $this
     * @internal
     */
    public function setSystemParameterMenuItemBuilder(SystemParameterMenuItemBuilder $systemParameterMenuBuilder): static
    {
        $this->systemParameterMenuBuilder = $systemParameterMenuBuilder;
        return $this;
    }
}
