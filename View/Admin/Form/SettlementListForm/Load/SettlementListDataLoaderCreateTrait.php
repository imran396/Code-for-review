<?php
/**
 * Settlement List Data Loader Create Trait
 *
 * SAM-6279: Refactor Settlement List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 10, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementListForm\Load;

/**
 * Trait SettlementListDataLoaderCreateTrait
 */
trait SettlementListDataLoaderCreateTrait
{
    protected ?SettlementListDataLoader $settlementListDataLoader = null;

    /**
     * @return SettlementListDataLoader
     */
    protected function createSettlementListDataLoader(): SettlementListDataLoader
    {
        $settlementListDataLoader = $this->settlementListDataLoader ?: SettlementListDataLoader::new();
        return $settlementListDataLoader;
    }

    /**
     * @param SettlementListDataLoader $settlementListDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setSettlementListDataLoader(SettlementListDataLoader $settlementListDataLoader): static
    {
        $this->settlementListDataLoader = $settlementListDataLoader;
        return $this;
    }
}
