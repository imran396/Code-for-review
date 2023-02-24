<?php
/**
 * Buyer Group List Data Loader Create Trait
 *
 * SAM-5949: Refactor buyer group list page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 26, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupListForm\Load;

/**
 * Trait BuyerGroupListDataLoaderCreateTrait
 */
trait BuyerGroupListDataLoaderCreateTrait
{
    protected ?BuyerGroupListDataLoader $buyerGroupListDataLoader = null;

    /**
     * @return BuyerGroupListDataLoader
     */
    protected function createBuyerGroupListDataLoader(): BuyerGroupListDataLoader
    {
        return $this->buyerGroupListDataLoader ?: BuyerGroupListDataLoader::new();
    }

    /**
     * @param BuyerGroupListDataLoader $buyerGroupListDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBuyerGroupListDataLoader(BuyerGroupListDataLoader $buyerGroupListDataLoader): static
    {
        $this->buyerGroupListDataLoader = $buyerGroupListDataLoader;
        return $this;
    }
}
