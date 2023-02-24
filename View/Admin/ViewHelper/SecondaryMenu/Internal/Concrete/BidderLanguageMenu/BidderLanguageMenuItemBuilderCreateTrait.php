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

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BidderLanguageMenu;

/**
 * Trait BidderLanguageMenuItemBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\BidderLanguageMenu
 */
trait BidderLanguageMenuItemBuilderCreateTrait
{
    protected ?BidderLanguageMenuItemBuilder $bidderLanguageMenuItemBuilder = null;

    /**
     * @return BidderLanguageMenuItemBuilder
     */
    protected function createBidderLanguageMenuItemBuilder(): BidderLanguageMenuItemBuilder
    {
        return $this->bidderLanguageMenuItemBuilder ?: BidderLanguageMenuItemBuilder::new();
    }

    /**
     * @param BidderLanguageMenuItemBuilder $bidderLanguageMenuItemBuilder
     * @return $this
     * @internal
     */
    public function setBidderLanguageMenuItemBuilder(BidderLanguageMenuItemBuilder $bidderLanguageMenuItemBuilder): static
    {
        $this->bidderLanguageMenuItemBuilder = $bidderLanguageMenuItemBuilder;
        return $this;
    }
}
