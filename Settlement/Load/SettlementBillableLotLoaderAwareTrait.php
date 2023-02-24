<?php
/**
 *
 * SAM-4557: Settlement management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/12/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load;

/**
 * Trait SettlementBillableLotLoaderAwareTrait
 * @package Sam\Settlement\Load
 */
trait SettlementBillableLotLoaderAwareTrait
{
    protected ?SettlementBillableLotLoader $settlementBillableLotLoader = null;

    /**
     * @return SettlementBillableLotLoader
     */
    protected function getSettlementBillableLotLoader(): SettlementBillableLotLoader
    {
        if ($this->settlementBillableLotLoader === null) {
            $this->settlementBillableLotLoader = SettlementBillableLotLoader::new();
        }
        return $this->settlementBillableLotLoader;
    }

    /**
     * @param SettlementBillableLotLoader $settlementBillableLotLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setSettlementBillableLotLoader(SettlementBillableLotLoader $settlementBillableLotLoader): static
    {
        $this->settlementBillableLotLoader = $settlementBillableLotLoader;
        return $this;
    }
}
