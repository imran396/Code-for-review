<?php
/**
 * Trait for Settlement Loader
 *
 *  SAM-4339: Settlement Loader class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since          Jul 21, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load;

/**
 * Trait SettlementLoaderAwareTrait
 * @package Sam\Settlement\Load
 */
trait SettlementLoaderAwareTrait
{
    protected ?SettlementLoader $settlementLoader = null;

    /**
     * @param SettlementLoader $settlementLoader
     * @return static
     * @internal
     */
    public function setSettlementLoader(SettlementLoader $settlementLoader): static
    {
        $this->settlementLoader = $settlementLoader;
        return $this;
    }

    /**
     * @return SettlementLoader
     */
    protected function getSettlementLoader(): SettlementLoader
    {
        if ($this->settlementLoader === null) {
            $this->settlementLoader = SettlementLoader::new();
        }
        return $this->settlementLoader;
    }
}
