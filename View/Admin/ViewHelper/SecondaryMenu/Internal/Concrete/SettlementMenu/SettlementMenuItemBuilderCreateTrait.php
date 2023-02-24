<?php
/**
 * SAM-9795: Check Printing for Settlements: Implementation of html layout and view layer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-24, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettlementMenu;

/**
 * Trait SettlementMenuItemBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\SettlementMenu
 */
trait SettlementMenuItemBuilderCreateTrait
{
    protected ?SettlementMenuItemBuilder $settlementMenuItemBuilder = null;

    /**
     * @return SettlementMenuItemBuilder
     */
    protected function createSettlementMenuItemBuilder(): SettlementMenuItemBuilder
    {
        return $this->settlementMenuItemBuilder ?: SettlementMenuItemBuilder::new();
    }

    /**
     * @param SettlementMenuItemBuilder $settlementMenuItemBuilder
     * @return $this
     * @internal
     */
    public function setSettlementMenuItemBuilder(SettlementMenuItemBuilder $settlementMenuItemBuilder): static
    {
        $this->settlementMenuItemBuilder = $settlementMenuItemBuilder;
        return $this;
    }
}




