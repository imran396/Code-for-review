<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate\Internal\Load;


/**
 * Trait SettlementCalculatorDataLoaderAwareTrait
 * @package Sam\Settlement\Calculate\Internal\Load
 */
trait SettlementCalculatorDataLoaderAwareTrait
{
    protected ?SettlementCalculatorDataLoader $settlementCalculatorDataLoader = null;

    /**
     * @return SettlementCalculatorDataLoader
     */
    protected function getSettlementCalculatorDataLoader(): SettlementCalculatorDataLoader
    {
        if ($this->settlementCalculatorDataLoader === null) {
            $this->settlementCalculatorDataLoader = SettlementCalculatorDataLoader::new();
        }
        return $this->settlementCalculatorDataLoader;
    }

    /**
     * @param SettlementCalculatorDataLoader $settlementCalculatorDataLoader
     * @return static
     * @internal
     */
    public function setSettlementCalculatorDataLoader(SettlementCalculatorDataLoader $settlementCalculatorDataLoader): static
    {
        $this->settlementCalculatorDataLoader = $settlementCalculatorDataLoader;
        return $this;
    }
}
