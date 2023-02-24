<?php
/**
 *
 * SAM-4559: SettlementItem Loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/7/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load;

/**
 * Trait SettlementItemLoaderAwareTrait
 * @package Sam\Settlement\Load
 */
trait SettlementItemLoaderAwareTrait
{
    protected ?SettlementItemLoader $settlementItemLoader = null;

    /**
     * @return SettlementItemLoader
     */
    protected function getSettlementItemLoader(): SettlementItemLoader
    {
        if ($this->settlementItemLoader === null) {
            $this->settlementItemLoader = SettlementItemLoader::new();
        }
        return $this->settlementItemLoader;
    }

    /**
     * @param SettlementItemLoader $settlementItemLoader
     * @return static
     * @internal
     */
    public function setSettlementItemLoader(SettlementItemLoader $settlementItemLoader): static
    {
        $this->settlementItemLoader = $settlementItemLoader;
        return $this;
    }
}
