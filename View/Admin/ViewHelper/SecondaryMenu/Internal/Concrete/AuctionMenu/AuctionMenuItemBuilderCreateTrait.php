<?php
/**
 * SAM-9573: Refactor admin secondary menu for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\AuctionMenu;

/**
 * Trait AuctionMenuItemBuilderCreateTrait
 * @package Sam\View\Admin\ViewHelper\SecondaryMenu\Internal\Concrete\AuctionMenu
 */
trait AuctionMenuItemBuilderCreateTrait
{
    protected ?AuctionMenuItemBuilder $auctionMenuItemBuilder = null;

    /**
     * @return AuctionMenuItemBuilder
     */
    protected function createAuctionMenuItemBuilder(): AuctionMenuItemBuilder
    {
        return $this->auctionMenuItemBuilder ?: AuctionMenuItemBuilder::new();
    }

    /**
     * @param AuctionMenuItemBuilder $auctionMenuItemBuilder
     * @return $this
     * @internal
     */
    public function setAuctionMenuItemBuilder(AuctionMenuItemBuilder $auctionMenuItemBuilder): static
    {
        $this->auctionMenuItemBuilder = $auctionMenuItemBuilder;
        return $this;
    }
}
