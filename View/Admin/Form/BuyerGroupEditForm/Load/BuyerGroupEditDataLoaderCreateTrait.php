<?php
/**
 * Buyer Group Edit Data Loader Create Trait
 *
 * SAM-5945: Refactor buyer group edit page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 24, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupEditForm\Load;

/**
 * Trait BuyerGroupEditDataLoaderCreateTrait
 */
trait BuyerGroupEditDataLoaderCreateTrait
{
    protected ?BuyerGroupEditDataLoader $buyerGroupEditDataLoader = null;

    /**
     * @return BuyerGroupEditDataLoader
     */
    protected function createBuyerGroupEditDataLoader(): BuyerGroupEditDataLoader
    {
        return $this->buyerGroupEditDataLoader ?: BuyerGroupEditDataLoader::new();
    }

    /**
     * @param BuyerGroupEditDataLoader $buyerGroupEditDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBuyerGroupEditDataLoader(BuyerGroupEditDataLoader $buyerGroupEditDataLoader): static
    {
        $this->buyerGroupEditDataLoader = $buyerGroupEditDataLoader;
        return $this;
    }
}
