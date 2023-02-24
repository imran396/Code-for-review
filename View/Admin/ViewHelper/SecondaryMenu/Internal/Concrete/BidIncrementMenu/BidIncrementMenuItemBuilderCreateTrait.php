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

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BidIncrementMenu;

/**
 * Trait BidIncrementMenuItemBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BidIncrememntMenu
 */
trait BidIncrementMenuItemBuilderCreateTrait
{
    protected ?BidIncrementMenuItemBuilder $bidIncrementMenuItemBuilder = null;

    /**
     * @return BidIncrementMenuItemBuilder
     */
    protected function createBidIncrementMenuItemBuilder(): BidIncrementMenuItemBuilder
    {
        return $this->bidIncrementMenuItemBuilder ?: BidIncrementMenuItemBuilder::new();
    }

    /**
     * @param BidIncrementMenuItemBuilder $bidIncrementMenuItemBuilder
     * @return $this
     * @internal
     */
    public function setBidIncrementMenuItemBuilder(BidIncrementMenuItemBuilder $bidIncrementMenuItemBuilder): static
    {
        $this->bidIncrementMenuItemBuilder = $bidIncrementMenuItemBuilder;
        return $this;
    }
}
