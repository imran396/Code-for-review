<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate\Internal\Load;


/**
 * Trait SettlementSummaryDataLoaderCreateTrait
 * @package Sam\Settlement\Calculate\Internal\Load
 */
trait SettlementSummaryDataLoaderCreateTrait
{
    protected ?SettlementSummaryDataLoader $settlementSummaryDataLoader = null;

    /**
     * @return SettlementSummaryDataLoader
     */
    protected function createSettlementSummaryDataLoader(): SettlementSummaryDataLoader
    {
        return $this->settlementSummaryDataLoader ?: SettlementSummaryDataLoader::new();
    }

    /**
     * @param SettlementSummaryDataLoader $settlementSummaryDataLoader
     * @return static
     * @internal
     */
    public function setSettlementSummaryDataLoader(SettlementSummaryDataLoader $settlementSummaryDataLoader): static
    {
        $this->settlementSummaryDataLoader = $settlementSummaryDataLoader;
        return $this;
    }
}
