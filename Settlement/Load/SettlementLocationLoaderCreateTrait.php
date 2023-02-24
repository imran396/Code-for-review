<?php
/**
 * SAM-8016: Add 'City' as an attribute of Location
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Load;

/**
 * Trait SettlementLocationLoaderCreateTrait
 * @package Sam\Settlement\Load
 */
trait SettlementLocationLoaderCreateTrait
{
    protected ?SettlementLocationLoader $settlementLocationLoader = null;

    /**
     * @return SettlementLocationLoader
     */
    protected function createSettlementLocationLoader(): SettlementLocationLoader
    {
        return $this->settlementLocationLoader ?: SettlementLocationLoader::new();
    }

    /**
     * @param SettlementLocationLoader $settlementLocationLoader
     * @return static
     * @internal
     */
    public function setSettlementLocationLoader(SettlementLocationLoader $settlementLocationLoader): static
    {
        $this->settlementLocationLoader = $settlementLocationLoader;
        return $this;
    }
}
