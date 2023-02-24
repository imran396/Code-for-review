<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 16, 2020
 * file encoding    UTF-83
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate\Internal\Load;


/**
 * Trait SettlementCalculatorDataLoaderCreateTrait
 * @package Sam\Settlement\Calculate\Internal\Load
 */
trait SettlementCalculatorDataLoaderCreateTrait
{
    protected ?SettlementCalculatorDataLoader $settlementCalculatorDataLoader = null;

    /**
     * @return SettlementCalculatorDataLoader
     */
    protected function createSettlementCalculatorDataLoader(): SettlementCalculatorDataLoader
    {
        return $this->settlementCalculatorDataLoader ?: SettlementCalculatorDataLoader::new();
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
