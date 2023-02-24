<?php
/**
 * Buyer Group Add User Data Loader CreateTrait
 *
 * SAM-5938: Refactor buyer group add user page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupAddUserForm\Load;

/**
 * Trait BuyerGroupAddUSerDataLoaderCreateTrait
 */
trait BuyerGroupAddUserDataLoaderCreateTrait
{
    protected ?BuyerGroupAddUserDataLoader $buyerGroupAddUserDataLoader = null;

    /**
     * @return BuyerGroupAddUserDataLoader
     */
    protected function createBuyerGroupAddUserDataLoader(): BuyerGroupAddUserDataLoader
    {
        return $this->buyerGroupAddUserDataLoader ?: BuyerGroupAddUserDataLoader::new();
    }

    /**
     * @param BuyerGroupAddUserDataLoader $dataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBuyerGroupAddUserDataLoader(BuyerGroupAddUserDataLoader $dataLoader): static
    {
        $this->buyerGroupAddUserDataLoader = $dataLoader;
        return $this;
    }
}
