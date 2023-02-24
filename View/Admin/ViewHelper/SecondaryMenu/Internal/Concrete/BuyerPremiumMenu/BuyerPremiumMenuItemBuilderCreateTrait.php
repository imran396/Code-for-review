<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BuyerPremiumMenu;

/**
 * Trait BuyerPremiumMenuItemBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BuyerPremiumMenu
 */
trait BuyerPremiumMenuItemBuilderCreateTrait
{
    protected ?BuyerPremiumMenuItemBuilder $buyerPremiumMenuItemBuilder = null;

    /**
     * @return BuyerPremiumMenuItemBuilder
     */
    protected function createBuyerPremiumMenuItemBuilder(): BuyerPremiumMenuItemBuilder
    {
        return $this->buyerPremiumMenuItemBuilder ?: BuyerPremiumMenuItemBuilder::new();
    }

    /**
     * @param BuyerPremiumMenuItemBuilder $buyerPremiumMenuItemBuilder
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setBuyerPremiumMenuItemBuilder(BuyerPremiumMenuItemBuilder $buyerPremiumMenuItemBuilder): static
    {
        $this->buyerPremiumMenuItemBuilder = $buyerPremiumMenuItemBuilder;
        return $this;
    }
}
