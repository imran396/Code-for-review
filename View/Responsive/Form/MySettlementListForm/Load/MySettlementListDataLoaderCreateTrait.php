<?php
/**
 * My Settlement List Data Loader Create Trait
 *
 * SAM-6309: Refactor My Settlement List page at client side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 20, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MySettlementListForm\Load;

/**
 * Trait MySettlementListDataLoaderCreateTrait
 */
trait MySettlementListDataLoaderCreateTrait
{
    protected ?MySettlementListDataLoader $mySettlementListDataLoader = null;

    /**
     * @return MySettlementListDataLoader
     */
    protected function createMySettlementListDataLoader(): MySettlementListDataLoader
    {
        $mySettlementListDataLoader = $this->mySettlementListDataLoader ?: MySettlementListDataLoader::new();
        return $mySettlementListDataLoader;
    }

    /**
     * @param MySettlementListDataLoader $mySettlementListDataLoader
     * @return $this
     * @noinspection PhpUnused
     * @internal
     */
    public function setMySettlementListDataLoader(MySettlementListDataLoader $mySettlementListDataLoader): static
    {
        $this->mySettlementListDataLoader = $mySettlementListDataLoader;
        return $this;
    }
}
