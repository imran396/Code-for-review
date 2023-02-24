<?php
/**
 * SAM-9889: Check Printing for Settlements: Searching, Filtering, Listing Checks (Part 3)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load;

/**
 * Trait SettlementAutocompleteDataProviderCreateTrait
 * @package Sam\Settlement\Load
 */
trait SettlementAutocompleteDataProviderCreateTrait
{
    protected ?SettlementAutocompleteDataProvider $settlementAutocompleteDataProvider = null;

    /**
     * @return SettlementAutocompleteDataProvider
     */
    protected function createSettlementAutocompleteDataProvider(): SettlementAutocompleteDataProvider
    {
        return $this->settlementAutocompleteDataProvider ?: SettlementAutocompleteDataProvider::new();
    }

    /**
     * @param SettlementAutocompleteDataProvider $settlementAutocompleteDataProvider
     * @return static
     * @internal
     */
    public function setSettlementAutocompleteDataProvider(SettlementAutocompleteDataProvider $settlementAutocompleteDataProvider): static
    {
        $this->settlementAutocompleteDataProvider = $settlementAutocompleteDataProvider;
        return $this;
    }
}