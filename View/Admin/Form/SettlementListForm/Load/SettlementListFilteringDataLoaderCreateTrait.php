<?php
/**
 * Settlement List Filtering Data Loader Create Trait
 * SAM-6279: Refactor Settlement List page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 28, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementListForm\Load;


/**
 * Trait SettlementListFilteringDataLoaderCreateTrait
 */
trait SettlementListFilteringDataLoaderCreateTrait
{
    protected ?SettlementListFilteringDataLoader $settlementListFilteringDataLoader = null;

    /**
     * @return SettlementListFilteringDataLoader
     */
    protected function createSettlementListFilteringDataLoader(): SettlementListFilteringDataLoader
    {
        return $this->settlementListFilteringDataLoader ?: SettlementListFilteringDataLoader::new();
    }

    /**
     * @param SettlementListFilteringDataLoader $settlementListFilteringDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setSettlementListFilteringDataLoader(SettlementListFilteringDataLoader $settlementListFilteringDataLoader): static
    {
        $this->settlementListFilteringDataLoader = $settlementListFilteringDataLoader;
        return $this;
    }
}
