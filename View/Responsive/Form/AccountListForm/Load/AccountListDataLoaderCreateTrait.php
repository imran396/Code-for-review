<?php
/**
 * Account List Data Loader Create Trait
 *
 * SAM-6289: Refactor Account List page at client side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AccountListForm\Load;

/**
 * Trait AccountListDataLoaderCreateTrait
 */
trait AccountListDataLoaderCreateTrait
{
    protected ?AccountListDataLoader $accountListDataLoader = null;

    /**
     * @return AccountListDataLoader
     */
    protected function createAccountListDataLoader(): AccountListDataLoader
    {
        $accountListDataLoader = $this->accountListDataLoader ?: AccountListDataLoader::new();
        return $accountListDataLoader;
    }

    /**
     * @param AccountListDataLoader $accountListDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setAccountListDataLoader(AccountListDataLoader $accountListDataLoader): static
    {
        $this->accountListDataLoader = $accountListDataLoader;
        return $this;
    }
}
