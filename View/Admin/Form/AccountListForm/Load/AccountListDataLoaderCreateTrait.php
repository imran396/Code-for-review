<?php
/**
 * SAM-5673: Refactor data loader for Account List datagrid at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AccountListForm\Load;

/**
 * Trait AccountListDataLoaderCreateTrait
 * @package Sam\View\Admin\Form\AccountListForm\Load
 */
trait AccountListDataLoaderCreateTrait
{
    protected ?AccountListDataLoader $accountListDataLoader = null;

    /**
     * @return AccountListDataLoader
     */
    protected function createAccountListDataLoader(): AccountListDataLoader
    {
        return $this->accountListDataLoader ?: AccountListDataLoader::new();
    }

    /**
     * @param AccountListDataLoader $accountListDataLoader
     * @return $this
     * @internal
     */
    public function setAccountListDataLoader(AccountListDataLoader $accountListDataLoader): static
    {
        $this->accountListDataLoader = $accountListDataLoader;
        return $this;
    }
}
