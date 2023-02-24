<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-11-27
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load;

/**
 * Trait SettlementAdditionalLoaderAwareTrait
 * @package Sam\Settlement\Load
 */
trait SettlementAdditionalLoaderAwareTrait
{
    protected ?SettlementAdditionalLoader $settlementAdditionalLoader = null;

    /**
     * @return SettlementAdditionalLoader
     */
    protected function getSettlementAdditionalLoader(): SettlementAdditionalLoader
    {
        if ($this->settlementAdditionalLoader === null) {
            $this->settlementAdditionalLoader = SettlementAdditionalLoader::new();
        }
        return $this->settlementAdditionalLoader;
    }

    /**
     * @param SettlementAdditionalLoader $settlementAdditionalLoader
     * @return static
     * @internal
     */
    public function setSettlementAdditionalLoader(SettlementAdditionalLoader $settlementAdditionalLoader): static
    {
        $this->settlementAdditionalLoader = $settlementAdditionalLoader;
        return $this;
    }
}
