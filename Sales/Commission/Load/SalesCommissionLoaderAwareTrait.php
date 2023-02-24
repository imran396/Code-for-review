<?php
/**
 * Trait for SalesCommissionLoader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           10/26/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sales\Commission\Load;

/**
 * Trait SalesCommissionLoaderAwareTrait
 * @package Sam\Sales\Commission\Load
 */
trait SalesCommissionLoaderAwareTrait
{
    /**
     * @var SalesCommissionLoader|null
     */
    protected ?SalesCommissionLoader $salesCommissionLoader = null;

    /**
     * @return SalesCommissionLoader
     */
    protected function getSalesCommissionLoader(): SalesCommissionLoader
    {
        if ($this->salesCommissionLoader === null) {
            $this->salesCommissionLoader = SalesCommissionLoader::new();
        }
        return $this->salesCommissionLoader;
    }

    /**
     * @param SalesCommissionLoader $salesCommissionLoader
     * @return static
     * @internal
     */
    public function setSalesCommissionLoader(SalesCommissionLoader $salesCommissionLoader): static
    {
        $this->salesCommissionLoader = $salesCommissionLoader;
        return $this;
    }
}
